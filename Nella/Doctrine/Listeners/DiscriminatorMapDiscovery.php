<?php
/**
 * This file is part of the Nella Framework (http://nellafw.org).
 *
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2006, 2012 Patrik Votoček (http://patrik.votocek.cz)
 * Copyright (c) 2008, 2011 Filip Procházka (filip.prochazka@kdyby.org)
 *
 * @license http://www.kdyby.org/license
 *
 * For the full copyright and license information,
 * please view the file LICENSE.txt that was distributed with this source code.
 */

namespace Nella\Doctrine\Listeners;

use Nette\Reflection\ClassType,
	Doctrine\Common\Annotations\Reader,
	Doctrine\ORM\Events,
	Doctrine\Common\Persistence\Mapping\Driver\MappingDriver,
	Doctrine\ORM\Event\LoadClassMetadataEventArgs;

/**
 * Discriminator map discovery
 *
 * Support for defining discriminator maps at Child-level
 *
 * @author	Patrik Votoček
 * @author	Filip Procházka
 */
class DiscriminatorMapDiscovery extends \Nette\Object implements \Doctrine\Common\EventSubscriber
{
	/** @var \Doctrine\Common\Annotations\Reader */
	private $reader;

	/**
	 * @param \Doctrine\Common\Annotations\Reader
	 */
	public function __construct(Reader $reader)
	{
		$this->reader = $reader;
	}

	/**
	 * @return array
	 */
	public function getSubscribedEvents()
	{
		return array(
			Events::loadClassMetadata,
		);
	}

	/**
	 * @param \Doctrine\ORM\Event\LoadClassMetadataEventArgs
	 */
	public function loadClassMetadata(LoadClassMetadataEventArgs $args)
	{
		$meta = $args->getClassMetadata();
		$driver = $args->getEntityManager()->getConfiguration()->getMetadataDriverImpl();

		if ($meta->isInheritanceTypeNone()) {
			return;
		}

		$map = $meta->discriminatorMap;
		foreach ($this->getChildClasses($driver, $meta->name) as $className) {
			if (!in_array($className, $meta->discriminatorMap) && $entry = $this->getEntryName($className)) {
				$map[$entry->name] = $className;
			}
		}

		$meta->setDiscriminatorMap($map);
		$meta->subClasses = array_unique($meta->subClasses); // really? may array_values($map)
	}

	/**
	 * @param \Doctrine\Common\Persistence\Mapping\Driver\MappingDriver
	 * @param string
	 * @return array
	 */
	private function getChildClasses(MappingDriver $driver, $currentClass)
	{
		$classes = array();
		foreach ($driver->getAllClassNames() as $className) {
			if (!ClassType::from($className)->isSubclassOf($currentClass)) {
				continue;
			}

			$classes[] = $className;
		}
		return $classes;
	}

	/**
	 * @param string
	 * @return string|NULL
	 */
	private function getEntryName($className)
	{
		return $this->reader->getClassAnnotation(
			ClassType::from($className), 'Doctrine\ORM\Mapping\DiscriminatorEntry'
		) ? : NULL;
	}
}


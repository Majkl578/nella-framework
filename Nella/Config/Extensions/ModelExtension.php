<?php
/**
 * This file is part of the Nella Framework (http://nellafw.org).
 *
 * Copyright (c) 2006, 2012 Patrik Votoček (http://patrik.votocek.cz)
 *
 * For the full copyright and license information,
 * please view the file LICENSE.txt that was distributed with this source code.
 */

namespace Nella\Config\Extensions;

use Doctrine\ORM\EntityManager;

/**
 * Model extension
 *
 * Registering default facade services
 *
 * @author	Patrik Votoček
 */
class ModelExtension extends \Nette\Config\CompilerExtension
{
	const SERVICES_KEY = 'services';

	/** @var array */
	public $defaults = array(
		self::SERVICES_KEY => array(
			'media.file' => '@media.fileFacade',
			'media.image' => '@media.imageFacade',
			'media.imageFormat' => '@media.imageFormatFacade',
		)
	);

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		if (!isset($config['entityManager'])) {
			throw new \Nette\InvalidStateException('Model extension entity manager not set');
		}

		$builder->addDefinition($this->prefix('entityManager'))
			->setClass('Doctrine\ORM\EntityManager')
			->setFactory($config['entityManager'])
			->setAutowired(FALSE);
		unset($config['entityManager']);

		foreach ($config[self::SERVICES_KEY] as $name => $def) {
			\Nette\Config\Compiler::parseService($builder->addDefinition($this->prefix($name)), $def, FALSE);
		}
		unset($config[self::SERVICES_KEY]);

		foreach ($config as $name => $data) {
			$this->setupItem($builder, $name, $data);
		}
	}

	/**
	 * @param \Nette\DI\ContainerBuilder
	 * @param string
	 * @param mixed
	 * @param string|NULL
	 */
	protected function setupItem(\Nette\DI\ContainerBuilder $builder, $name, $data, $parent = NULL)
	{
		$fullname = $parent ? ("$parent.$name") : $name;

		if (is_array($data) && !isset($data['entity'])) {
			$builder->addDefinition($this->prefix($fullname))
				->setClass('Nette\DI\NestedAccessor', array('@container', $this->prefix($fullname)));
			foreach ($data as $name => $item) {
				$this->setupItem($builder, $name, $item, $fullname);
			}
		} elseif (is_array($data) && isset($data['entity'])) {
			$params = array($this->prefix('@entityManager'), $data['entity'], NULL);
			if (isset($data['service'])) {
				$params[2] = $data['service'];
			}
			if (isset($data['class'])) {
				$params[3] = $data['class'];
			}

			$def = $builder->addDefinition($this->prefix($fullname));
			$def->setClass('Nella\Doctrine\Facade')
				->setFactory(get_called_class().'::factory', $params);
			if (isset($data['setup'])) {
				foreach ($data['setup'] as $setup) {
					$def->addSetup($setup->value, $setup->attributes);
				}
			}
		} elseif (is_string($data) && \Nette\Utils\Strings::startsWith($data, '@')) {
			$builder->addDefinition($this->prefix($fullname))->setClass('Nella\Doctrine\Facade')->setFactory($data);
		} elseif (is_string($data) && class_exists($data)) {
			$builder->addDefinition($this->prefix($fullname))
				->setClass('Nella\Doctrine\Facade')
				->setFactory(get_called_class().'::factory', array($this->prefix('@entityManager'), $data));
		} else {
			$builder->addDefinition($this->prefix($fullname))
				->setClass('Nella\Doctrine\Facade')
				->setFactory($data);
		}
	}

	/**
	 * @param \Doctrine\ORM\EntityManager
	 * @param string
	 * @param object|NULL
	 * @param string
	 * @return object
	 */
	public static function factory(EntityManager $em, $entity, $service = NULL, $class = 'Nella\Doctrine\Facade')
	{
		$ref = \Nette\Reflection\ClassType::from($class);
		return $ref->newInstanceArgs(array(
			'em' => $em,
			'repository' => $em->getRepository($entity),
			'service' => $service,
		));
	}
}


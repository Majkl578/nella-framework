<?php
/**
 * This file is part of the Nella Framework (http://nellafw.org).
 *
 * Copyright (c) 2006, 2012 Patrik Votoček (http://patrik.votocek.cz)
 *
 * For the full copyright and license information, please view the file LICENSE.txt that was distributed with this source code.
 */

namespace NellaTests\Forms;

use Nella\Forms\MultiplerContainer;

class MultiplerContainerTest extends \Nella\Testing\TestCase
{
	/** @var \Nella\Forms\MultiplerContainer */
	private $container;

	public function setup()
	{
		$this->container = new MultiplerContainer;
	}

	public function testInstance()
	{
		$this->assertInstanceOf('Nette\Forms\Container', $this->container, 'is Nette\Forms\Container instance');
		$this->assertInstanceOf('Nella\Forms\Container', $this->container, 'is Nella\Forms\Container instance');
	}

	public function testAddRemoveContainerButton()
	{
		$this->container->addRemoveContainerButton("Remove container");

		$this->assertTrue(isset($this->container[MultiplerContainer::REMOVE_CONTAINER_BUTTON_ID]), "is button exist");
		$this->assertInstanceOf(
			'Nette\Forms\Controls\SubmitButton', $this->container[MultiplerContainer::REMOVE_CONTAINER_BUTTON_ID],
			"is button valid type"
		);
	}
}
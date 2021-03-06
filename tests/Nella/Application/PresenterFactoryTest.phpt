<?php
/**
 * Test: Nella\Application\PresenterFactory
 *
 * This file is part of the Nella Framework (http://nellafw.org).
 *
 * Copyright (c) 2006, 2012 Patrik Votoček (http://patrik.votocek.cz)
 *
 * For the full copyright and license information, please view the file LICENSE.txt that was distributed with this source code.
 *
 * @testcase
 */

namespace Nella\Tests\Application;

use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

class PresenterFactoryTest extends \Tester\TestCase
{
	/** @var \Nella\Application\PresenterFactory */
	private $loader;

	public function setUp()
	{
		parent::setUp();

		$this->loader = new \Nella\Application\PresenterFactory(new \Nette\DI\Container);
		$this->loader->addNamespace('App', 2);
		$this->loader->addNamespace('Nella');
		$this->loader->useModuleSuffix = FALSE;
	}

	public function dataFormatPresenterClass()
	{
		return array(
			array('Foo', 'App\FooPresenter'),
			array('Foo:Bar', 'App\Foo\BarPresenter'),
			array('Foo:Bar:Baz', 'App\Foo\Bar\BazPresenter'),
			array('Foo', 'Nella\FooPresenter', 'Nella'),
			array('Foo:Bar', 'Nella\Foo\BarPresenter', 'Nella'),
			array('Foo:Bar:Baz', 'Nella\Foo\Bar\BazPresenter', 'Nella'),
			array('Nette:Micro', 'NetteModule\MicroPresenter'),
		);
	}

	/**
	 * @dataProvider dataFormatPresenterClass
	 */
	public function testFormatPresenterClass($presenter, $class, $namespace = 'App')
	{
		Assert::equal($class, $this->loader->formatPresenterClass($presenter, $namespace), "->formatPresenterClass('$presenter')");
	}

	public function dataFormatPresenterClassModule()
	{
		return array(
			array('Foo', 'App\FooPresenter'),
			array('Foo:Bar', 'App\FooModule\BarPresenter'),
			array('Foo:Bar:Baz', 'App\FooModule\BarModule\BazPresenter'),
			array('Foo', 'Nella\FooPresenter', 'Nella'),
			array('Foo:Bar', 'Nella\FooModule\BarPresenter', 'Nella'),
			array('Foo:Bar:Baz', 'Nella\FooModule\BarModule\BazPresenter', 'Nella'),
			array('Nette:Micro', 'NetteModule\MicroPresenter'),
		);
	}

	/**
	 * @dataProvider dataFormatPresenterClassModule
	 */
	public function testFormatPresenterClassModule($presenter, $class, $namespace = 'App')
	{
		$this->loader->useModuleSuffix = TRUE;
		Assert::equal($class, $this->loader->formatPresenterClass($presenter, $namespace), "->formatPresenterClass('$presenter')");
	}

	public function dataUnformatPresenterClass()
	{
		return array(
			array('App\FooPresenter', 'Foo'),
			array('App\Foo\BarPresenter', 'Foo:Bar'),
			array('App\Foo\Bar\BazPresenter', 'Foo:Bar:Baz'),
			array('Nella\FooPresenter', 'Foo'),
			array('Nella\Foo\BarPresenter', 'Foo:Bar'),
			array('Nella\Foo\Bar\BazPresenter', 'Foo:Bar:Baz'),
			array('NetteModule\MicroPresenter', 'Nette:Micro'),
		);
	}

	/**
	 * @dataProvider dataUnformatPresenterClass
	 */
	public function testUnformatPresenterClass($class, $presenter)
	{
		Assert::equal($presenter, $this->loader->unformatPresenterClass($class), "->unformatPresenterClass('$class')");
	}

	public function dataUnformatPresenterClassModule()
	{
		return array(
			array('App\FooPresenter', 'Foo'),
			array('App\FooModule\BarPresenter', 'Foo:Bar'),
			array('App\FooModule\BarModule\BazPresenter', 'Foo:Bar:Baz'),
			array('Nella\FooPresenter', 'Foo'),
			array('Nella\FooModule\BarPresenter', 'Foo:Bar'),
			array('Nella\FooModule\BarModule\BazPresenter', 'Foo:Bar:Baz'),
			array('NetteModule\MicroPresenter', 'Nette:Micro'),
		);
	}

	/**
	 * @dataProvider dataUnformatPresenterClassModule
	 */
	public function testUnformatPresenterClassModule($class, $presenter)
	{
		$this->loader->useModuleSuffix = TRUE;
		Assert::equal($presenter, $this->loader->unformatPresenterClass($class), "->unformatPresenterClass('$class')");
	}

	public function dataGetPresenterClass()
	{
		return array(
			array('Foo', 'Nella\Tests\Application\PresenterFactory\FooPresenter'),
			array('Bar:Foo', 'Nella\Tests\Application\PresenterFactory\Bar\FooPresenter'),
			array('My', 'Nella\Tests\Application\PresenterFactoryTest\MyPresenter'),
			array('Foo:My', 'Nella\Tests\Application\PresenterFactoryTest\Foo\MyPresenter'),
		);
	}

	/**
	 * @dataProvider dataGetPresenterClass
	 */
	public function testGetPresenterClass($presenter, $class)
	{
		$this->loader->addNamespace('Nella\Tests\Application\PresenterFactory', 1);
		$this->loader->addNamespace('Nella\Tests\Application\PresenterFactoryTest', 1);
		Assert::equal($class, $this->loader->getPresenterClass($presenter), "->getPresenterClass('$presenter')");
	}

	public function testGetPresenterClassException1()
	{
		$loader = $this->loader;
		Assert::throws(function() use($loader) {
			$name = NULL;
			$loader->getPresenterClass($name);
		}, 'Nette\Application\InvalidPresenterException');
	}

	public function testGetPresenterClassException2()
	{
		$loader = $this->loader;
		Assert::throws(function() use($loader) {
			$name = 'Bar';
			$loader->getPresenterClass($name);
		}, 'Nette\Application\InvalidPresenterException');
	}
	public function testGetPresenterClassException3()
	{
		$loader = $this->loader;
		Assert::throws(function() use($loader) {
			$name = 'Baz';
			$loader->getPresenterClass($name);
		}, 'Nette\Application\InvalidPresenterException');
	}

	public function testGetPresenterClassException4()
	{
		$loader = $this->loader;
		Assert::throws(function() use($loader) {
			$name = 'Bar';
			$loader->getPresenterClass($name);
		}, 'Nette\Application\InvalidPresenterException');
	}
}

namespace Nella\Tests\Application\PresenterFactory;

class FooPresenter extends \Nette\Application\UI\Presenter { }

namespace Nella\Tests\Application\PresenterFactory\Bar;

class FooPresenter extends \Nette\Application\UI\Presenter { }

namespace Nella\Tests\Application\PresenterFactoryTest;

class MyPresenter extends \Nette\Application\UI\Presenter { }

namespace Nella\Tests\Application\PresenterFactoryTest\Foo;

class MyPresenter extends \Nette\Application\UI\Presenter { }

id(new \Nella\Tests\Application\PresenterFactoryTest)->run(isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : NULL);

<?php
/**
 * Test: Nella\Forms\Controls\DateTime
 *
 * This file is part of the Nella Framework (http://nellafw.org).
 *
 * Copyright (c) 2006, 2012 Patrik Votoček (http://patrik.votocek.cz)
 *
 * For the full copyright and license information, please view the file LICENSE.txt that was distributed with this source code.
 *
 * @testcase
 */

namespace Nella\Tests\Forms\Controls;

use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

class DateTimeTest extends \Tester\TestCase
{
	/** @var \Nella\Forms\Controls\DateTime */
	private $item;

	public function setUp()
	{
		parent::setUp();
		$form = new \Nella\Forms\Form;
		$form['foo'] = $this->item = new \Nella\Forms\Controls\DateTime("foo");
	}

	public function testType()
	{
		Assert::equal("datetime", $this->item->control->type, "datetime type");
	}

	public function testValues()
	{
		$dt = new \DateTime();
		Assert::null($this->item->getValue(), "is default NULL");
		$this->item->setValue($dt);
		Assert::true($this->item->getValue() instanceof \DateTime, "test value getter returns DateTime object");
		Assert::equal($dt->format("Y-m-d H:i"), $this->item->getValue()->format("Y-m-d H:i"), "test value getter (previous set with setter)");
		$this->item->value = NULL;
		Assert::null($this->item->value, "test value property getter (previous set with property setter)");
	}

	public function testValidate()
	{
		Assert::false($this->item->isFilled(), "validate empty value");
		$this->item->value = new \DateTime;
		Assert::true($this->item->isFilled(), "validate value");
		$this->item->value = "test";
		Assert::false($this->item->isFilled(), "validate invalid value");
	}
}

id(new DateTimeTest)->run(isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : NULL);

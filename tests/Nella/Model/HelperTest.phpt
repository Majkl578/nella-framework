<?php
/**
 * Test: Nella\Model\Helper
 *
 * This file is part of the Nella Framework (http://nellafw.org).
 *
 * Copyright (c) 2006, 2012 Patrik Votoček (http://patrik.votocek.cz)
 *
 * For the full copyright and license information, please view the file LICENSE.txt that was distributed with this source code.
 *
 * @testcase
 */

namespace Nella\Tests\Model;

use Tester\Assert,
	Nella\Model\Helper;

require_once __DIR__ . '/../../bootstrap.php';

class HelperTest extends \Tester\TestCase
{
	public function dataConvertException()
	{
		$dupl1 = new \PDOException('foo', 23000);
		$dupl1->errorInfo = array(23000, 1062);

		$empty1 = new \PDOException('bar', 23000);
		$empty1->errorInfo = array(23000, 1048, "error in 'test'");

		$other = new \PDOException('baz', 23000);
		$other->errorInfo = array(23000, 999999);

		return array(
			array($dupl1, 'Nella\Model\DuplicateEntryException', $dupl1->getMessage()),
			array($empty1, 'Nella\Model\EmptyValueException', $empty1->getMessage()),
			array($other, 'Nella\Model\Exception', $other->getMessage()),
		);
	}

	/**
	 * @dataProvider dataConvertException
	 */
	public function testConvertException($exception, $class, $message)
	{
		try {
			Helper::convertException($exception);
		} catch (\Nella\Model\Exception $e) {
			Assert::same($exception, $e->getPrevious());
		}

		Assert::exception(function() use($exception) {
			Helper::convertException($exception);
		}, $class, $message);
	}

	public function testAdvancedEmptyValueException()
	{
		$exception = new \PDOException('bar', 23000);
		$exception->errorInfo = array(23000, 1048, "error in 'test'");

		try {
			Helper::convertException($exception);
		} catch (\Nella\Model\EmptyValueException $e) {
			Assert::equal('test', $e->getColumn());
		}
	}
}

id(new HelperTest)->run(isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : NULL);

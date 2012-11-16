<?php
/**
 * Test: Nella\Latte\Macros\UIMacros
 *
 * This file is part of the Nella Framework (http://nellafw.org).
 *
 * Copyright (c) 2006, 2012 Patrik Votoček (http://patrik.votocek.cz)
 *
 * For the full copyright and license information, please view the file LICENSE.txt that was distributed with this source code.
 *
 * @testcase
 */

namespace Nella\Tests\Latte\Macros;

use Tester\Assert,
	Nette\Latte\Compiler,
	Nette\Latte\Parser;

require_once __DIR__ . '/../../../bootstrap.php';

class UIMacrosTest extends \Tester\TestCase
{
	/** @var \Nette\Latte\Compiler */
	private $compiler;
	/** @var \Nette\Latte\Parser */
	private $parser;

	protected function setUp()
	{
		$this->compiler = new Compiler;
		$this->compiler->setContext(Compiler::CONTENT_HTML);
		$this->parser = new Parser;
		$this->parser->setContext(Parser::CONTEXT_TEXT);
		\Nella\Latte\Macros\UIMacros::install($this->compiler);
		\Nette\Diagnostics\Debugger::$maxLen = 4096;
	}

	public function dataPhref()
	{
		return array(
			array(':Homepage:default', '":Homepage:default"'),
			array(':Foo: show, 13', "\":Foo:\", array('show', 13)"),
			array('Foo:Bar:baz show => detail, id => 13', "\"Foo:Bar:baz\", array('show' => 'detail', 'id' => 13)"),
		);
	}

	/**
	 * @dataProvider dataPhref
	 */
	public function testPhref($input, $output)
	{
		$data = '<a n:phref="'.$input.'">Link</a>';
		$expected = '<a<?php  ?> href="<?php echo htmlSpecialChars($_presenter->link('.$output.')) ?>"<?php  ?>>Link</a>';

		$tokens = $this->parser->parse($data);
		$actual = $this->compiler->compile($tokens);
		Assert::equal($expected, $actual, $input);
	}
}

id(new UIMacrosTest)->run(isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : NULL);

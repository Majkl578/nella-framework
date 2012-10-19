<?php
/**
 * This file is part of the Nella Framework (http://nellafw.org).
 *
 * Copyright (c) 2006, 2012 Patrik Votoček (http://patrik.votocek.cz)
 *
 * This source file is subject to the GNU Lesser General Public License. For more information please see http://nella-project.com
 */

namespace NellaTests\Media\Latte;

use Nette\Latte\Compiler,
	Nette\Latte\Parser,
	Nette\Latte\MacroNode;

class MediaMacrosTest extends \Nella\Testing\TestCase
{
	/** @var \Nette\Latte\Compiler */
	private $compiler;
	/** @var \Nette\Latte\Parser */
	private $parser;
	/** @var \Nette\Latte\IMacro */
	private $macros;

	protected function setup()
	{
		$this->compiler = new Compiler;
		$this->compiler->setContext(Compiler::CONTENT_HTML);
		$this->parser = new Parser;
		$this->parser->setContext(Parser::CONTEXT_TEXT);
		\Nella\Media\Latte\MediaMacros::install($this->compiler);
		\Nette\Diagnostics\Debugger::$maxLen = 4096;
	}

	public function dataFile()
	{
		return array(
			array('{file $x}', array('$x', "\$x instanceof \\Nella\\Media\\IFile ? pathinfo(\$x->getPath(), PATHINFO_EXTENSION) : NULL")),
			array('{file 1, "zip"}', array(1, '"zip"')),
		);
	}

	/**
	 * @dataProvider dataFile
	 */
	public function testFile($macro, $data)
	{
		$mask = "<?php echo Nette\\Templating\\Helpers::escapeHtml(\$_presenter->link(':Nette:Micro:', array('file'=>%file%,'ext'=>%ext%)), ENT_NOQUOTES) ?>\n";
		$expected = str_replace(array('%file%', '%ext%'), $data, $mask);

		$tokens = $this->parser->parse($macro);
		$actual = $this->compiler->compile($tokens);
		$this->assertEquals($expected, $actual, $macro);
	}

	public function dataFhref()
	{
		return array(
			array('<a n:fhref="$x">Foo</a>', array('$x', "\$x instanceof \\Nella\\Media\\IFile ? pathinfo(\$x->getPath(), PATHINFO_EXTENSION) : NULL")),
			array('<a n:fhref="1, zip">Foo</a>', array(1, '"zip"')),
		);
	}

	/**
	 * @dataProvider dataFhref
	 */
	public function testFhref($macro, $data)
	{
		$mask = "<a<?php  ?> href=\"<?php echo htmlSpecialChars(\$_presenter->link(':Nette:Micro:', array('file'=>%file%,'ext'=>%ext%))) ?>\"<?php  ?>>Foo</a>";
		$expected = str_replace(array('%file%', '%ext%'), $data, $mask);

		$tokens = $this->parser->parse($macro);
		$actual = $this->compiler->compile($tokens);
		$this->assertEquals($expected, $actual, $macro);
	}



	public function dataImage()
	{
		return array(
			array('{image $x, $y}', array('$x', '$y', "\$x instanceof \\Nella\\Media\\IImage ? \$x->getImageType() : 'jpg'")),
			array('{image $x, $y, png}', array('$x', '$y', '"png"')),
			array('{image $x, $y, \'gif\'}', array('$x', '$y', "'gif'")),
			array('{image $x, $y, "jpg"}', array('$x', '$y', '"jpg"')),
			array('{image 1, 2}', array('1', '2', "'jpg'")),
			array('{image 1, test}', array('1', '"test"', "'jpg'")),
			array('{image 1, "test"}', array('1', '"test"', "'jpg'")),
			array('{image 1, \'test\'}', array('1', "'test'", "'jpg'")),
			array('{img $x, $y}', array('$x', '$y', "\$x instanceof \\Nella\\Media\\IImage ? \$x->getImageType() : 'jpg'")),
			array('{img $x, $y, png}', array('$x', '$y', '"png"')),
			array('{img $x, $y, \'gif\'}', array('$x', '$y', "'gif'")),
			array('{img $x, $y, "jpg"}', array('$x', '$y', '"jpg"')),
			array('{img 1, 2}', array('1', '2', "'jpg'")),
			array('{img 1, test}', array('1', '"test"', "'jpg'")),
			array('{img 1, "test"}', array('1', '"test"', "'jpg'")),
			array('{img 1, \'test\'}', array('1', "'test'", "'jpg'")),
		);
	}

	/**
	 * @dataProvider dataImage
	 */
	public function testImage($macro, $data)
	{
		$mask = "<?php echo Nette\\Templating\\Helpers::escapeHtml(\$_presenter->link(':Nette:Micro:', array('image'=>%image%,'format'=>%format%,'type'=>%type%)), ENT_NOQUOTES) ?>\n";
		$expected = str_replace(array('%image%', '%format%', '%type%'), $data, $mask);

		$tokens = $this->parser->parse($macro);
		$actual = $this->compiler->compile($tokens);
		$this->assertEquals($expected, $actual, $macro);
	}

	public function dataSrc()
	{
		return array(
			array('<img n:src="$x, $y">', array('$x', '$y', "\$x instanceof \\Nella\\Media\\IImage ? \$x->getImageType() : 'jpg'")),
			array('<img n:src="$x, $y, png">', array('$x', '$y', '"png"')),
			array('<img n:src="$x, $y, \'gif\'">', array('$x', '$y', "'gif'")),
			array('<img n:src="1, 2">', array('1', '2', "'jpg'")),
			array('<img n:src="1, test">', array('1', '"test"', "'jpg'")),
			array('<img n:src="1, \'test\'">', array('1', "'test'", "'jpg'")),
		);
	}

	/**
	 * @dataProvider dataSrc
	 */
	public function testSrc($macro, $data)
	{
		$mask = "<img <?php  ?> src=\"<?php echo htmlSpecialChars(\$_presenter->link(':Nette:Micro:', array('image'=>%image%,'format'=>%format%,'type'=>%type%))) ?>\"<?php  ?>/>";
		$expected = str_replace(array('%image%', '%format%', '%type%'), $data, $mask);

		$tokens = $this->parser->parse($macro);
		$actual = $this->compiler->compile($tokens);
		$this->assertEquals($expected, $actual, $macro);
	}

	public function dataIhref()
	{
		return array(
			array('<a n:ihref="$x, $y">Foo</a>', array('$x', '$y', "\$x instanceof \\Nella\\Media\\IImage ? \$x->getImageType() : 'jpg'")),
			array('<a n:ihref="$x, $y, png">Foo</a>', array('$x', '$y', '"png"')),
			array('<a n:ihref="$x, $y, \'gif\'">Foo</a>', array('$x', '$y', "'gif'")),
			array('<a n:ihref="1, 2">Foo</a>', array('1', '2', "'jpg'")),
			array('<a n:ihref="1, test">Foo</a>', array('1', '"test"', "'jpg'")),
			array('<a n:ihref="1, \'test\'">Foo</a>', array('1', "'test'", "'jpg'")),
		);
	}

	/**
	 * @dataProvider dataIhref
	 */
	public function testIhref($macro, $data)
	{
		$mask = "<a<?php  ?> href=\"<?php echo htmlSpecialChars(\$_presenter->link(':Nette:Micro:', array('image'=>%image%,'format'=>%format%,'type'=>%type%))) ?>\"<?php  ?>>Foo</a>";
		$expected = str_replace(array('%image%', '%format%', '%type%'), $data, $mask);

		$tokens = $this->parser->parse($macro);
		$actual = $this->compiler->compile($tokens);
		$this->assertEquals($expected, $actual, $macro);
	}
}
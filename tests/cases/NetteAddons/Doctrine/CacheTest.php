<?php
/**
 * This file is part of the Nella Framework.
 *
 * Copyright (c) 2006, 2012 Patrik Votoček (http://patrik.votocek.cz)
 *
 * This source file is subject to the GNU Lesser General Public License. For more information please see http://nellacms.com
 */

namespace NellaTests\NetteAddons\Doctrine;

class CacheTest extends \Nella\Testing\TestCase
{
	/** @var \Nella\NetteAddons\Doctine\Cache */
	private $cache;

	public function setup()
	{
		parent::setup();
		$this->cache = new \Nella\NetteAddons\Doctrine\Cache(new \Nette\Caching\Storages\MemoryStorage);
	}

	public function testDefault()
	{
		$this->assertFalse($this->cache->contains('foo'), "default is emtpy");

		$this->cache->save('foo', "test");
		$this->assertTrue($this->cache->contains('foo'), "->contains('foo')");
		$this->assertEquals("test", $this->cache->fetch('foo'), "->load('foo')");

		$this->cache->delete('foo');
		$this->assertFalse($this->cache->contains('foo'), "->contains('foo') - removed");
	}
}
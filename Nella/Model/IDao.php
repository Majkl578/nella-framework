<?php
/**
 * This file is part of the Nella Framework (http://nellafw.org).
 *
 * Copyright (c) 2006, 2012 Patrik Votoček (http://patrik.votocek.cz)
 *
 * For the full copyright and license information, 
 * please view the file LICENSE.txt that was distributed with this source code.
 */

namespace Nella\Model;

/**
 * Dao interface
 *
 * @author	Patrik Votoček
 */
interface IDao
{
	const FLUSH = FALSE,
		NO_FLUSH = TRUE;

	/**
	 * @param object
	 * @param bool
	 */
	public function save($entity, $withoutFlush = self::FLUSH);

	/**
	 * @param object
	 * @param bool
	 */
	public function remove($entity, $withoutFlush = self::FLUSH);

	/**
	 * @param mixed
	 * @return object
	 */
	public function find($id);

	/**
	 * @return array
	 */
	public function findAll();

	/**
	 * @param array
	 * @return object|NULL
	 */
	public function findOneBy(array $criteria);

	/**
	 * @param array
	 * @param array|NULL
	 * @param int|NULL
	 * @param int|NULL
	 * @return array
	 */
	public function findBy(array $criteria, array $orderBy = NULL, $limit = NULL, $offset = NULL);
}


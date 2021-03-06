<?php
/**
 * This file is part of the Nella Framework (http://nellafw.org).
 *
 * Copyright (c) 2006, 2012 Patrik Votoček (http://patrik.votocek.cz)
 *
 * For the full copyright and license information, please view the file LICENSE.txt that was distributed with this source code.
 */

namespace Nella\Mocks\Security;

class UserStorage extends \Nette\Object implements \Nette\Security\IUserStorage
{
	/**
	 * Sets the authenticated status of this user.
	 * @param  bool
	 * @return void
	 */
	function setAuthenticated($state)
	{
		return $this;
	}

	/**
	 * Is this user authenticated?
	 * @return bool
	 */
	function isAuthenticated()
	{
		return TRUE;
	}

	/**
	 * Sets the user identity.
	 * @return void
	 */
	function setIdentity(\Nette\Security\IIdentity $identity = NULL)
	{
		return $this;
	}

	/**
	 * Returns current user identity, if any.
	 * @return \Nette\Security\IIdentity|NULL
	 */
	function getIdentity()
	{
		return new \Nette\Security\Identity('foo', array('admin'));
	}

	/**
	 * Enables log out from the persistent storage after inactivity.
	 * @param string|int|DateTime number of seconds or timestamp
	 * @param int Log out when the browser is closed | Clear the identity from persistent storage?
	 * @return void
	 */
	function setExpiration($time, $flags = 0)
	{
		return $this;
	}

	/**
	 * Why was user logged out?
	 * @return int
	 */
	function getLogoutReason()
	{
		return NULL;
	}

}

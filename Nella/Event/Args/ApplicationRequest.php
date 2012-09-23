<?php
/**
 * This file is part of the Nella Framework (http://nellafw.org).
 *
 * Copyright (c) 2006, 2012 Patrik Votoček (http://patrik.votocek.cz)
 *
 * For the full copyright and license information, 
 * please view the file LICENSE.txt that was distributed with this source code.
 */

namespace Nella\Event\Args;

use Nette\Application\Application as NApplication,
	Nette\Application\Request;

/**
 * Application request args
 * 
 * @author Michael Moravec
 * 
 * @property \Nette\Application\Request
 */
class ApplicationRequest extends Application
{
	/** @var \Nette\Application\Request */
	private $request;

	/**
	 * @param \Nette\Appliaction\Application
	 * @param \Nette\Application\Request
	 */
	public function __construct(NApplication $application, Request $request)
	{
		parent::__construct($application);
		$this->request = $request;
	}

	/**
	 * @return \Nette\Application\Request
	 */
	public function getRequest()
	{
		return $this->request;
	}
}


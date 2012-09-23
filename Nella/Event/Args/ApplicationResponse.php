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
	Nette\Application\IResponse;

/**
 * Application response args
 * 
 * @author Michael Moravec
 * 
 * @property-read \Nette\Application\IResponse $response
 */
class ApplicationResponse extends Application
{
	/** @var \Nette\Application\IResponse */
	private $response;

	/**
	 * @param \Nette\Application\Application
	 * @param \Nette\Application\IResponse
	 */
	public function __construct(NApplication $application, IResponse $response)
	{
		parent::__construct($application);
		$this->response = $response;
	}

	/**
	 * @return \Nette\Application\IResponse
	 */
	public function getResponse()
	{
		return $this->response;
	}
}


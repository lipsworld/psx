<?php
/*
 * psx
 * A object oriented and modular based PHP framework for developing
 * dynamic web applications. For the current version and informations
 * visit <http://phpsx.org>
 *
 * Copyright (c) 2010-2013 Christoph Kappestein <k42b3.x@gmail.com>
 *
 * This file is part of psx. psx is free software: you can
 * redistribute it and/or modify it under the terms of the
 * GNU General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or any later version.
 *
 * psx is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with psx. If not, see <http://www.gnu.org/licenses/>.
 */

namespace PSX\Domain;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * DomainManager
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl.html GPLv3
 * @link    http://phpsx.org
 */
class DomainManager implements DomainManagerInterface
{
	/**
	 * @var Symfony\Component\DependencyInjection\ContainerInterface
	 */
	protected $container;

	protected $_container;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function getDomain($className)
	{
		if(!isset($this->_container[$className]))
		{
			$this->_container[$className] = new $className();

			if($this->_container[$className] instanceof ContainerAwareInterface)
			{
				$this->_container[$className]->setContainer($this->container);
			}
		}

		return $this->_container[$className];
	}
}
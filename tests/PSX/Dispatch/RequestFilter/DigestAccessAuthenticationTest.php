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

namespace PSX\Dispatch\RequestFilter;

use Closure;
use PSX\Url;
use PSX\Http\Request;
use PSX\Http\Authentication;

/**
 * DigestAccessAuthenticationTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl.html GPLv3
 * @link    http://phpsx.org
 */
class DigestAccessAuthenticationTest extends \PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
		if(version_compare(PHP_VERSION, '5.4.0') < 0)
		{
			$this->markTestSkipped('PHP 5.4.0 required');
		}
	}

	/**
	 * @expectedException \PSX\Dispatch\RequestFilter\SuccessException
	 */
	public function testSuccessful()
	{
		$nonce  = md5(uniqid());
		$opaque = md5(uniqid());
		$cnonce = md5(uniqid());
		$nc     = '00000001';

		$handle = new DigestAccessAuthentication(function($username){
			return md5($username . ':psx:test');
		});

		$handle->onSuccess(function(){
			throw new SuccessException();
		});

		$handle->onFailure(function(){
			throw new FailureException();
		});

		$handle->onMissing(function(){
			throw new MissingException();
		});

		$handle->setNonce($nonce);
		$handle->setOpaque($opaque);

		$username = 'test';
		$password = 'test';

		$ha1      = md5($username . ':psx:' . $password);
		$ha2      = md5('GET:/index.php');
		$response = md5($ha1 . ':' . $nonce . ':' . $nc . ':' . $cnonce . ':auth:' . $ha2);

		$params = array(
			'username' => $username,
			'realm'    => 'psx',
			'nonce'    => $nonce,
			'qop'      => 'auth',
			'nc'       => $nc,
			'cnonce'   => $cnonce,
			'response' => $response,
			'opaque'   => $opaque,
		);

		$request = new Request(new Url('http://localhost/index.php'), 'GET', array('Authorization' => 'Digest ' . Authentication::encodeParameters($params)));

		$handle->handle($request);
	}

	/**
	 * @expectedException \PSX\Dispatch\RequestFilter\FailureException
	 */
	public function testFailure()
	{
		$nonce  = md5(uniqid());
		$opaque = md5(uniqid());
		$cnonce = md5(uniqid());
		$nc     = '00000001';

		$handle = new DigestAccessAuthentication(function($username){
			return md5($username . ':psx:test');
		});

		$handle->onSuccess(function(){
			throw new SuccessException();
		});

		$handle->onFailure(function(){
			throw new FailureException();
		});

		$handle->onMissing(function(){
			throw new MissingException();
		});

		$handle->setNonce($nonce);
		$handle->setOpaque($opaque);

		$username = 'foo';
		$password = 'bar';

		$ha1      = md5($username . ':psx:' . $password);
		$ha2      = md5('GET:/index.php');
		$response = md5($ha1 . ':' . $nonce . ':' . $nc . ':' . $cnonce . ':auth:' . $ha2);

		$params = array(
			'username' => $username,
			'realm'    => 'psx',
			'nonce'    => $nonce,
			'qop'      => 'auth',
			'nc'       => $nc,
			'cnonce'   => $cnonce,
			'response' => $response,
			'opaque'   => $opaque,
		);

		$request = new Request(new Url('http://localhost/index.php'), 'GET', array('Authorization' => 'Digest ' . Authentication::encodeParameters($params)));

		$handle->handle($request);
	}

	/**
	 * @expectedException \PSX\Dispatch\RequestFilter\MissingException
	 */
	public function testMissing()
	{
		$handle = new DigestAccessAuthentication(function($username){
			return md5($username . ':psx:test');
		});

		$handle->onSuccess(function(){
			throw new SuccessException();
		});

		$handle->onFailure(function(){
			throw new FailureException();
		});

		$handle->onMissing(function(){
			throw new MissingException();
		});

		$request = new Request(new Url('http://localhost'), 'GET');

		$handle->handle($request);
	}

	/**
	 * @expectedException \PSX\Dispatch\RequestFilter\MissingException
	 */
	public function testMissingWrongType()
	{
		$handle = new DigestAccessAuthentication(function($username){
			return md5($username . ':psx:test');
		});

		$handle->onSuccess(function(){
			throw new SuccessException();
		});

		$handle->onFailure(function(){
			throw new FailureException();
		});

		$handle->onMissing(function(){
			throw new MissingException();
		});

		$request = new Request(new Url('http://localhost'), 'GET', array('Authorization' => 'Foo'));

		$handle->handle($request);
	}
}

if(!class_exists('\PSX\Dispatch\RequestFilter\SuccessException'))
{
	class SuccessException extends \Exception
	{
	}
}

if(!class_exists('\PSX\Dispatch\RequestFilter\FailureException'))
{
	class FailureException extends \Exception
	{
	}
}

if(!class_exists('\PSX\Dispatch\RequestFilter\MissingException'))
{
	class MissingException extends \Exception
	{
	}
}
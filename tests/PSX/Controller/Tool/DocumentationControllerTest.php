<?php
/*
 * psx
 * A object oriented and modular based PHP framework for developing
 * dynamic web applications. For the current version and informations
 * visit <http://phpsx.org>
 *
 * Copyright (c) 2010-2014 Christoph Kappestein <k42b3.x@gmail.com>
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

namespace PSX\Controller\Tool;

use PSX\Http\Stream\TempStream;
use PSX\Http\Request;
use PSX\Http\Response;
use PSX\Json;
use PSX\Test\ControllerTestCase;
use PSX\Url;

/**
 * DocumentationControllerTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl.html GPLv3
 * @link    http://phpsx.org
 */
class DocumentationControllerTest extends ControllerTestCase
{
	public function testIndex()
	{
		$body     = new TempStream(fopen('php://memory', 'r+'));
		$request  = new Request(new Url('http://127.0.0.1/doc'), 'GET');
		$request->addHeader('Accept', 'application/json');
		$response = new Response();
		$response->setBody($body);

		$controller = $this->loadController($request, $response);
		$data       = Json::decode((string) $body);

		$this->assertArrayHasKey('routings', $data);

		$routing = current($data['routings']);

		$this->assertEquals(array('GET', 'POST', 'PUT', 'DELETE'), $routing['method']);
		$this->assertEquals('/api', $routing['path']);
		$this->assertEquals('PSX\Controller\Foo\Application\TestSchemaApiController', $routing['controller']);
	}

	public function testDetail()
	{
		$body     = new TempStream(fopen('php://memory', 'r+'));
		$request  = new Request(new Url('http://127.0.0.1/doc?path=' . urlencode('/api')), 'GET');
		$request->addHeader('Accept', 'application/json');
		$response = new Response();
		$response->setBody($body);

		$controller = $this->loadController($request, $response);
		$data       = Json::decode((string) $body);

		$this->assertArrayHasKey('method', $data);
		$this->assertEquals(array('GET', 'POST', 'PUT', 'DELETE'), $data['method']);
		$this->assertArrayHasKey('path', $data);
		$this->assertEquals('/api', $data['path']);
		$this->assertArrayHasKey('controller', $data);
		$this->assertEquals('PSX\Controller\Foo\Application\TestSchemaApiController', $data['controller']);
		$this->assertArrayHasKey('get_response', $data);
		$this->assertArrayHasKey('post_request', $data);
		$this->assertArrayHasKey('post_response', $data);
		$this->assertArrayHasKey('put_request', $data);
		$this->assertArrayHasKey('put_response', $data);
		$this->assertArrayHasKey('delete_request', $data);
		$this->assertArrayHasKey('delete_response', $data);
	}

	protected function getPaths()
	{
		return array(
			'/doc' => 'PSX\Controller\Tool\DocumentationController',
			'/api' => 'PSX\Controller\Foo\Application\TestSchemaApiController',
		);
	}
}
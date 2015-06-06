<?php
/*
 * PSX is a open source PHP framework to develop RESTful APIs.
 * For the current version and informations visit <http://phpsx.org>
 *
 * Copyright 2010-2015 Christoph Kappestein <k42b3.x@gmail.com>
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *     http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace PSX\Data\Schema\Property;

use PSX\Data\Schema\Property;

/**
 * DateTypeTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class DateTypeTest extends \PHPUnit_Framework_TestCase
{
	public function testValidate()
	{
		$property = Property::getDate('test');

		$this->assertTrue($property->validate('2000-01-01'));
		$this->assertTrue($property->validate('2000-01-01+13:00'));
	}

	/**
	 * @expectedException PSX\Data\Schema\ValidationException
	 */
	public function testValidateInvalidFormat()
	{
		$property = Property::getDate('test');

		$property->validate('foo');
	}

	/**
	 * @expectedException PSX\Data\Schema\ValidationException
	 */
	public function testValidateInvalidTimezone()
	{
		$property = Property::getDate('test');

		$property->validate('2000-01-01+25:00');
	}

	public function testValidateNull()
	{
		$property = Property::getDate('test');

		$this->assertTrue($property->validate(null));
	}

	public function testValidateDateTime()
	{
		$property = Property::getDate('test');

		$this->assertTrue($property->validate(new \DateTime()));
	}

	public function testAssimilate()
	{
		$property = Property::getDate('test');

		$this->assertInstanceOf('PSX\DateTime\Date', $property->assimilate('2000-01-01'));
		$this->assertInstanceOf('PSX\DateTime\Date', $property->assimilate('2000-01-01+13:00'));
		$this->assertInstanceOf('DateTime', $property->assimilate(new \DateTime()));
	}

	/**
	 * @expectedException RuntimeException
	 */
	public function testAssimilateInvalidFormat()
	{
		$property = Property::getDate('test');

		$property->assimilate('foo');
	}

	public function testGetId()
	{
		$property = Property::getDate('test');

		$this->assertEquals('f9dd14cdfa1dfdf83bae9aac01907504', $property->getId());
	}

	public function testGetTypeName()
	{
		$this->assertEquals('date', Property::getDate('test')->getTypeName());
	}
}

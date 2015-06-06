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
 * IntegerTypeTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class IntegerTypeTest extends \PHPUnit_Framework_TestCase
{
	public function testValidate()
	{
		$property = Property::getInteger('test');

		$this->assertTrue($property->validate(4));
		$this->assertTrue($property->validate('4'));
		$this->assertTrue($property->validate('+4'));
		$this->assertTrue($property->validate('-4'));
	}

	/**
	 * @expectedException PSX\Data\Schema\ValidationException
	 */
	public function testValidateInvalidFormat()
	{
		$property = Property::getInteger('test');

		$this->assertTrue($property->validate('foo'));
	}

	/**
	 * @expectedException PSX\Data\Schema\ValidationException
	 */
	public function testValidateInvalidFormatFraction()
	{
		$property = Property::getInteger('test');

		$this->assertTrue($property->validate('1.2'));
	}

	/**
	 * @expectedException PSX\Data\Schema\ValidationException
	 */
	public function testValidateInvalidFormatType()
	{
		$property = Property::getInteger('test');

		$this->assertTrue($property->validate(1.2));
	}

	/**
	 * @expectedException PSX\Data\Schema\ValidationException
	 */
	public function testValidateInvalidType()
	{
		$property = Property::getInteger('test');

		$this->assertTrue($property->validate(new \stdClass()));
	}

	/**
	 * @expectedException PSX\Data\Schema\ValidationException
	 */
	public function testValidateMin()
	{
		$property = Property::getInteger('test')->setMin(2);

		$this->assertTrue($property->validate(2));

		$property->validate(1);
	}

	/**
	 * @expectedException PSX\Data\Schema\ValidationException
	 */
	public function testValidateMax()
	{
		$property = Property::getInteger('test')->setMax(2);

		$this->assertTrue($property->validate(2));

		$property->validate(3);
	}

	public function testValidateNull()
	{
		$property = Property::getInteger('test');

		$this->assertTrue($property->validate(null));
	}

	public function testAssimilate()
	{
		$property = Property::getInteger('test');

		$this->assertInternalType('integer', $property->assimilate('4'));
		$this->assertEquals(4, $property->assimilate(4));
		$this->assertEquals(4, $property->assimilate('4'));
		$this->assertEquals(4, $property->assimilate('+4'));
		$this->assertEquals(-4, $property->assimilate('-4'));
	}

	public function testAssimilateInvalidFormat()
	{
		$property = Property::getInteger('test');

		$this->assertEquals(0, $property->assimilate('foo'));
	}

	public function testGetId()
	{
		$property = Property::getInteger('test');

		$this->assertEquals('6cf21bdd65cb914f8142978ecbb65568', $property->getId());
	}

	public function testGetTypeName()
	{
		$this->assertEquals('integer', Property::getInteger('test')->getTypeName());
	}
}

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
 * ComplexTypeTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class ComplexTypeTest extends \PHPUnit_Framework_TestCase
{
	public function testValidate()
	{
		$property = Property::getComplex('test')
			->add(Property::getString('foo'))
			->add(Property::getString('bar'));

		$this->assertTrue($property->validate(new \stdClass()));
	}

	/**
	 * @expectedException PSX\Data\Schema\ValidationException
	 */
	public function testValidateInvalidFormat()
	{
		$property = Property::getComplex('test')
			->add(Property::getString('foo'))
			->add(Property::getString('bar'));

		$this->assertTrue($property->validate('foo'));
	}

	/**
	 * @expectedException RuntimeException
	 */
	public function testValidateNoProperties()
	{
		$property = Property::getComplex('test');

		$property->validate(new \stdClass());
	}

	public function testValidateNull()
	{
		$property = Property::getComplex('test')
			->add(Property::getString('foo'))
			->add(Property::getString('bar'));

		$this->assertTrue($property->validate(null));
	}

	public function testAssimilate()
	{
		$property = Property::getComplex('test')
			->add(Property::getString('foo'))
			->add(Property::getString('bar'));

		$record = $property->assimilate(array('foo' => 'bar', 'baz' => 'foo'));

		$this->assertInstanceOf('PSX\Data\RecordInterface', $record);
		$this->assertEquals(array('foo' => 'bar'), $record->getRecordInfo()->getData());
	}

	/**
	 * @expectedException RuntimeException
	 */
	public function testAssimilateInvalidValue()
	{
		$property = Property::getComplex('test')
			->add(Property::getString('foo'))
			->add(Property::getString('bar'));

		$property->assimilate('foo');
	}

	/**
	 * @expectedException RuntimeException
	 */
	public function testAssimilateNoProperties()
	{
		$property = Property::getComplex('test');

		$property->assimilate('foo');
	}

	/**
	 * @expectedException RuntimeException
	 */
	public function testAssimilateRequiredMissing()
	{
		$property = Property::getComplex('test')
			->add(Property::getString('foo')->setRequired(true))
			->add(Property::getString('bar')->setRequired(true));

		$property->assimilate(array('foo' => 'bar', 'baz' => 'foo'));
	}

	public function testGetId()
	{
		$property = Property::getComplex('test')
			->add(Property::getString('foo'))
			->add(Property::getString('bar'));

		$this->assertEquals('0a7a6f5aee11c41efce8cbd1a3ed0b1d', $property->getId());
	}

	public function testProperties()
	{
		$property = Property::getComplex('test')
			->add(Property::getString('foo'))
			->add(Property::getString('bar'));

		$this->assertInstanceOf('PSX\Data\Schema\Property\StringType', $property->get('foo'));
		$this->assertTrue($property->has('foo'));

		$property->remove('foo');
		$property->remove('foo'); // should not produce an error

		$this->assertFalse($property->has('foo'));
	}

	public function testGetTypeName()
	{
		$this->assertEquals('complex', Property::getComplex('test')->getTypeName());
	}

	public function testMatch()
	{
		$property = Property::getComplex('test')
			->add(Property::getString('foo'))
			->add(Property::getString('bar'));

		$this->assertEquals(0, $property->match('foo'));
		$this->assertEquals(0, $property->match(array()));
		$this->assertEquals(0.5, $property->match(array('foo' => '')));
		$this->assertEquals(1, $property->match(array('foo' => '', 'bar' => '')));

		$property = Property::getComplex('test')
			->add(Property::getString('foo')->setRequired(true))
			->add(Property::getString('bar')->setRequired(true));

		$this->assertEquals(0, $property->match('foo'));
		$this->assertEquals(0, $property->match(array()));
		$this->assertEquals(0, $property->match(array('foo' => '')));
		$this->assertEquals(1, $property->match(array('foo' => '', 'bar' => '')));
	}
}

<?php
/*
 * psx
 * A object oriented and modular based PHP framework for developing
 * dynamic web applications. For the current version and informations
 * visit <http://phpsx.org>
 *
 * Copyright (c) 2010-2015 Christoph Kappestein <k42b3.x@gmail.com>
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

namespace PSX\Data\Schema\Generator;

/**
 * XsdTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl.html GPLv3
 * @link    http://phpsx.org
 */
class XsdTest extends GeneratorTestCase
{
	public function testGenerate()
	{
		$generator = new Xsd('http://ns.foo.com');
		$result    = $generator->generate($this->getSchema());

		$expect = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<xs:schema xmlns:tns="http://ns.foo.com" xmlns:xs="http://www.w3.org/2001/XMLSchema" elementFormDefault="qualified" targetNamespace="http://ns.foo.com">
	<xs:element name="news">
		<xs:complexType>
			<xs:annotation>
				<xs:documentation>An general news entry</xs:documentation>
			</xs:annotation>
			<xs:sequence>
				<xs:element name="tags" type="xs:string" maxOccurs="unbounded" minOccurs="1"/>
				<xs:element name="receiver" type="tns:type80f95cf5fd279866b5859c275abd7fa2" maxOccurs="unbounded" minOccurs="1"/>
				<xs:element name="read" type="xs:boolean" minOccurs="0" maxOccurs="1"/>
				<xs:element name="author" type="tns:type80f95cf5fd279866b5859c275abd7fa2" minOccurs="1" maxOccurs="1"/>
				<xs:element name="sendDate" type="xs:date" minOccurs="0" maxOccurs="1"/>
				<xs:element name="readDate" type="xs:dateTime" minOccurs="0" maxOccurs="1"/>
				<xs:element name="expires" type="xs:duration" minOccurs="0" maxOccurs="1"/>
				<xs:element name="price" type="tns:type1ca166360fdb85525e06be9b86ee18e9" minOccurs="1" maxOccurs="1"/>
				<xs:element name="rating" type="tns:type52e8d9b0939a88014e059cd49d9a376a" minOccurs="0" maxOccurs="1"/>
				<xs:element name="content" type="tns:type040034bdc6b65d156732a453749aa5b8" minOccurs="1" maxOccurs="1"/>
				<xs:element name="question" type="tns:typed73434c1994d3e6f3bc2fa7cc8178b89" minOccurs="0" maxOccurs="1"/>
				<xs:element name="coffeeTime" type="xs:time" minOccurs="0" maxOccurs="1"/>
			</xs:sequence>
		</xs:complexType>
	</xs:element>
	<xs:complexType name="type80f95cf5fd279866b5859c275abd7fa2">
		<xs:annotation>
			<xs:documentation>An simple author element with some description</xs:documentation>
		</xs:annotation>
		<xs:sequence>
			<xs:element maxOccurs="1" minOccurs="1" name="title" type="tns:typecf953ba6222cfc4017c889354fd489b4"/>
			<xs:element name="email" type="xs:string" minOccurs="0" maxOccurs="1"/>
			<xs:element maxOccurs="unbounded" minOccurs="0" name="categories" type="xs:string"/>
			<xs:element maxOccurs="unbounded" minOccurs="0" name="locations" type="tns:type93ef595df6d9e735702cba3611adba27"/>
			<xs:element maxOccurs="1" minOccurs="0" name="origin" type="tns:type93ef595df6d9e735702cba3611adba27"/>
		</xs:sequence>
	</xs:complexType>
	<xs:simpleType name="typecf953ba6222cfc4017c889354fd489b4">
		<xs:restriction base="xs:string">
			<xs:pattern value="[A-z]{3,16}"/>
		</xs:restriction>
	</xs:simpleType>
	<xs:complexType name="type93ef595df6d9e735702cba3611adba27">
		<xs:annotation>
			<xs:documentation>Location of the person</xs:documentation>
		</xs:annotation>
		<xs:sequence>
			<xs:element maxOccurs="1" minOccurs="0" name="lat" type="xs:integer"/>
			<xs:element maxOccurs="1" minOccurs="0" name="long" type="xs:integer"/>
		</xs:sequence>
	</xs:complexType>
	<xs:simpleType name="type1ca166360fdb85525e06be9b86ee18e9">
		<xs:restriction base="xs:float">
			<xs:maxInclusive value="100"/>
			<xs:minInclusive value="1"/>
		</xs:restriction>
	</xs:simpleType>
	<xs:simpleType name="type52e8d9b0939a88014e059cd49d9a376a">
		<xs:restriction base="xs:integer">
			<xs:maxInclusive value="5"/>
			<xs:minInclusive value="1"/>
		</xs:restriction>
	</xs:simpleType>
	<xs:simpleType name="type040034bdc6b65d156732a453749aa5b8">
		<xs:annotation>
			<xs:documentation>Contains the main content of the news entry</xs:documentation>
		</xs:annotation>
		<xs:restriction base="xs:string">
			<xs:minLength value="3"/>
			<xs:maxLength value="512"/>
		</xs:restriction>
	</xs:simpleType>
	<xs:simpleType name="typed73434c1994d3e6f3bc2fa7cc8178b89">
		<xs:restriction base="xs:string">
			<xs:enumeration value="foo"/>
			<xs:enumeration value="bar"/>
		</xs:restriction>
	</xs:simpleType>
</xs:schema>
XML;

		$this->assertXmlStringEqualsXmlString($expect, $result);
	}

	/**
	 * Check whether the generated xsd is valid and we can use it agains some 
	 * custom xml
	 */
	public function testXsd()
	{
		$generator = new Xsd('http://ns.foo.com');
		$result    = $generator->generate($this->getSchema());

		$xml = <<<XML
<news xmlns="http://ns.foo.com">
	<tags>foo</tags>
	<tags>bar</tags>
	<receiver>
		<title>bar</title>
	</receiver>
	<read>1</read>
	<author>
		<title>test</title>
		<categories>foo</categories>
		<categories>bar</categories>
		<locations>
			<lat>13</lat>
			<long>-37</long>
		</locations>
	</author>
	<sendDate>2014-07-22</sendDate>
	<readDate>2014-07-22T22:47:00</readDate>
	<expires>P1M</expires>
	<price>13.37</price>
	<rating>4</rating>
	<content>foobar</content>
	<coffeeTime>16:00:00</coffeeTime>
</news>
XML;

		$dom = new \DOMDocument();
		$dom->loadXML($xml);

		$this->assertTrue($dom->schemaValidateSource($result));
	}

	/**
	 * Test whether the generated XSD follows the schema XSD
	 */
	public function testXsdSchema()
	{
		$generator = new Xsd('http://ns.foo.com');
		$result    = $generator->generate($this->getSchema());

		$dom = new \DOMDocument();
		$dom->loadXML($result);

		$this->assertTrue($dom->schemaValidate(__DIR__ . '/../../../Api/View/Generator/Wsdl/schema.xsd'));
	}
}

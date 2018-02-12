<?php namespace Limoncello\Tests\Flute\Types;

/**
 * Copyright 2015-2017 info@neomerx.com
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

use DateTime;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Types\Type;
use Exception;
use Limoncello\Flute\Types\DateJsonApiStringType;
use Limoncello\Flute\Types\DateTimeDefaultNativeType;
use Limoncello\Flute\Types\DateTimeDefaultStringType;
use Limoncello\Flute\Types\DateTimeJsonApiNativeType;
use Limoncello\Flute\Types\DateTimeJsonApiStringType;
use Limoncello\Flute\Types\JsonApiDateTime;
use Limoncello\Flute\Types\JsonApiDateTimeType;
use Limoncello\Flute\Types\JsonApiDateType;
use Limoncello\Tests\Flute\TestCase;

/**
 * @package Limoncello\Tests\Flute
 */
class DateTimeTypesTest extends TestCase
{
    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        if (Type::hasType(DateTimeDefaultNativeType::NAME) === false) {
            Type::addType(DateTimeDefaultNativeType::NAME, DateTimeDefaultNativeType::class);
        }
        if (Type::hasType(DateTimeDefaultStringType::NAME) === false) {
            Type::addType(DateTimeDefaultStringType::NAME, DateTimeDefaultStringType::class);
        }
        if (Type::hasType(DateTimeJsonApiNativeType::NAME) === false) {
            Type::addType(DateTimeJsonApiNativeType::NAME, DateTimeJsonApiNativeType::class);
        }
        if (Type::hasType(DateTimeJsonApiStringType::NAME) === false) {
            Type::addType(DateTimeJsonApiStringType::NAME, DateTimeJsonApiStringType::class);
        }
        if (Type::hasType(DateJsonApiStringType::NAME) === false) {
            Type::addType(DateJsonApiStringType::NAME, DateJsonApiStringType::class);
        }
        if (Type::hasType(JsonApiDateTimeType::NAME) === false) {
            Type::addType(JsonApiDateTimeType::NAME, JsonApiDateTimeType::class);
        }
        if (Type::hasType(JsonApiDateType::NAME) === false) {
            Type::addType(JsonApiDateType::NAME, JsonApiDateType::class);
        }
    }

    /**
     * Test date conversions.
     *
     * @throws Exception
     * @throws DBALException
     */
    public function testDefaultNativeConversions(): void
    {
        $type     = Type::getType(DateTimeDefaultNativeType::NAME);
        $platform = $this->createConnection()->getDatabasePlatform();

        $this->assertNotEmpty($type->getSQLDeclaration([], $platform));

        $dbDate = '2001-02-03 04:05:06';

        $dateTime = DateTime::createFromFormat(DateTime::ISO8601, '2001-02-03T04:05:06+00:00');
        $dbValue  = $type->convertToDatabaseValue($dateTime, $platform);

        $this->assertEquals($dbDate, $dbValue);

        $phpValue = $type->convertToPHPValue($dbDate, $platform);
        $this->assertEquals($dateTime, $phpValue);

        $this->assertEquals($phpValue, $type->convertToPHPValue($phpValue, $platform));

        // extra coverage for getSQLDeclaration
        $this->assertEquals('DATETIME', $type->getSQLDeclaration([], $platform));

        // extra coverage for `null` inputs
        $this->assertNull($type->convertToPHPValue(null, $platform));
        $this->assertNull($type->convertToDatabaseValue(null, $platform));

        $this->assertNull($type->convertToDatabaseValue(null, $platform));
        $this->assertNull($type->convertToPHPValue(null, $platform));
    }

    /**
     * Test date conversions.
     *
     * @throws Exception
     * @throws DBALException
     */
    public function testDefaultStringConversions(): void
    {
        $type     = Type::getType(DateTimeDefaultStringType::NAME);
        $platform = $this->createConnection()->getDatabasePlatform();

        $this->assertNotEmpty($type->getSQLDeclaration([], $platform));

        $jsonDate = '2001-02-03T04:05:06+0000';
        $dbDate   = '2001-02-03 04:05:06';

        $dbValue = $type->convertToDatabaseValue($jsonDate, $platform);
        $this->assertEquals($dbDate, $dbValue);

        $phpValue = $type->convertToPHPValue($dbDate, $platform);
        $this->assertEquals($jsonDate, $phpValue);

        $this->assertNull($type->convertToDatabaseValue(null, $platform));
        $this->assertNull($type->convertToPHPValue(null, $platform));
    }

    /**
     * Test date conversions.
     *
     * @throws Exception
     * @throws DBALException
     */
    public function testJsonApiNativeConversions(): void
    {
        $type     = Type::getType(DateTimeJsonApiNativeType::NAME);
        $platform = $this->createConnection()->getDatabasePlatform();

        $this->assertNotEmpty($type->getSQLDeclaration([], $platform));

        $jsonDate = '2001-02-03T04:05:06+0000';
        $dbDate   = '2001-02-03 04:05:06';
        $dateTime = DateTime::createFromFormat(DateTime::ISO8601, '2001-02-03T04:05:06+00:00');

        $dbValue = $type->convertToDatabaseValue($dateTime, $platform);
        $this->assertEquals($dbDate, $dbValue);

        $phpValue = $type->convertToPHPValue($jsonDate, $platform);
        $this->assertEquals($dateTime, $phpValue);
        $this->assertEquals($phpValue, $type->convertToPHPValue($phpValue, $platform));

        $this->assertNull($type->convertToDatabaseValue(null, $platform));
        $this->assertNull($type->convertToPHPValue(null, $platform));
    }

    /**
     * Test date conversions.
     *
     * @throws Exception
     * @throws DBALException
     */
    public function testJsonApiStringConversions(): void
    {
        $type     = Type::getType(DateTimeJsonApiStringType::NAME);
        $platform = $this->createConnection()->getDatabasePlatform();

        $this->assertNotEmpty($type->getSQLDeclaration([], $platform));

        $jsonDate = '2001-02-03T04:05:06+0000';
        $dbDate   = '2001-02-03 04:05:06';

        $dbValue = $type->convertToDatabaseValue($jsonDate, $platform);
        $this->assertEquals($dbDate, $dbValue);

        $phpValue = $type->convertToPHPValue($jsonDate, $platform);
        $this->assertEquals($jsonDate, $phpValue);

        $this->assertNull($type->convertToDatabaseValue(null, $platform));
        $this->assertNull($type->convertToPHPValue(null, $platform));
    }

    /**
     * Test date conversions.
     *
     * @throws Exception
     * @throws DBALException
     *
     * @expectedException \Doctrine\DBAL\Types\ConversionException
     */
    public function testInvalidValueForDefaultNativeConversions(): void
    {
        $type     = Type::getType(DateTimeDefaultNativeType::NAME);
        $platform = $this->createConnection()->getDatabasePlatform();

        $this->assertNotEmpty($type->getSQLDeclaration([], $platform));

        $type->convertToPHPValue('2001-02-03 04:05:06.XXX', $platform);

        $this->assertNull($type->convertToDatabaseValue(null, $platform));
        $this->assertNull($type->convertToPHPValue(null, $platform));
    }

    /**
     * Test date conversions.
     *
     * @throws Exception
     * @throws DBALException
     */
    public function testJsonApiStringDateConversions(): void
    {
        $type     = Type::getType(DateJsonApiStringType::NAME);
        $platform = $this->createConnection()->getDatabasePlatform();

        $this->assertNotEmpty($type->getSQLDeclaration([], $platform));

        $jsonDate = '2001-02-03T04:05:06+0000';
        $dbDate   = '2001-02-03';

        $dbValue = $type->convertToDatabaseValue($jsonDate, $platform);
        $this->assertEquals($dbDate, $dbValue);

        $dbValue = $type->convertToDatabaseValue(null, $platform);
        $this->assertNull($dbValue);

        $phpValue = $type->convertToPHPValue($jsonDate, $platform);
        $this->assertEquals($jsonDate, $phpValue);

        $this->assertNull($type->convertToDatabaseValue(null, $platform));
        $this->assertNull($type->convertToPHPValue(null, $platform));
    }

    /**
     * Test date conversions.
     *
     * @throws Exception
     * @throws DBALException
     */
    public function testJsonApiDateTimeTypeConversions(): void
    {
        $type     = Type::getType(JsonApiDateTimeType::NAME);
        $platform = $this->createConnection()->getDatabasePlatform();

        $jsonDate = '2001-02-03T04:05:06+0000';

        /** @var JsonApiDateTime $phpValue */
        $phpValue = $type->convertToPHPValue($jsonDate, $platform);
        $this->assertEquals(981173106, $phpValue->getValue()->getTimestamp());
        $this->assertEquals($jsonDate, $phpValue->jsonSerialize());
    }

    /**
     * Test date conversions.
     *
     * @throws Exception
     * @throws DBALException
     */
    public function testJsonApiDateTimeTypeToDatabaseConversions1(): void
    {
        $type     = Type::getType(JsonApiDateTimeType::NAME);
        $platform = $this->createConnection()->getDatabasePlatform();

        $jsonDate = '2001-02-03T04:05:06+0000';

        /** @var string $phpValue */
        $phpValue = $type->convertToDatabaseValue($jsonDate, $platform);
        $this->assertEquals('2001-02-03 04:05:06', $phpValue);
    }

    /**
     * Test date conversions.
     *
     * @throws Exception
     * @throws DBALException
     */
    public function testJsonApiDateTimeTypeToDatabaseConversions2(): void
    {
        $type     = Type::getType(JsonApiDateTimeType::NAME);
        $platform = $this->createConnection()->getDatabasePlatform();

        $jsonDate = new DateTime('2001-02-03 04:05:06');

        /** @var string $phpValue */
        $phpValue = $type->convertToDatabaseValue($jsonDate, $platform);
        $this->assertEquals('2001-02-03 04:05:06', $phpValue);
    }

    /**
     * Test date conversions.
     *
     * @throws Exception
     * @throws DBALException
     */
    public function testJsonApiDateTimeTypeToDatabaseConversions3(): void
    {
        /** @var JsonApiDateTimeType $type */
        $type     = Type::getType(JsonApiDateTimeType::NAME);
        $platform = $this->createConnection()->getDatabasePlatform();

        $jsonDate = new JsonApiDateTime(new DateTime('2001-02-03 04:05:06'));

        /** @var string $phpValue */
        $phpValue = $type->convertToDatabaseValue($jsonDate, $platform);
        $this->assertEquals('2001-02-03 04:05:06', $phpValue);
    }

    /**
     * Test date conversions.
     *
     * @throws Exception
     * @throws DBALException
     *
     * @expectedException \Doctrine\DBAL\Types\ConversionException
     */
    public function testJsonApiDateTimeTypeToDatabaseConversionsInvalidInput1(): void
    {
        $type     = Type::getType(JsonApiDateTimeType::NAME);
        $platform = $this->createConnection()->getDatabasePlatform();

        $jsonDate = 'XXX';

        $type->convertToDatabaseValue($jsonDate, $platform);
    }

    /**
     * Test date conversions.
     *
     * @throws Exception
     * @throws DBALException
     *
     * @expectedException \Doctrine\DBAL\Types\ConversionException
     */
    public function testJsonApiDateTimeTypeToDatabaseConversionsInvalidInput2(): void
    {
        $type     = Type::getType(JsonApiDateTimeType::NAME);
        $platform = $this->createConnection()->getDatabasePlatform();

        $jsonDate = new \stdClass();

        $type->convertToDatabaseValue($jsonDate, $platform);
    }

    /**
     * Test date conversions.
     *
     * @throws Exception
     * @throws DBALException
     */
    public function testJsonApiDateTypeConversions(): void
    {
        $type     = Type::getType(JsonApiDateType::NAME);
        $platform = $this->createConnection()->getDatabasePlatform();

        $jsonDate = '2001-02-03';

        /** @var JsonApiDateTime $phpValue */
        $phpValue = $type->convertToPHPValue($jsonDate, $platform);
        $this->assertEquals(981158400, $phpValue->getValue()->getTimestamp());
        $this->assertEquals('2001-02-03T00:00:00+0000', $phpValue->jsonSerialize());
    }

    /**
     * Test date conversions.
     *
     * @throws Exception
     * @throws DBALException
     */
    public function testJsonApiDateTypeToDatabaseConversions1(): void
    {
        /** @var JsonApiDateType $type */
        $type     = Type::getType(JsonApiDateType::NAME);
        $platform = $this->createConnection()->getDatabasePlatform();

        $jsonDate = '2001-02-03T04:05:06+0000';

        /** @var string $phpValue */
        $phpValue = $type->convertToDatabaseValue($jsonDate, $platform);
        $this->assertEquals('2001-02-03', $phpValue);
    }

    /**
     * Test date conversions.
     *
     * @throws Exception
     * @throws DBALException
     */
    public function testJsonApiDateTypeToDatabaseConversions2(): void
    {
        /** @var JsonApiDateType $type */
        $type     = Type::getType(JsonApiDateType::NAME);
        $platform = $this->createConnection()->getDatabasePlatform();

        $jsonDate = new DateTime('2001-02-03 04:05:06');

        /** @var string $phpValue */
        $phpValue = $type->convertToDatabaseValue($jsonDate, $platform);
        $this->assertEquals('2001-02-03', $phpValue);
    }

    /**
     * Test date conversions.
     *
     * @throws Exception
     * @throws DBALException
     */
    public function testJsonApiDateTypeToDatabaseConversions3(): void
    {
        /** @var JsonApiDateType $type */
        $type     = Type::getType(JsonApiDateType::NAME);
        $platform = $this->createConnection()->getDatabasePlatform();

        $jsonDate = new JsonApiDateTime(new DateTime('2001-02-03'));

        /** @var string $phpValue */
        $phpValue = $type->convertToDatabaseValue($jsonDate, $platform);
        $this->assertEquals('2001-02-03', $phpValue);
    }

    /**
     * Test date conversions.
     *
     * @throws Exception
     * @throws DBALException
     *
     * @expectedException \Doctrine\DBAL\Types\ConversionException
     */
    public function testJsonApiDateTypeToDatabaseConversionsInvalidInput1(): void
    {
        $type     = Type::getType(JsonApiDateType::NAME);
        $platform = $this->createConnection()->getDatabasePlatform();

        $jsonDate = 'XXX';

        $type->convertToDatabaseValue($jsonDate, $platform);
    }

    /**
     * Test date conversions.
     *
     * @throws Exception
     * @throws DBALException
     *
     * @expectedException \Doctrine\DBAL\Types\ConversionException
     */
    public function testJsonApiDateTypeToDatabaseConversionsInvalidInput2(): void
    {
        $type     = Type::getType(JsonApiDateType::NAME);
        $platform = $this->createConnection()->getDatabasePlatform();

        $jsonDate = new \stdClass();

        $type->convertToDatabaseValue($jsonDate, $platform);
    }

    /**
     * Test date conversions.
     *
     * @throws Exception
     * @throws DBALException
     *
     * @expectedException \Doctrine\DBAL\Types\ConversionException
     */
    public function testInvalidValueForJsonApiStringDateConversions(): void
    {
        $type     = Type::getType(DateJsonApiStringType::NAME);
        $platform = $this->createConnection()->getDatabasePlatform();

        $this->assertNotEmpty($type->getSQLDeclaration([], $platform));

        $type->convertToDatabaseValue('2001-02-03 04:05:06.XXX', $platform);

        $this->assertNull($type->convertToDatabaseValue(null, $platform));
        $this->assertNull($type->convertToPHPValue(null, $platform));
    }

    /**
     * Test date conversions for invalid value.
     *
     * @throws Exception
     * @throws DBALException
     *
     * @expectedException \Doctrine\DBAL\Types\ConversionException
     */
    public function testDefaultStringConversionsInvalidValue(): void
    {
        Type::getType(DateTimeDefaultStringType::NAME)->convertToDatabaseValue(
            '2001-02-03 04:05:06', // invalid format
            $this->createConnection()->getDatabasePlatform()
        );
    }

    /**
     * Test date conversions for invalid value.
     *
     * @throws Exception
     * @throws DBALException
     *
     * @expectedException \Doctrine\DBAL\Types\ConversionException
     */
    public function testJsonApiNativeConversionsInvalidValue(): void
    {
        Type::getType(DateTimeJsonApiNativeType::NAME)->convertToPHPValue(
            '2001-02-03 04:05:06', // invalid format
            $this->createConnection()->getDatabasePlatform()
        );
    }

    /**
     * Test date conversions for invalid value.
     *
     * @throws Exception
     * @throws DBALException
     *
     * @expectedException \Doctrine\DBAL\Types\ConversionException
     */
    public function testJsonApiStringConversionsInvalidValue(): void
    {
        Type::getType(DateTimeJsonApiStringType::NAME)->convertToDatabaseValue(
            '2001-02-03 04:05:06', // invalid format
            $this->createConnection()->getDatabasePlatform()
        );
    }
}

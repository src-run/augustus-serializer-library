<?php

/*
 * This file is part of the `src-run/arthur-doctrine-serializer-library` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 * (c) Scribe Inc      <scr@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\Serializer\Tests;

use SR\Serializer\SerializerFactory;

/**
 * Class SerializerFactoryTest.
 */
class SerializerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateTypes()
    {
        static::assertInstanceOf(
            'SR\Serializer\SerializerIgbinary',
            SerializerFactory::create(SerializerFactory::SERIALIZER_IGBINARY)
        );

        static::assertInstanceOf(
            'SR\Serializer\SerializerJson',
            SerializerFactory::create(SerializerFactory::SERIALIZER_JSON)
        );

        static::assertInstanceOf(
            'SR\Serializer\SerializerNative',
            SerializerFactory::create(SerializerFactory::SERIALIZER_NATIVE)
        );
    }

    public function testAutoTypeWithIgbinary()
    {
        if (extension_loaded('igbinary')) {
            static::assertInstanceOf(
                'SR\Serializer\SerializerIgbinary',
                SerializerFactory::create(SerializerFactory::SERIALIZER_AUTO)
            );
        }
    }

    public function testAutoTypeWithoutIgbinary()
    {
        if (!extension_loaded('igbinary')) {
            static::assertInstanceOf(
                'SR\Serializer\SerializerNative',
                SerializerFactory::create(SerializerFactory::SERIALIZER_NATIVE)
            );
        }
    }
}

/* EOF */

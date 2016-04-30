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
 * Class SerializerIgbinaryTest.
 */
class SerializerIgbinaryTest extends \PHPUnit_Framework_TestCase
{
    public function testIgbinaryType()
    {
        static::assertInstanceOf(
            'SR\Serializer\SerializerIgbinary',
            $s = SerializerFactory::create(SerializerFactory::SERIALIZER_IGBINARY)
        );

        $expectedUnserialized = [1, 'something', ['2, 3', 4]];
        $expectedSerialized = igbinary_serialize($expectedUnserialized);

        $serialized = $s->serializeData($expectedUnserialized);
        $unserialized = $s->unSerializeData($serialized);

        static::assertEquals($expectedSerialized, $serialized);
        static::assertEquals($expectedUnserialized, $unserialized);
    }

    public function testVisitor()
    {
        $s = SerializerFactory::create(SerializerFactory::SERIALIZER_IGBINARY);

        $expectedUnserialized = ['foo'];
        $expectedSerialized = igbinary_serialize($expectedUnserialized);

        $serialized = $s->serializeData($expectedUnserialized, function ($data) {
            return $data;
        });

        $unserialized = $s->unSerializeData($serialized, function ($data) {
            return $data;
        });

        static::assertEquals($expectedSerialized, $serialized);
        static::assertEquals($expectedUnserialized, $unserialized);
    }
}

/* EOF */

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

use SR\Serializer\Serializer;

/**
 * Class SerializerFactoryTest.
 */
class SerializerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateTypes()
    {
        $this->assertInstanceOf(
            'SR\Serializer\Type\SerializerTypeIgbinary',
            Serializer::create(Serializer::TYPE_IGBINARY)->getSerializer()
        );

        $this->assertInstanceOf(
            'SR\Serializer\Type\SerializerTypeJson',
            Serializer::create(Serializer::TYPE_JSON)->getSerializer()
        );

        $this->assertInstanceOf(
            'SR\Serializer\Type\SerializerTypePhp',
            Serializer::create(Serializer::TYPE_PHP)->getSerializer()
        );
    }

    public function testAutoTypeWithIgbinary()
    {
        if (extension_loaded('igbinary')) {
            $this->assertInstanceOf(
                'SR\Serializer\Type\SerializerTypeIgbinary',
                Serializer::create(Serializer::TYPE_AUTO)->getSerializer()
            );
        }
    }

    public function testAutoTypeWithoutIgbinary()
    {
        if (!extension_loaded('igbinary')) {
            $this->assertInstanceOf(
                'SR\Serializer\Type\SerializerTypePhp',
                Serializer::create(Serializer::TYPE_PHP)->getSerializer()
            );
        }
    }

    public function testBasicSerializeUnserializeJson()
    {
        $serializer = Serializer::create(Serializer::TYPE_JSON);
        $dataOriginal = ['a', 'b', 'c'];
        $dataSerialized = $serializer->serialize($dataOriginal);

        $this->assertSame(json_encode($dataOriginal), $dataSerialized);
        $this->assertSame($dataOriginal, $serializer->unserialize($dataSerialized));

        $this->assertInstanceOf(
            'SR\Serializer\Type\SerializerTypeJson',
            $serializer->getSerializer()
        );
    }

    public function testBasicSerializeUnserializePhp()
    {
        $serializer = Serializer::create(Serializer::TYPE_PHP);
        $dataOriginal = ['a', 'b', 'c'];
        $dataSerialized = $serializer->serialize($dataOriginal);

        $this->assertSame(serialize($dataOriginal), $dataSerialized);
        $this->assertSame($dataOriginal, $serializer->unserialize($dataSerialized));

        $this->assertInstanceOf(
            'SR\Serializer\Type\SerializerTypePhp',
            $serializer->getSerializer()
        );
    }

    public function testBasicSerializeUnserializeIgbinary()
    {
        $serializer = Serializer::create(Serializer::TYPE_IGBINARY);
        $dataOriginal = ['a', 'b', 'c'];
        $dataSerialized = $serializer->serialize($dataOriginal);

        $this->assertSame(igbinary_serialize($dataOriginal), $dataSerialized);
        $this->assertSame($dataOriginal, $serializer->unserialize($dataSerialized));

        $this->assertInstanceOf(
            'SR\Serializer\Type\SerializerTypeIgbinary',
            $serializer->getSerializer()
        );
    }

    public function testBasicSerializeUnserializeWithNormalizerAndDenormalizer()
    {
        $normalizer = function ($data) {
            return array_map(function ($d) {
                return $d * 4;
            }, $data);
        };

        $denormalizer = function ($data) {
            return array_map(function ($d) {
                return $d * 4;
            }, $data);
        };

        $serializer = Serializer::create(Serializer::TYPE_JSON);

        $dataOriginal = [1, 2, 3];

        $dataSerializedNoVisitor = $serializer->serialize($dataOriginal);

        $dataSerialized = $serializer
            ->setNormalizer($normalizer)
            ->serialize($dataOriginal);

        $this->assertNotSame($dataSerializedNoVisitor, $dataSerialized);
        $this->assertSame(json_encode($normalizer($dataOriginal)), $dataSerialized);

        $dataUnserialized = $serializer
            ->setDenormalizer($denormalizer)
            ->unserialize($dataSerialized);

        $this->assertSame($denormalizer(json_decode(json_encode($normalizer($dataOriginal)))), $dataUnserialized);
    }
}

/* EOF */

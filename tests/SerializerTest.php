<?php

/*
 * This file is part of the `src-run/augustus-serializer-library` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\Serializer\Tests;

use PHPUnit\Framework\TestCase;
use SR\Serializer\Handler\IgbinaryHandler;
use SR\Serializer\Handler\JsonHandler;
use SR\Serializer\Handler\PhpHandler;
use SR\Serializer\Serializer;
use SR\Serializer\SerializerInterface;
use SR\Serializer\Tests\Fixture\FooClass;
use SR\Serializer\Tests\Fixture\FooHandler;
use SR\Serializer\Visitor\VisitorInterface;
use SR\Utilities\EngineQuery;

/**
 * @covers \SR\Serializer\Serializer
 * @covers \SR\Serializer\Handler\ClosureHandler
 * @covers \SR\Serializer\Handler\IgbinaryHandler
 * @covers \SR\Serializer\Handler\JsonHandler
 * @covers \SR\Serializer\Handler\PhpHandler
 */
class SerializerTest extends TestCase
{
    public function testConstruction()
    {
        $this->assertInstanceOf(SerializerInterface::class, new Serializer());
        $this->assertInstanceOf(SerializerInterface::class, Serializer::create());
    }

    /**
     * @return \Iterator
     */
    public static function provideHandlerData(): \Iterator
    {
        yield [IgbinaryHandler::class];
        yield [PhpHandler::class];
        yield [JsonHandler::class];
    }

    /**
     * @dataProvider provideHandlerData
     *
     * @param string $handler
     */
    public function testSpecifiedHandler(string $handler)
    {
        $this->assertInstanceOf($handler, (new Serializer($handler))->getHandler());
    }

    public function testDefaultHandlerWithIgbinaryExtension()
    {
        $this->assertInstanceOf(
            EngineQuery::extensionLoaded('igbinary') ? IgbinaryHandler::class : PhpHandler::class,
            (new Serializer())->getHandler()
        );
    }

    /**
     * @return \Iterator
     */
    public static function provideDefaultSerializationData(): \Iterator
    {
        foreach (self::provideSerializationData() as [$data]) {
            yield [$data, (function ($data): string {
                return EngineQuery::extensionLoaded('igbinary') ? igbinary_serialize($data) : serialize($data);
            })($data)];
        }
    }

    /**
     * @dataProvider provideDefaultSerializationData
     *
     * @param mixed  $provided
     * @param string $expected
     */
    public function testSerialization($provided, string $expected): void
    {
        $this->assertSame($expected, $serialized = ($serializer = new Serializer())->serialize($provided));
        $this->assertSame(
            self::getUnSerializationObjectVisitor()->visit($provided),
            self::getUnSerializationObjectVisitor()->visit($serializer->unSerialize($serialized))
        );
    }

    /**
     * @return \Iterator
     */
    public static function provideSpecifiedHandlerSerializationData(): \Iterator
    {
        foreach (self::provideHandlerData() as [$handler]) {
            $dataSet = array_filter(iterator_to_array(self::provideSerializationData()), function ($data) use ($handler) {
                return JsonHandler::class === $handler && !is_object($data[0]);
            });
            foreach ($dataSet as [$data]) {
                yield [$handler, $data, (function ($data, string $handler) {
                    switch ($handler) {
                        case JsonHandler::class:
                            return json_encode($data);

                        case PhpHandler::class:
                            return serialize($data);

                        case IgbinaryHandler::class:
                        default:
                            return igbinary_serialize($data);
                    }
                })($data, $handler)];
            }
        }
    }

    /**
     * @dataProvider provideSpecifiedHandlerSerializationData
     *
     * @param string $handler
     * @param mixed  $provided
     * @param string $expected
     */
    public function testSpecifiedHandlerSerialization(string $handler, $provided, string $expected): void
    {
        $serializer = $serializer = new Serializer($handler);

        $this->assertInstanceOf($handler, $serializer->getHandler());
        $this->assertSame($expected, $serialized = $serializer->serialize($provided));
        $this->assertSame(
            self::getUnSerializationObjectVisitor()->visit($provided),
            self::getUnSerializationObjectVisitor()->visit($serializer->unSerialize($serialized))
        );
    }

    /**
     * @dataProvider provideDefaultSerializationData
     *
     * @param mixed  $provided
     * @param string $expected
     */
    public function testSerializationWithRuntimeVisitors($provided): void
    {
        $serializerNoVisitors = new Serializer();
        $serializer = new Serializer();

        $this->assertCount(0, $serializer->getSerializeVisitors());
        $this->assertCount(0, $serializer->getUnSerializeVisitors());

        $serializer->registerSerializeVisitors(self::getDoSerializationObjectVisitor());
        $serializer->registerUnSerializeVisitors(self::getUnSerializationObjectVisitor());

        $this->assertCount(1, $serializer->getSerializeVisitors());
        $this->assertCount(1, $serializer->getUnSerializeVisitors());

        $this->assertSame(
            $serializedNoVisitors = $serializerNoVisitors->serialize(self::getDoSerializationObjectVisitor()->visit($provided)),
            $serialized = $serializer->serialize($provided)
        );

        $this->assertSame(
            self::getUnSerializationObjectVisitor()->visit($serializerNoVisitors->unSerialize($serializedNoVisitors)),
            $serializer->unSerialize($serialized)
        );

        $serializer->removeSerializeVisitors(self::getDoSerializationObjectVisitor());
        $serializer->removeUnSerializeVisitors(self::getUnSerializationObjectVisitor());

        $this->assertCount(0, $serializer->getSerializeVisitors());
        $this->assertCount(0, $serializer->getUnSerializeVisitors());
    }

    public static function provideInvalidHandlerData(): \Iterator
    {
        yield [__CLASS__];
        yield [sprintf('%s/ClassDoesNotExist', __NAMESPACE__)];
        yield [FooHandler::class];
    }

    /**
     * @dataProvider provideInvalidHandlerData
     *
     * @param string $handler
     */
    public function testInvalidConstruction(string $handler)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageRegExp('{Failed to setup handlers: (".+"){1,}}');

        new Serializer($handler);
    }

    /**
     * @return \Iterator
     */
    private static function provideSerializationData(): \Iterator
    {
        $dataSet = [
            100,
            100.33,
            'a-string',
            false,
            true,
            ['a', 'simple', 'array'],
            new \stdClass(),
            new FooClass(),
        ];

        foreach ($dataSet as $data) {
            yield [$data];
        }
    }

    /**
     * @return VisitorInterface
     */
    private static function getDoSerializationObjectVisitor(): VisitorInterface
    {
        static $visitor;

        if (null === $visitor) {
            $visitor = new class() implements VisitorInterface {
                /**
                 * @param mixed $data
                 *
                 * @return mixed
                 */
                public function visit($data)
                {
                    if (is_object($data)) {
                        return $data;
                    }

                    if (is_array($data)) {
                        return array_map(function ($value) {
                            return $this->visit($value);
                        }, $data);
                    }

                    if (is_string($data)) {
                        return sprintf('visited-%s', $data);
                    }

                    if (is_int($data)) {
                        return $data + 10;
                    }

                    return $data;
                }
            };
        }

        return $visitor;
    }

    /**
     * @return VisitorInterface
     */
    private static function getUnSerializationObjectVisitor(): VisitorInterface
    {
        static $visitor;

        if (null === $visitor) {
            $visitor = new class() implements VisitorInterface {
                /**
                 * @param mixed $data
                 *
                 * @return mixed
                 */
                public function visit($data)
                {
                    if (is_array($data)) {
                        return array_map(function ($value) {
                            return $this->visit($value);
                        }, $data);
                    }

                    return is_object($data) ? get_class($data) : $data;
                }
            };
        }

        return $visitor;
    }
}

/* EOF */

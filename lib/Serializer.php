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

namespace SR\Serializer;

use SR\Serializer\Type\SerializerTypePhp;

/**
 * Class Serializer.
 */
class Serializer implements SerializerInterface
{
    /**
     * @var SerializerTypeInterface
     */
    private static $serializer;

    /**
     * @var null|\Closure
     */
    private static $normalizer;

    /**
     * @var null|\Closure
     */
    private static $denormalizer;

    /**
     * @param string $type
     *
     * @return SerializerInterface
     */
    final public static function create($type = self::TYPE_AUTO)
    {
        if ($type === static::TYPE_AUTO) {
            static::createAuto();
        } else {
            static::createType($type);
        }

        return new static();
    }

    /**
     * @param mixed $data
     *
     * @return mixed
     */
    final public function serialize($data)
    {
        return static::$serializer->serialize($data, static::$normalizer);
    }

    /**
     * @param mixed $data
     *
     * @return mixed
     */
    final public function unserialize($data)
    {
        return static::$serializer->unserialize($data, static::$denormalizer);
    }

    /**
     * @param null|\Closure $denormalizer
     */
    final public function setDenormalizer(\Closure $denormalizer = null)
    {
        static::$denormalizer = $denormalizer;

        return $this;
    }

    /**
     * @param null|\Closure $normalizer
     */
    final public function setNormalizer(\Closure $normalizer = null)
    {
        static::$normalizer = $normalizer;

        return $this;
    }

    /**
     * @return SerializerTypeInterface
     */
    final public function getSerializer()
    {
        return static::$serializer;
    }

    /**
     * @return SerializerInterface
     */
    final private static function createAuto()
    {
        foreach (self::PRIORITY as $type) {
            if ($type::supported()) {
                static::createType($type);

                break;
            }
        }
    }

    /**
     * @param string $type
     *
     * @return SerializerInterface
     */
    final private static function createType($type)
    {
        static::$serializer = class_exists($type) ? $type::create() : SerializerTypePhp::create();
    }
}

/* EOF */

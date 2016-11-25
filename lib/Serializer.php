<?php

/*
 * This file is part of the `src-run/augustus-serializer-library` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\Serializer;

use SR\Serializer\Type\SerializerTypeInterface;

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
     * @param string|int $type
     *
     * @return SerializerInterface
     */
    final public static function create(string $type = null) : SerializerInterface
    {
        $serializers = static::TYPE_PRIORITY;

        if ($type !== null) {
            $serializers = [$type];
        }

        foreach ($serializers as $type) {
            if (static::createSerializer($type)) {
                break;
            }
        }

        return new static();
    }

    /**
     * @param mixed $data
     *
     * @return mixed
     */
    final public function serialize($data) : string
    {
        return $this->getSerializer()->serialize($data, static::$normalizer);
    }

    /**
     * @param mixed $data
     *
     * @return mixed
     */
    final public function unserialize($data)
    {
        return $this->getSerializer()->unserialize($data, static::$denormalizer);
    }

    /**
     * @param null|\Closure $denormalizer
     *
     * @return $this
     */
    final public function setDenormalizer(\Closure $denormalizer = null) : SerializerInterface
    {
        static::$denormalizer = $denormalizer;

        return $this;
    }

    /**
     * @param null|\Closure $normalizer
     *
     * @return $this
     */
    final public function setNormalizer(\Closure $normalizer = null) : SerializerInterface
    {
        static::$normalizer = $normalizer;

        return $this;
    }

    /**
     * @return SerializerTypeInterface
     */
    final public function getSerializer() : SerializerTypeInterface
    {
        return static::$serializer;
    }

    /**
     * @return bool
     */
    final public function hasSerializer() : bool
    {
        return static::$serializer instanceof SerializerTypeInterface;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    final private static function createSerializer(string $type) : bool
    {
        if (call_user_func([$type, 'supported'])) {
            static::initializeSerializer($type);

            return true;
        }

        return false;
    }

    /**
     * @param string $type
     *
     * @return SerializerTypeInterface
     */
    final private static function initializeSerializer(string $type) : SerializerTypeInterface
    {
        return static::$serializer = $serializer = call_user_func([$type, 'create']);
    }
}

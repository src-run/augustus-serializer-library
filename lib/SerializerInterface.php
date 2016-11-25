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

use SR\Serializer\Type\SerializerTypeIgbinary;
use SR\Serializer\Type\SerializerTypeInterface;
use SR\Serializer\Type\SerializerTypeJson;
use SR\Serializer\Type\SerializerTypePhp;

interface SerializerInterface
{
    /**
     * @var string
     */
    const TYPE_PHP = SerializerTypePhp::class;

    /**
     * @var string
     */
    const TYPE_IGBINARY = SerializerTypeIgbinary::class;

    /**
     * @var string
     */
    const TYPE_JSON = SerializerTypeJson::class;

    /**
     * @var string[]
     */
    const TYPE_PRIORITY = [
        self::TYPE_IGBINARY,
        self::TYPE_PHP,
        self::TYPE_JSON,
    ];

    /**
     * @param string|int $type
     *
     * @return SerializerInterface
     */
    public static function create(string $type = null) : SerializerInterface;

    /**
     * @param mixed $data
     *
     * @return mixed
     */
    public function serialize($data) : string;

    /**
     * @param mixed $data
     *
     * @return mixed
     */
    public function unserialize($data);

    /**
     * @param null|\Closure $denormalizer
     */
    public function setDenormalizer(\Closure $denormalizer = null) : SerializerInterface;

    /**
     * @param null|\Closure $normalizer
     */
    public function setNormalizer(\Closure $normalizer = null) : SerializerInterface;

    /**
     * @return SerializerTypeInterface
     */
    public function getSerializer() : SerializerTypeInterface;

    /**
     * @return bool
     */
    public function hasSerializer() : bool;
}

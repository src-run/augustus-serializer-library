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

/**
 * Interface SerializerInterface.
 */
interface SerializerInterface
{
    /**
     * @var int
     */
    const TYPE_AUTO = -1;

    /**
     * @var string
     */
    const TYPE_PHP = 'SR\Serializer\Type\SerializerTypePhp';

    /**
     * @var string
     */
    const TYPE_IGBINARY = 'SR\Serializer\Type\SerializerTypeIgbinary';

    /**
     * @var string
     */
    const TYPE_JSON = 'SR\Serializer\Type\SerializerTypeJson';

    /**
     * @var string
     */
    const TYPE_CALLABLE = 'SR\Serializer\Type\SerializerTypeCallable';

    /**
     * @var string[]
     */
    const PRIORITY = [
        self::TYPE_IGBINARY,
        self::TYPE_PHP,
        self::TYPE_JSON,
    ];

    /**
     * @param string $type
     *
     * @return SerializerInterface
     */
    public static function create($type = self::TYPE_AUTO);

    /**
     * @param mixed $data
     *
     * @return mixed
     */
    public function serialize($data);

    /**
     * @param mixed $data
     *
     * @return mixed
     */
    public function unserialize($data);

    /**
     * @param null|\Closure $denormalizer
     */
    public function setDenormalizer(\Closure $denormalizer = null);

    /**
     * @param null|\Closure $normalizer
     */
    public function setNormalizer(\Closure $normalizer = null);

    /**
     * @return SerializerTypeInterface
     */
    public function getSerializer();
}

/* EOF */

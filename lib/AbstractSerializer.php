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
 * Class AbstractSerializer.
 */
class AbstractSerializer implements SerializerInterface
{
    /**
     * @var callable|\Closure
     */
    protected $serializationHandler;

    /**
     * @var callable|\Closure
     */
    protected $unSerializationHandler;

    /**
     * @return SerializerInterface
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @param mixed|null    $data
     * @param \Closure|null $visitor
     *
     * @return mixed
     */
    public function serializeData($data = null, \Closure $visitor = null)
    {
        if ($visitor instanceof \Closure) {
            $data = $visitor($data);
        }

        return call_user_func($this->serializationHandler, $data);
    }

    /**
     * @param mixed|null    $data
     * @param \Closure|null $visitor
     *
     * @return mixed
     */
    public function unSerializeData($data = null, \Closure $visitor = null)
    {
        $data = call_user_func($this->unSerializationHandler, $data);

        return $visitor instanceof \Closure ? $visitor($data) : $data;
    }
}

/* EOF */

<?php

/*
 * This file is part of the `src-run/augustus-serializer-library` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\Serializer\Type;

abstract class AbstractSerializerType implements SerializerTypeInterface
{
    /**
     * @var callable|\Closure
     */
    protected $serializerHandler;

    /**
     * @var callable|\Closure
     */
    protected $unserializerHandler;

    /**
     * @return SerializerTypeInterface
     */
    public static function create() : SerializerTypeInterface
    {
        return new static();
    }

    /**
     * @param mixed|null    $data
     * @param \Closure|null $visitor
     *
     * @return mixed
     */
    public function serialize($data = null, \Closure $visitor = null) : string
    {
        if ($visitor instanceof \Closure) {
            $data = $visitor($data);
        }

        return call_user_func($this->serializerHandler, $data);
    }

    /**
     * @param mixed|null    $data
     * @param \Closure|null $visitor
     *
     * @return mixed
     */
    public function unserialize($data = null, \Closure $visitor = null)
    {
        $data = call_user_func($this->unserializerHandler, $data);

        return $visitor instanceof \Closure ? $visitor($data) : $data;
    }
}

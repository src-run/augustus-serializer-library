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

interface SerializerTypeInterface
{
    /**
     * @return SerializerTypeInterface
     */
    public static function create() : SerializerTypeInterface;

    /**
     * @param mixed|null    $data
     * @param \Closure|null $visitor
     *
     * @return mixed
     */
    public function serialize($data = null, \Closure $visitor = null) : string;

    /**
     * @param mixed|null    $data
     * @param \Closure|null $visitor
     *
     * @return mixed
     */
    public function unserialize($data = null, \Closure $visitor = null);

    /**
     * @return bool
     */
    public static function supported() : bool;
}

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

namespace SR\Serializer\Type;

/**
 * Interface SerializerTypeInterface.
 */
interface SerializerTypeInterface
{
    /**
     * @param mixed|null    $data
     * @param \Closure|null $visitor
     *
     * @return mixed
     */
    public function serialize($data = null, \Closure $visitor = null);

    /**
     * @param mixed|null    $data
     * @param \Closure|null $visitor
     *
     * @return mixed
     */
    public function unserialize($data = null, \Closure $visitor = null);
}

/* EOF */

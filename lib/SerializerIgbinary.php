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
 * Class SerializerIgbinary.
 */
final class SerializerIgbinary extends AbstractSerializer
{
    /**
     * SerializerIgbinary constructor.
     */
    public function __construct()
    {
        $this->serializationHandler = 'igbinary_serialize';
        $this->unSerializationHandler = 'igbinary_unserialize';
    }
}

/* EOF */

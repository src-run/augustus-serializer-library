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

use SR\Utility\EngineInspect;

/**
 * Class SerializerTypeIgbinary.
 */
final class SerializerTypeIgbinary extends AbstractSerializerType
{
    public function __construct()
    {
        $this->serializationHandler = 'igbinary_serialize';
        $this->unSerializationHandler = 'igbinary_unserialize';
    }

    /**
     * @return bool
     */
    public static function supported()
    {
        return EngineInspect::extensionLoaded('igbinary');
    }
}

/* EOF */

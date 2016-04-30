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
 * Class SerializerTypePhp.
 */
class SerializerTypePhp extends AbstractSerializerType
{
    public function __construct()
    {
        $this->serializationHandler = 'serialize';
        $this->unSerializationHandler = 'unserialize';
    }

    /**
     * @return bool
     */
    public static function supported()
    {
        return true;
    }
}

/* EOF */

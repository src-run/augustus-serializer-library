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

use SR\Util\Info\EngineInfo;

final class SerializerTypeIgbinary extends AbstractSerializerType
{
    public function __construct()
    {
        $this->serializerHandler = 'igbinary_serialize';
        $this->unserializerHandler = 'igbinary_unserialize';
    }

    /**
     * @return bool
     */
    public static function supported() : bool
    {
        return EngineInfo::extensionLoaded('igbinary');
    }
}

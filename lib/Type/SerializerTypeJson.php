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

class SerializerTypeJson extends AbstractSerializerType
{
    public function __construct()
    {
        $this->serializerHandler = 'json_encode';
        $this->unserializerHandler = 'json_decode';
    }

    /**
     * @return bool
     */
    public static function supported() : bool
    {
        return EngineInfo::extensionLoaded('json');
    }
}

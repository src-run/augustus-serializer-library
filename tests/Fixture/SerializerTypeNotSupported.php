<?php

/*
 * This file is part of the `src-run/augustus-serializer-library` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\Serializer\Tests\Fixture;

use SR\Serializer\Type\AbstractSerializerType;

class SerializerTypeNotSupported extends AbstractSerializerType
{
    /**
     * @return bool
     */
    public static function supported() : bool
    {
        return false;
    }
}

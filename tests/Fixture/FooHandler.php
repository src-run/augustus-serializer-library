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

use SR\Serializer\Handler\ClosureHandler;

class FooHandler extends ClosureHandler
{
    public function __construct()
    {
        parent::__construct(
            function ($data) { return $data; },
            function ($data) { return $data; }
        );
    }

    /**
     * @return bool
     */
    public static function isSupported(): bool
    {
        return false;
    }
}

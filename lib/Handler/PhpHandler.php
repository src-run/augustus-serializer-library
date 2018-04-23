<?php

/*
 * This file is part of the `src-run/augustus-serializer-library` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\Serializer\Handler;

final class PhpHandler extends ClosureHandler
{
    public function __construct()
    {
        parent::__construct(
            function ($data) { return serialize($data); },
            function ($data) { return unserialize($data); }
        );
    }
}

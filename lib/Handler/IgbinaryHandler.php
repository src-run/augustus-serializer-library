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

use SR\Utilities\Query\EngineQuery;

final class IgbinaryHandler extends ClosureHandler
{
    public function __construct()
    {
        parent::__construct(
            function ($data) { return igbinary_serialize($data); },
            function ($data) { return igbinary_unserialize($data); }
        );
    }

    public static function isSupported(): bool
    {
        return parent::isSupported()
            && EngineQuery::extensionLoaded('igbinary')
            && function_exists('igbinary_serialize')
            && function_exists('igbinary_unserialize');
    }
}

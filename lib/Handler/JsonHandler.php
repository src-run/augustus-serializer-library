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

final class JsonHandler extends ClosureHandler
{
    public function __construct()
    {
        parent::__construct(
            function ($data) { return json_encode($data); },
            function ($data) { return json_decode($data); }
        );
    }

    /**
     * @return bool
     */
    public static function isSupported(): bool
    {
        return parent::isSupported()
            && EngineQuery::extensionLoaded('json')
            && function_exists('json_encode')
            && function_exists('json_decode');
    }
}

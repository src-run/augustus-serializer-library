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

use SR\Serializer\Visitor\VisitorInterface;

interface HandlerInterface
{
    /**
     * @param mixed|null $data
     */
    public function doSerialization($data = null, VisitorInterface ...$visitors): string;

    /**
     * @return mixed
     */
    public function unSerialization(string $data = null, VisitorInterface ...$visitors);

    public static function isSupported(): bool;
}

<?php

/*
 * This file is part of the `src-run/augustus-serializer-library` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\Serializer;

use SR\Serializer\Handler\HandlerInterface;
use SR\Serializer\Visitor\VisitorInterface;

interface SerializerInterface
{
    /**
     * @param mixed ...$handlerArguments
     */
    public static function create(string $handlerName = null, ...$handlerArguments): self;

    public function getHandler(): HandlerInterface;

    /**
     * @return VisitorInterface[]
     */
    public function getSerializeVisitors(): array;

    public function registerSerializeVisitors(VisitorInterface ...$visitors): self;

    public function removeSerializeVisitors(VisitorInterface ...$visitors): self;

    /**
     * @return VisitorInterface[]
     */
    public function getUnSerializeVisitors(): array;

    public function registerUnSerializeVisitors(VisitorInterface ...$visitors): self;

    public function removeUnSerializeVisitors(VisitorInterface ...$visitors): self;

    /**
     * @param mixed $data
     */
    public function serialize($data, VisitorInterface ...$runtimeVisitors): string;

    /**
     * @return mixed
     */
    public function unSerialize(string $data, VisitorInterface ...$runtimeVisitors);
}

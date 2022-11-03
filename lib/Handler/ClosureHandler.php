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

class ClosureHandler implements HandlerInterface
{
    /**
     * @var \Closure
     */
    protected $doSerializationClosure;

    /**
     * @var \Closure
     */
    protected $unSerializationClosure;

    public function __construct(\Closure $doSerializationClosure, \Closure $unSerializationHandler)
    {
        $this->doSerializationClosure = $doSerializationClosure;
        $this->unSerializationClosure = $unSerializationHandler;
    }

    public static function isSupported(): bool
    {
        return true;
    }

    /**
     * @param mixed|null $data
     */
    public function doSerialization($data = null, VisitorInterface ...$visitors): string
    {
        return ($this->doSerializationClosure)($this->visitVisitors($data, $visitors));
    }

    /**
     * @return mixed
     */
    public function unSerialization(string $data = null, VisitorInterface ...$visitors)
    {
        return $this->visitVisitors(($this->unSerializationClosure)($data), $visitors);
    }

    /**
     * @param mixed              $data
     * @param VisitorInterface[] $visitors
     *
     * @return mixed
     */
    private function visitVisitors($data, array $visitors)
    {
        foreach ($visitors as $visitor) {
            $data = $visitor->visit($data);
        }

        return $data;
    }
}

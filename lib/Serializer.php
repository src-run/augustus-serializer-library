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
use SR\Serializer\Handler\IgbinaryHandler;
use SR\Serializer\Handler\JsonHandler;
use SR\Serializer\Handler\PhpHandler;
use SR\Serializer\Visitor\VisitorInterface;

final class Serializer implements SerializerInterface
{
    /**
     * @var string[]
     */
    private const AVAILABLE_HANDLERS = [
        IgbinaryHandler::class,
        PhpHandler::class,
        JsonHandler::class,
    ];

    /**
     * @var HandlerInterface|null
     */
    private $handler;

    /**
     * @var VisitorInterface[]
     */
    private $doVisitors = [];

    /**
     * @var VisitorInterface[]
     */
    private $unVisitors = [];

    /**
     * @param mixed ...$handlerArguments
     */
    public function __construct(string $handlerName = null, ...$handlerArguments)
    {
        $this->handler = $this->setupHandler($handlerName, $handlerArguments);
    }

    /**
     * @param mixed ...$handlerArguments
     */
    public static function create(string $handlerName = null, ...$handlerArguments): SerializerInterface
    {
        return new self($handlerName, ...$handlerArguments);
    }

    public function getHandler(): HandlerInterface
    {
        return $this->handler;
    }

    /**
     * @return VisitorInterface[]
     */
    public function getSerializeVisitors(): array
    {
        return $this->doVisitors;
    }

    public function registerSerializeVisitors(VisitorInterface ...$visitors): SerializerInterface
    {
        $this->doVisitors = array_merge($this->doVisitors, self::filterVisitors($visitors, $this->doVisitors));

        return $this;
    }

    public function removeSerializeVisitors(VisitorInterface ...$visitors): SerializerInterface
    {
        $this->doVisitors = self::filterVisitors($this->doVisitors, $visitors ?: $this->doVisitors);

        return $this;
    }

    /**
     * @return VisitorInterface[]
     */
    public function getUnSerializeVisitors(): array
    {
        return $this->unVisitors;
    }

    public function registerUnSerializeVisitors(VisitorInterface ...$visitors): SerializerInterface
    {
        $this->unVisitors = array_merge($this->unVisitors, self::filterVisitors($visitors, $this->unVisitors));

        return $this;
    }

    public function removeUnSerializeVisitors(VisitorInterface ...$visitors): SerializerInterface
    {
        $this->unVisitors = self::filterVisitors($this->unVisitors, $visitors ?: $this->unVisitors);

        return $this;
    }

    /**
     * @param mixed $data
     */
    public function serialize($data, VisitorInterface ...$runtimeVisitors): string
    {
        return $this->getHandler()->doSerialization($data, ...array_merge($this->doVisitors, $runtimeVisitors));
    }

    /**
     * @return mixed
     */
    public function unSerialize(string $data, VisitorInterface ...$runtimeVisitors)
    {
        return $this->getHandler()->unSerialization($data, ...array_merge($this->unVisitors, $runtimeVisitors));
    }

    private function setupHandler(string $name = null, array $arguments = []): ?HandlerInterface
    {
        $exceptions = [];

        foreach (null !== $name ? [$name] : self::AVAILABLE_HANDLERS as $handler) {
            try {
                return self::createHandler($handler, $arguments);
            } catch (\Exception $exception) {
                $exceptions[] = $exception;
            }
        }

        throw new \InvalidArgumentException(sprintf('Failed to setup handlers: %s', implode(', ', array_map(function (\Exception $exception): string { return sprintf('"%s"', $exception->getMessage()); }, $exceptions))), 0, array_shift($exceptions));
    }

    /**
     * @param mixed[] $constructorArguments
     *
     * @return HandlerInterface|object
     */
    private static function createHandler(string $className, array $constructorArguments): HandlerInterface
    {
        try {
            $reflection = new \ReflectionClass($className);
        } catch (\ReflectionException $exception) {
            throw new \RuntimeException(sprintf('Provided handler "%s" could not have a \ReflectionClass created.', $className));
        }

        if (!$reflection->implementsInterface(HandlerInterface::class)) {
            throw new \InvalidArgumentException(sprintf('Provided handler "%s" does not implement "%s".', $reflection->getName(), HandlerInterface::class));
        }

        if (!$reflection->getMethod('isSupported')->invoke(null)) {
            throw new \RuntimeException(sprintf('Provided handler "%s" reported being unsupported in current environment.', $reflection->getName()));
        }

        return $reflection->newInstanceArgs($constructorArguments);
    }

    private static function filterVisitors(array $visitors, array $blacklist): array
    {
        return array_filter($visitors, function (VisitorInterface $v) use ($blacklist) {
            return false === in_array($v, $blacklist, true);
        });
    }
}

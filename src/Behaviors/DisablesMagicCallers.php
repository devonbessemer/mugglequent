<?php
namespace Dev\Mugglequent\Behaviors;

use Dev\Mugglequent\Exceptions\ProtectedMethodException;
use Dev\Mugglequent\Exceptions\UndefinedMethodException;

trait DisablesMagicCallers
{
    private $allowedProxyMethods = ["hydrate"];

    final public function __call($name, $arguments)
    {
        if (in_array($name, $this->allowedProxyMethods)) {
            return self::query()->{$name}(...$arguments);
        }

        if (method_exists($this, $name)) {
            throw new ProtectedMethodException("Method $name is not accessible.");
        }

        throw new UndefinedMethodException("Method $name is undefined.");
    }

    final public static function __callStatic($name, $arguments)
    {
        if (method_exists(static::class, $name)) {
            throw new ProtectedMethodException("Static method $name is not accessible.");
        }

        throw new UndefinedMethodException("Static method $name is undefined.");
    }
}

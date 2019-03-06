<?php
namespace Dev\Mugglequent\Behaviors;

use Dev\Mugglequent\Exceptions\ProtectedPropertyException;
use Dev\Mugglequent\Exceptions\UndefinedPropertyException;

trait DisablesMagicGetters
{
    /**
     * @throws \Dev\Mugglequent\Exceptions\ProtectedPropertyException
     * @throws \Dev\Mugglequent\Exceptions\UndefinedPropertyException
     */
    final public function __get($key)
    {
        if (property_exists($this, $key)) {
            throw new ProtectedPropertyException("Property $key is not an accessible.");
        }

        // Throw an exception (preferably something more descriptive) to disable the magic property getter
        throw new UndefinedPropertyException("Property $key is undefined.");
    }
}

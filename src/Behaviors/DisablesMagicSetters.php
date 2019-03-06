<?php
namespace Dev\Mugglequent\Behaviors;

use Dev\Mugglequent\Exceptions\ProtectedPropertyException;
use Dev\Mugglequent\Exceptions\UndefinedPropertyException;

trait DisablesMagicSetters
{
    /**
     * @throws \Dev\Mugglequent\Exceptions\ProtectedPropertyException
     * @throws \Dev\Mugglequent\Exceptions\UndefinedPropertyException
     */
    final public function __set($key, $value)
    {
        if (property_exists($this, $key)) {
            throw new ProtectedPropertyException("Property $key is not an accessible.");
        }

        // Throw an exception (preferably something more descriptive) to disable the magic property getter
        throw new UndefinedPropertyException("Property $key is undefined.");
    }

    /**
     * Set the value of the "created at" attribute.  This is overridden since it tries to use the magic setter.
     *
     * @param  mixed $value
     * @return $this
     */
    public function setCreatedAt($value)
    {
        $this->attributes[static::CREATED_AT] = $value;

        return $this;
    }

    /**
     * Set the value of the "updated at" attribute.  This is overridden since it tries to use the magic setter.
     *
     * @param  mixed $value
     * @return $this
     */
    public function setUpdatedAt($value)
    {
        $this->attributes[static::UPDATED_AT] = $value;

        return $this;
    }

    /**
     * Increment the underlying attribute value and sync with original.  This is overridden since it tries to use the magic setter.
     *
     * @param  string $column
     * @param  int $amount
     * @param  array $extra
     * @param  string $method
     * @return void
     */
    protected function incrementOrDecrementAttributeValue($column, $amount, $extra, $method)
    {
        $this->attributes[$column] = $this->attributes[$column] + ($method == 'increment' ? $amount : $amount * -1);

        $this->forceFill($extra);

        $this->syncOriginalAttribute($column);
    }
}

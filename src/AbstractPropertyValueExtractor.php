<?php

declare(strict_types=1);

namespace Phpolar\Model;

use ReflectionProperty;

/**
 * Provides a default implementation used to *extract* the value of a property.
 */
abstract class AbstractPropertyValueExtractor implements PropertyValueSetterInterface
{
    protected mixed $val;

    /**
     * Immutably sets the property value
     *
     * @internal
     */
    public function withPropVal(ReflectionProperty $prop, object $obj): static
    {
        $copy = clone $this;
        if ($prop->isInitialized($obj) === true) {
            $copy->val = $prop->getValue($obj);
        }
        if ($prop->hasDefaultValue() === true) {
            $copy->val = $prop->getDefaultValue();
        }
        return $copy;
    }
}

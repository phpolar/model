<?php

declare(strict_types=1);

namespace Phpolar\Model;

use PhpContrib\Validator\ValidatorInterface;
use ReflectionAttribute;
use ReflectionObject;
use ReflectionProperty;

/**
 * Use to add support for validating the properties of an object.
 */
trait ValidationTrait
{
    /**
     * Determines if the configured properties of an object
     * are valid.
     *
     * The configuration is used to determine validity.
     *
     * @api
     */
    public function isValid(): bool
    {
        return array_reduce(
            (new ReflectionObject($this))->getProperties(ReflectionProperty::IS_PUBLIC),
            $this->validateProperty(...),
            true
        );
    }

    /**
     * Uses validation attributes to determine if the
     * property is valid.
     */
    private function validateProperty(bool $prev, ReflectionProperty $prop): bool
    {
        return $prev && array_reduce(
            $this->getValidators($prop),
            static fn(bool $previousResult, ValidatorInterface $currentAttribute) =>
            $previousResult && $currentAttribute->isValid(),
            true
        );
    }

    /**
     * Provides a way of retrieving only the validator attributes of a property.
     *
     * Returns only validation attributes.
     *
     * @return ValidatorInterface[]
     */
    private function getValidators(ReflectionProperty $prop): array
    {
        return array_map(
            function (ValidatorInterface $instance) use ($prop): ValidatorInterface {
                if (property_exists($instance, "propVal") === true) {
                    $instance->propVal = $prop->isInitialized($this) === true ? $prop->getValue($this) : $prop->getDefaultValue();
                }
                return $instance;
            },
            array_map(
                static fn(ReflectionAttribute $attr) => $attr->newInstance(),
                $prop->getAttributes(ValidatorInterface::class, ReflectionAttribute::IS_INSTANCEOF),
            ),
        );
    }
}

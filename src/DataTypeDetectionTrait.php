<?php

declare(strict_types=1);

namespace Phpolar\Model;

use Phpolar\StorageDriver\DataTypeUnknown;
use Phpolar\StorageDriver\StorageDriverInterface;
use Phpolar\StorageDriver\TypeName;
use ReflectionProperty;
use ReflectionNamedType;
use Stringable;

/**
 * Provides support for configuring column parameters used to create tables.
 */
trait DataTypeDetectionTrait
{
    /**
     * Uses the property type to determine the data type type.
     *
     * @api
     */
    public function getDataType(
        string $propName,
        StorageDriverInterface $storageDriver
    ): Stringable|DataTypeUnknown {
        $property = new ReflectionProperty($this, $propName);
        $propertyType = $property->getType();
        if ($propertyType instanceof ReflectionNamedType) {
            return $this->getDataTypeFromDeclaredProperty($propertyType, $storageDriver);
        }
        return $this->getDataTypeFromNotDeclaredProperty($property, $storageDriver);
    }

    private function getDataTypeFromDeclaredProperty(
        ReflectionNamedType $propertyType,
        StorageDriverInterface $storageDriver,
    ): Stringable|DataTypeUnknown {
        $propertyTypeName = $propertyType->getName();
        $typeName = parseTypeName($propertyTypeName);
        return match ($typeName) {
            TypeName::Invalid => new DataTypeUnknown(),
            default => $storageDriver->getDataType($typeName),
        };
    }

    private function getDataTypeFromNotDeclaredProperty(
        ReflectionProperty $property,
        StorageDriverInterface $storageDriver,
    ): Stringable|DataTypeUnknown {
        if ($property->isInitialized($this) === true) {
            $value = $property->getValue($this);

            if (is_object($value) === false) {
                return $storageDriver->getDataType(parseTypeName(gettype($value)));
            }

            if ($this->isInstanceOfDateTime($value) === true) {
                return $storageDriver->getDataType(TypeName::T_DateTime);
            }
        }
        return new DataTypeUnknown();
    }

    private function isInstanceOfDateTime(object $value): bool
    {
        return in_array(get_class($value), ["DateTimeImmutable", "DateTime"]);
    }
}

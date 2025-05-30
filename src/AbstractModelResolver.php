<?php

declare(strict_types=1);

namespace Phpolar\Model;

use Phpolar\ModelResolver\ModelResolverInterface;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use RuntimeException;

/**
 * Converts an object that is marked as a model
 * attribute to a argument-name-object key-value pair.
 */
abstract class AbstractModelResolver implements ModelResolverInterface
{
    public function __construct(
        protected mixed $unresolvedObj,
    ) {}

    /**
     * Return the argument-name, object key-value pair
     * of the Model.
     *
     * @return array<string,AbstractModel>
     */
    public function resolve(object $obj, string $methodName): array
    {
        $reflectionMethod = new ReflectionMethod($obj, $methodName);
        $methodArgs = $reflectionMethod->getParameters();
        $modelParams = array_filter($methodArgs, $this->hasModelParams(...));
        $modelParamNames = array_map($this->getParamName(...), $modelParams);
        $modelInstances = array_map($this->newModel(...), $modelParams);
        return array_filter(
            array_combine(
                $modelParamNames,
                $modelInstances,
            )
        );
    }

    private function getParamName(ReflectionParameter $param): string
    {
        return $param->getName();
    }

    private function hasModelParams(ReflectionParameter $param): bool
    {
        return count($param->getAttributes(Model::class)) > 0;
    }

    private function newModel(ReflectionParameter $param): ?AbstractModel
    {
        $paramType = $param->getType();
        if ($paramType instanceof ReflectionNamedType) {
            $className = $paramType->getName();
            if (is_subclass_of($className, AbstractModel::class) === false) {
                throw new RuntimeException(
                    sprintf(
                        "Argument of type %s is not a subclass of %s",
                        $className,
                        AbstractModel::class
                    )
                );
            }
            return new $className($this->unresolvedObj);
        }
        return null; // @codeCoverageIgnore
    }
}

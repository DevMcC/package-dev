<?php

namespace DevMcC\PackageDev\Core;

use DevMcC\PackageDev\CommandArgument\ProcessArguments;
use DevMcC\PackageDev\Environment\RootDirectory;
use DevMcC\PackageDev\Exception\Core\NonInjectableParameter;
use ReflectionMethod;

class DependencyInjection
{
    /** @var object[] $resolvedClassNames */
    private $resolvedClassNames = [];

    public function __construct(
        ProcessArguments $processArguments,
        RootDirectory $rootDirectory
    ) {
        $this->resolvedClassNames[self::class] = $this;
        $this->resolvedClassNames[ProcessArguments::class] = $processArguments;
        $this->resolvedClassNames[RootDirectory::class] = $rootDirectory;
    }

    /**
     * @return object
     */
    public function resolveClassName(string $className)
    {
        $resolvedClassName = $this->resolvedClassNames[$className] ?? null;

        if (!$resolvedClassName) {
            $resolvedClassName = new $className(... $this->resolveDependencyTree($className));
            $this->resolvedClassNames[$className] = $resolvedClassName;
        }

        return $resolvedClassName;
    }

    /**
     * @return object[]
     *
     * @throws NonInjectableParameter
     */
    private function resolveDependencyTree(string $className): array
    {
        if (!method_exists($className, '__construct')) {
            return [];
        }

        $parameters = (new ReflectionMethod($className, '__construct'))->getParameters();

        if (!$parameters) {
            return [];
        }

        $dependencies = [];

        foreach ($parameters as $parameter) {
            $reflectionClass = $parameter->getClass();

            if (!$reflectionClass) {
                throw new NonInjectableParameter($className, $parameter->getName());
            }

            $dependencies[] = $this->resolveClassName($reflectionClass->name);
        }

        return $dependencies;
    }
}

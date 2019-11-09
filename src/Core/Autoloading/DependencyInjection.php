<?php

namespace DevMcC\PackageDev\Core\Autoloading;

use DevMcC\PackageDev\CommandArgument\ProcessArguments;
use DevMcC\PackageDev\Environment\RootDirectory;
use ReflectionMethod;

class DependencyInjection
{
    /** @var array $resolvedClassNames */
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
            $dependencies[] = $this->resolveClassName($parameter->getClass()->name);
        }

        return $dependencies;
    }
}

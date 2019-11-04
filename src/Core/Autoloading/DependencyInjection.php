<?php

namespace DevMcC\PackageDev\Core\Autoloading;

use DevMcC\PackageDev\CommandArgument\ProcessArguments;
use DevMcC\PackageDev\Environment\RootDirectory;
use ReflectionMethod;

class DependencyInjection
{
    /** @var array $matchedDependencies */
    private $matchedDependencies = [];

    public function __construct(
        ProcessArguments $processArguments,
        RootDirectory $rootDirectory
    ) {
        $this->matchedDependencies[self::class] = $this;
        $this->matchedDependencies[ProcessArguments::class] = $processArguments;
        $this->matchedDependencies[RootDirectory::class] = $rootDirectory;
    }

    /**
     * @param string $class
     *
     * @return object
     */
    public function resolveClass($class)
    {
        return new $class(... $this->resolveDependencies($class));
    }

    public function resolveDependencies(string $dependent): array
    {
        if (!method_exists($dependent, '__construct')) {
            return [];
        }

        $dependencies = [];
        $parameters = (new ReflectionMethod($dependent, '__construct'))->getParameters();

        if (!$parameters) {
            return [];
        }

        foreach ($parameters as $parameter) {
            $dependencies[] = $this->getDependency($parameter->getClass()->name);
        }

        return $dependencies;
    }

    /**
     * @return object
     */
    private function getDependency(string $className)
    {
        $dependency = $this->matchedDependencies[$className] ?? null;

        if (!$dependency) {
            $dependency = $this->matchedDependencies[$className] = $this->resolveClass($className);
        }

        return $dependency;
    }
}

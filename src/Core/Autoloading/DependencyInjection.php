<?php

namespace DevMcC\PackageDev\Core\Autoloading;

use DevMcC\PackageDev\CommandArgument\ProcessArguments;
use DevMcC\PackageDev\Environment\OperatingSystem;
use DevMcC\PackageDev\Environment\RootDirectory;
use ReflectionMethod;

class DependencyInjection
{
    /**
     * @var array $matchedDependencies
     */
    private $matchedDependencies = [];

    public function __construct(
        ProcessArguments $processArguments,
        OperatingSystem $operatingSystem,
        RootDirectory $rootDirectory
    ) {
        $this->matchedDependencies[ProcessArguments::class] = $processArguments;
        $this->matchedDependencies[OperatingSystem::class] = $operatingSystem;
        $this->matchedDependencies[RootDirectory::class] = $rootDirectory;
        $this->matchedDependencies[self::class] = $this;
    }

    public function get(string $dependent): array
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
        $depency = $this->matchedDependencies[$className] ?? null;

        if (!$depency) {
            $depency = $this->matchedDependencies[$className] = new $className(... $this->get($className));
        }

        return $depency;
    }
}
<?php

namespace DevMcC\PackageDev\Core\Autoloading;

class ClassLoader
{
    private const CLASS_PREFIX = 'DevMcC\\PackageDev';

    /** @var string $srcDir */
    private $srcDir;

    public function __construct(string $srcDir)
    {
        $this->srcDir = $srcDir;
    }

    public function autoload(string $className): void
    {
        $classPath = str_replace(self::CLASS_PREFIX, $this->srcDir, $className);
        $classPath = str_replace('\\', '/', $classPath);
        $classPath = $classPath . '.php';

        classLoaderRequire($classPath);
    }
}

function classLoaderRequire(string $classPath): void
{
    require $classPath;
}

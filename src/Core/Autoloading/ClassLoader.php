<?php

namespace DevMcC\PackageDev\Core\Autoloading;

class ClassLoader
{
    public const CLASS_NAME_PREFIX = 'DevMcC\\PackageDev';

    /** @var string $srcDir */
    private $srcDir;

    public function __construct(string $srcDir)
    {
        $this->srcDir = $srcDir;
    }

    public function autoload(string $className): void
    {
        $classPath = str_replace(self::CLASS_NAME_PREFIX, $this->srcDir, $className);
        $classPath = str_replace('\\', '/', $classPath);
        $classPath = $classPath . '.php';

        classLoaderRequire($classPath);
    }
}

function classLoaderRequire(string $classPath): void
{
    require $classPath;
}

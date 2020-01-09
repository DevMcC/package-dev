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
        $classFile = str_replace(self::CLASS_NAME_PREFIX, $this->srcDir, $className);
        $classFile = str_replace('\\', '/', $classFile);
        $classFile = $classFile . '.php';

        if (is_file($classFile)) {
            classLoaderRequire($classFile);
        }
    }
}

function classLoaderRequire(string $classFile): void
{
    require $classFile;
}

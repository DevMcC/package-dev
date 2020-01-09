<?php

namespace DevMcC\PackageDev\Test\Core\Autoloading;

use DevMcC\PackageDev\Core\Autoloading\ClassLoader;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class ClassLoaderTest extends TestCase
{
    private const CLASS_FILE_CODE = <<<PHP
<?php
namespace %s;
class %s {}
PHP;

    /** @var ClassLoader $classLoader */
    private $classLoader;

    /** @var vfsStreamDirectory $vfs */
    private $vfs;

    protected function setUp(): void
    {
        $this->vfs = vfsStream::setup();

        $this->classLoader = new ClassLoader(
            $this->vfs->url()
        );
    }

    public function testAutoload(): void
    {
        // Preparing test.
        $stubClassName = $this->prepareClass(true);

        // Starting test.
        $this->classLoader->autoload($stubClassName);

        // Assertion.
        $this->assertTrue(class_exists($stubClassName));
    }

    public function testAutoloadWhenFileDoesNotExist(): void
    {
        // Preparing test.
        $stubClassName = $this->prepareClass(false);

        // Starting test.
        $this->classLoader->autoload($stubClassName);

        // Assertion.
        // Class does not exist, as the file is not present.
        $this->assertFalse(class_exists($stubClassName));
    }

    private function prepareClass(bool $createClass): string
    {
        while (true) {
            $class = sprintf('ClassLoaderTestStub%s', md5((string)rand()));
            $className = sprintf('%s\\%s', ClassLoader::CLASS_NAME_PREFIX, $class);

            if (class_exists($className)) {
                continue;
            }

            if ($createClass) {
                $classFile = sprintf('%s.php', $class);
                $classCode = sprintf(self::CLASS_FILE_CODE, ClassLoader::CLASS_NAME_PREFIX, $class);

                $this->vfs->addChild(
                    vfsStream::newFile($classFile)->withContent($classCode)
                );
            }

            return $className;
        }
    }
}

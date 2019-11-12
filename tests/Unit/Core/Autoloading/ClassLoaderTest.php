<?php

namespace DevMcC\PackageDev\Test\Core\Autoloading;

use DevMcC\PackageDev\Core\Autoloading\ClassLoader;
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

    /** @var string $classStubClassName */
    private $classStubClassName;
    /** @var string $classStubPath */
    private $classStubPath;

    protected function setUp(): void
    {
        $this->setUpClassStub();

        $this->classLoader = new ClassLoader(dirname($this->classStubPath));
    }

    protected function tearDown(): void
    {
        unlink($this->classStubPath);

        unset($this->classStubClassName);
        unset($this->classStubPath);
    }

    public function testAutoload(): void
    {
        // Pre-test assertion.
        $this->assertFalse(class_exists($this->classStubClassName));

        // Starting test.
        $this->classLoader->autoload($this->classStubClassName);

        // Assertion.
        $this->assertTrue(class_exists($this->classStubClassName));
    }

    private function setUpClassStub(): void
    {
        while (true) {
            $classPath = sprintf(
                '%s/ClassLoaderTestStub%s.php',
                sys_get_temp_dir(),
                sha1((string)rand())
            );

            if (is_file($classPath)) {
                continue;
            }

            $class = pathinfo($classPath)['filename'];
            $className = sprintf('%s\\%s', ClassLoader::CLASS_NAME_PREFIX, $class);
            $code = sprintf(self::CLASS_FILE_CODE, ClassLoader::CLASS_NAME_PREFIX, $class);
            $handle = fopen($classPath, 'w');

            if (!$handle) {
                continue;
            }

            fwrite($handle, $code);
            fclose($handle);

            $this->classStubClassName = $className;
            $this->classStubPath = $classPath;

            break;
        }
    }
}

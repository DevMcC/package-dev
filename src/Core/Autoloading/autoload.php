<?php

require_once __DIR__  . '/ClassLoader.php';

use DevMcC\PackageDev\Core\Autoloading\ClassLoader;

$classLoader = new ClassLoader(__DIR__ . '/../..');

spl_autoload_register([$classLoader, 'autoload']);

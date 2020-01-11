<?php

require_once __DIR__  . '/../Core/ClassLoader.php';

use DevMcC\PackageDev\Core\ClassLoader;

$classLoader = new ClassLoader(realpath(__DIR__ . '/..'));

spl_autoload_register([$classLoader, 'autoload']);

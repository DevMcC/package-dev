<?php

require_once __DIR__ . '/Core/ClassLoader.php';

use DevMcC\PackageDev\CommandArgument\ProcessArguments;
use DevMcC\PackageDev\Core\ClassLoader;
use DevMcC\PackageDev\Core\CommandHandler;
use DevMcC\PackageDev\Core\DependencyInjection;
use DevMcC\PackageDev\Environment\RootDirectory;
use DevMcC\PackageDev\Exception\Command\TerminateCommand;

spl_autoload_register([(new ClassLoader(__DIR__)), 'autoload']);

$dependencyInjection = new DependencyInjection(
    new ProcessArguments($argv),
    new RootDirectory(getcwd(), __DIR__)
);

/**
 * @var CommandHandler $commandHandler
 */
$commandHandler = $dependencyInjection->resolveClassName(CommandHandler::class);

try {
    $commandHandler->handle();
} catch (TerminateCommand $e) {
    exit(1);
}

exit(0);

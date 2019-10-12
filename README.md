# TODO
* [x] core
* [x] help
* [x] init
* [x] link
* [x] unlink
* [x] symlink-create
* [x] symlink-remove
* [x] symlink management
* [ ] unit tests
    * [x] src\Command\HelpCommand.php
    * [x] src\Command\InitCommand.php
    * [x] src\Command\LinkCommand.php
    * [x] src\Command\SymlinkCreateCommand.php
    * [x] src\Command\SymlinkRemoveCommand.php
    * [x] src\Command\UnlinkCommand.php
    * [x] src\CommandArgument\CommandOptionalArgument.php
    * [x] src\CommandArgument\PackageArgument.php
    * [x] src\CommandArgument\ProcessArguments.php
    * [x] src\Config\CommandMapping.php
    * [ ] src\Core\Autoloading\ClassLoader.php
    * [ ] src\Core\Autoloading\DependencyInjection.php
    * [x] src\Core\CommandHandler.php
    * [x] src\Core\Output.php
    * [ ] src\Environment\Environment.php
    * [ ] src\Environment\FileSystem.php
    * [x] src\Environment\PackageManagement.php
    * [ ] src\Environment\PackagesFile.php
    * [ ] src\Environment\RootDirectory.php
    * [ ] src\Environment\UseCase\GetVendorPathFromPackage.php
    * [ ] src\Environment\UseCase\CreateSymlinkForPackagePath.php
    * [ ] src\Environment\UseCase\RemoveSymlinkFromPackagePath.php
    * intelephense fix
* [ ] restructure exceptions
    * [ ] better message constants
* [ ] stub prefix for shared values
* [ ] phar
* [ ] run in scripts
* [ ] remaining unit tests
    * [ ] phar command
* [ ] check code coverage
* [ ] phpstan?
* [ ] test on linux
* [ ] configure for github
* [ ] finish README

# Tests
After you have cloned the repo and installed all dependencies you can do the following:
* Run unit tests with: `vendor/bin/phpunit`

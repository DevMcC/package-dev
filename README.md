# TODO
* [x] core
* [x] help
* [x] init
* [x] link
* [x] unlink
* [x] symlink-create
* [x] symlink-remove
* [x] symlink management
* [x] unit tests
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
    * [x] src\Core\ClassLoader.php
    * [x] src\Core\DependencyInjection.php
    * [x] src\Core\CommandHandler.php
    * [x] src\Core\Output.php
    * [x] src\Environment\Environment.php
    * [x] src\Environment\FileSystem.php
    * [x] src\Environment\PackageManagement.php
    * [x] src\Environment\PackagesFile.php
    * [x] src\Environment\RootDirectory.php
    * [x] src\Environment\UseCase\GetVendorPathFromPackage.php
    * [x] src\Environment\UseCase\CreateSymlinkForPackagePath.php
    * [x] src\Environment\UseCase\RemoveSymlinkFromPackagePath.php
* [x] @var
* [x] better namespace for tests
* [x] restructure exceptions
    * [x] better message constants
* [x] stub prefix for shared values in unit tests
* [x] phar
    * [x] fix error
    * [x] do not clear existing archive
    * [x] unit test
    * [x] test autoloading
    * [x] test run in scripts
* [x] unit tests that are testing Output
* [x] combine FS tests
* [x] unit test intelephense fix & MockObject|
* [x] fix CommandHandlerTest
* [ ] other
    * [x] phpstan
    * [x] add tests for:
        * [x] NonInjectableParameter
        * [x] CommandNotFound null
        * [x] InvalidPackageName null
        * [x] UnableToWriteToPackagesFile false
    * [ ] code coverage
    * [ ] integration test?
* [ ] complete testing
* [ ] configure pipeline for github
* [ ] finish README

# Testing
After you have cloned the repo and installed all dependencies you can do the following:
* Run unit tests with `vendor/bin/phpunit`
* Run code analysis with `vendor/bin/phpstan analyse src tests --level max`

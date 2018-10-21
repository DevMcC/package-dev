<?php

namespace DevMcC\PackageDev\Commands;

use DevMcC\PackageDev\Extendables\Command;
use DevMcC\PackageDev\Classes\Output;
use DevMcC\PackageDev\Traits as PackageDevTraits;

/**
 * Class Init
 *
 * @author  DevMcC <sinbox.c@gmail.com>
 *
 * @package DevMcC\PackageDev\Commands
 */
class Init extends Command
{
    use PackageDevTraits\SetsProperties;
    use PackageDevTraits\WritesToLinkedFile;

    /**
     * Execute the console command.
     *
     * @author DevMcC <sinbox.c@gmail.com>
     *
     * @return void
     */
    public function handle()
    {
        $this->setProperties();

        if (is_file($this->packagesPath.'package-dev.json')) {
            Output::abort('ERROR: PackageDev is already initialized.');
        }

        if (!is_dir($this->packagesPath)) {
            if (!mkdir($this->packagesPath)) {
                Output::abort('ERROR: Unable to create packages directory.');
            }

            Output::msg('Created packages directory.');
        }

        if (!touch($this->packagesPath.'package-dev.json')) {
            Output::abort('ERROR: Unable to create package-dev.json file.');
        }

        $this->writeToLinkedFile([
            'packages' => [],
        ]);

        Output::msg('Created package-dev.json file.');
        Output::msg('PackageDev initialized.');
    }
}

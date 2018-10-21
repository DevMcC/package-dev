<?php

namespace DevMcC\PackageDev\Commands;

use DevMcC\PackageDev\Extendables\Command;
use DevMcC\PackageDev\Classes\Output;
use DevMcC\PackageDev\Traits as PackageDevTraits;

/**
 * Class JunctionMake
 *
 * @author  DevMcC <sinbox.c@gmail.com>
 *
 * @package DevMcC\PackageDev\Commands
 */
class JunctionMake extends Command
{
    use PackageDevTraits\AcceptsArguments;
    use PackageDevTraits\AcceptsPackageArgument;
    use PackageDevTraits\ManagesJunctions;
    use PackageDevTraits\ReadsLinkedFile;
    use PackageDevTraits\SetsProperties;
    use PackageDevTraits\VerifiesInit;

    /**
     * The arguments this command requires.
     *
     * @return array
     */
    protected function arguments()
    {
        return [
            'package' => 1,
        ];
    }

    /**
     * Defines if the argument is optional.
     *
     * @author DevMcC <sinbox.c@gmail.com>
     *
     * @return bool
     */
    protected function optionalPackageArgument()
    {
        return true;
    }

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
        $this->verifyInit();
        $this->setPackage();

        if ($this->packageArgument) {
            $this->validatePackageArgument();
            $this->junctionMake();

            return;
        }

        $this->readLinkedFile();

        foreach ($this->linkedPackages['packages'] as $package) {
            $this->junctionMake($package);
        }
    }
}

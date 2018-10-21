<?php

namespace DevMcC\PackageDev\Commands;

use DevMcC\PackageDev\Extendables\Command;
use DevMcC\PackageDev\Classes\Output;
use DevMcC\PackageDev\Traits as PackageDevTraits;

/**
 * Class Link
 *
 * @author  DevMcC <sinbox.c@gmail.com>
 *
 * @package DevMcC\PackageDev\Commands
 */
class Link extends Command
{
    use PackageDevTraits\AcceptsArguments;
    use PackageDevTraits\AcceptsPackageArgument;
    use PackageDevTraits\ManagesJunctions;
    use PackageDevTraits\ReadsLinkedFile;
    use PackageDevTraits\SetsProperties;
    use PackageDevTraits\VerifiesInit;
    use PackageDevTraits\WritesToLinkedFile;

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
        $this->validatePackageArgument();
        $this->readLinkedFile();

        foreach ($this->linkedPackages['packages'] as $package) {
            if ($package === $this->packageArgument) {
                Output::abort('ERROR: Package is already linked.');
            }
        }

        $this->linkedPackages['packages'] []= $this->packageArgument;

        $this->junctionMake();
        $this->writeToLinkedFile();

        Output::msg('Package "'.$this->packageArgument.'" has been linked.');
    }
}

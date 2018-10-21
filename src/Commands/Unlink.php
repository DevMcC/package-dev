<?php

namespace DevMcC\PackageDev\Commands;

use DevMcC\PackageDev\Extendables\Command;
use DevMcC\PackageDev\Classes\Output;
use DevMcC\PackageDev\Traits as PackageDevTraits;

/**
 * Class Unlink
 *
 * @author  DevMcC <sinbox.c@gmail.com>
 *
 * @package DevMcC\PackageDev\Commands
 */
class Unlink extends Command
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

        $origPackageCount = count($this->linkedPackages['packages']);

        foreach ($this->linkedPackages['packages'] as $i => $package) {
            if ($package === $this->packageArgument) {
                unset($this->linkedPackages['packages'][$i]);
                break;
            }
        }

        if ($origPackageCount === count($this->linkedPackages['packages'])) {
            Output::abort('ERROR: Package was not found in package-dev.json.');
        }

        $this->junctionDrop();
        $this->writeToLinkedFile();

        Output::msg('Package "'.$this->packageArgument.'" has been unlinked.');
    }
}

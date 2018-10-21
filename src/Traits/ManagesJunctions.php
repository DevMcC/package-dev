<?php

namespace DevMcC\PackageDev\Traits;

use DevMcC\PackageDev\Classes\Output;

/**
 * Trait ManagesJunctions
 *
 * @author  DevMcC <sinbox.c@gmail.com>
 *
 * @package DevMcC\PackageDev\Traits
 */
trait ManagesJunctions
{
    /**
     * Checks if the directory is a junction.
     *
     * @author DevMcC <sinbox.c@gmail.com>
     *
     * @param  string $vendor
     * @param  string $package
     *
     * @return bool
     */
    private function isJunction(string $vendor, string $package)
    {
        return realpath($vendor) === realpath($package);
    }

    /**
     * (Re)sets the original package directory name.
     *
     * @author DevMcC <sinbox.c@gmail.com>
     *
     * @param  bool $mode
     * @param  string $packageArgument
     *
     * @return bool
     */
    protected function junctionBack(bool $mode, string $packageArgument)
    {
        $old = $mode ? '' : '_Bk';
        $new = $mode ? '_Bk' : '';
        return rename($this->vendorPath.$packageArgument.$old, $this->vendorPath.$packageArgument.$new);
    }

    /**
     * Makes a junction for the package.
     *
     * @author DevMcC <sinbox.c@gmail.com>
     *
     * @param  string $packageArgument
     *
     * @return void
     */
    protected function junctionMake(string $packageArgument = null)
    {
        $packageArgument = $packageArgument ?? $this->packageArgument;
        $vendorPackage = $this->vendorPath.$packageArgument;
        $packagesPackage = $this->packagesPath.$packageArgument;

        if ($this->isJunction($vendorPackage, $packagesPackage)) {
            Output::msg('NOTE: Package "'.$packageArgument.'" already has a junction.');
            return;
        }

        if (!$this->junctionBack(true, $packageArgument)) {
            Output::abort('ERROR: Could not back package.');
        }

        switch (php_uname('s')) {
            case "Windows NT":
                //Variables originate from packageArgument, which has been validated.
                if (!exec('mklink /J "'.$vendorPackage.'" "'.$packagesPackage.'"')) {
                    $this->junctionBack(false, $packageArgument);
                    Output::abort('ERROR: Could not make junction for package "'.$packageArgument.'". (1)');
                }

                break;
            case "Linux":
                //falling through
            case "Darwin":
                if (!link($packagesPackage, $vendorPackage)) {
                    $this->junctionBack(false, $packageArgument);
                    Output::abort('ERROR: Could not make junction for package "'.$packageArgument.'". (2)');
                }

                break;
            default:
                Output::abort('ERROR: Unknown OS.');
        }

        Output::msg('Junction for package "'.$packageArgument.'" has been made.');
    }

    /**
     * Drops a junction from the package.
     *
     * @author DevMcC <sinbox.c@gmail.com>
     *
     * @param  string $packageArgument
     *
     * @return void
     */
    protected function junctionDrop(string $packageArgument = null)
    {
        $packageArgument = $packageArgument ?? $this->packageArgument;
        $vendorPackage = $this->vendorPath.$packageArgument;
        $packagesPackage = $this->packagesPath.$packageArgument;

        if (!$this->isJunction($vendorPackage, $packagesPackage)) {
            Output::msg('NOTE: Package "'.$packageArgument.'" does not have a junction.');
            return;
        }

        switch (php_uname('s')) {
            case "Windows NT":
                //Variable originates from packageArgument, which has been validated.
                if (exec('rmdir "'.$vendorPackage.'"') !== "") {
                    Output::abort('ERROR: Could not drop junction for package "'.$packageArgument.'". (1)');
                }

                break;
            case "Linux":
                //falling through
            case "Darwin":
                if (!unlink($vendorPackage)) {
                    Output::abort('ERROR: Could not drop junction for package "'.$packageArgument.'". (2)');
                }

                break;
            default:
                Output::abort('ERROR: Unknown OS.');
        }

        Output::msg('Junction for package "'.$packageArgument.'" has been dropped.');

        if (is_dir($this->vendorPath.$packageArgument.'_Bk')) {
            if (!$this->junctionBack(false, $packageArgument)) {
                Output::abort('ERROR: Could not return package "'.$packageArgument.'".');
            }

            return;
        }

        Output::msg('NOTE: Backup file of package "'.$packageArgument.'" was not found, you may need to run "composer install".');
    }
}

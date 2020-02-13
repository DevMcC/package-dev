<?php

namespace DevMcC\PackageDev\Exception\Environment;

use Exception;

class UnableToCreatePharArchive extends Exception
{
    protected $message = <<<TXT
An error occured while trying to create a .phar archive

* This was most likely caused by the creation of archives being disabled by the php.ini setting phar.readonly
TXT;
}

<?php

namespace DevMcC\PackageDev\Environment\UseCase;

use DevMcC\PackageDev\Environment\FileSystem;
use Phar;

class CreatePharArchive
{
    /** @var FileSystem $fileSystem */
    private $fileSystem;

    public function __construct(
        FileSystem $fileSystem
    ) {
        $this->fileSystem = $fileSystem;
    }

    public function execute(string $fname): Phar
    {
        return new Phar(
            $this->fileSystem->rootPathOfFile($fname)
        );
    }
}

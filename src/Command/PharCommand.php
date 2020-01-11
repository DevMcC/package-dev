<?php

namespace DevMcC\PackageDev\Command;

use DevMcC\PackageDev\Core\Output;
use DevMcC\PackageDev\Environment\FileSystem;
use DevMcC\PackageDev\Environment\RootDirectory;
use DevMcC\PackageDev\Environment\UseCase\CreatePharArchive;
use DevMcC\PackageDev\Exception\Environment\UnableToCreatePharArchive;
use PharException;

class PharCommand implements Command
{
    public const COMMAND_NAME = 'phar';
    public const COMMAND_USAGE = 'package-dev phar';
    public const COMMAND_DESCRIPTION = 'Dumps PackageDev into a .phar archive.';

    private const PHAR_ARCHIVE_NAME = 'package-dev.phar';

    /** @var CreatePharArchive $createPharArchive */
    private $createPharArchive;
    /** @var FileSystem $fileSystem */
    private $fileSystem;
    /** @var RootDirectory $rootDirectory */
    private $rootDirectory;
    /** @var Output $output */
    private $output;

    public function __construct(
        CreatePharArchive $createPharArchive,
        FileSystem $fileSystem,
        RootDirectory $rootDirectory,
        Output $output
    ) {
        $this->createPharArchive = $createPharArchive;
        $this->fileSystem = $fileSystem;
        $this->rootDirectory = $rootDirectory;
        $this->output = $output;
    }

    public function handle(): void
    {
        $this->clearExistingArchive();
        $this->createNewArchive();

        $this->output->line(sprintf('%s was successfully created.', self::PHAR_ARCHIVE_NAME));
    }

    private function clearExistingArchive(): void
    {
        if ($this->fileSystem->doesFileExist(self::PHAR_ARCHIVE_NAME)) {
            $this->fileSystem->deleteFile(self::PHAR_ARCHIVE_NAME);
        }
    }

    private function createNewArchive(): void
    {
        try {
            $phar = $this->createPharArchive->execute(self::PHAR_ARCHIVE_NAME);
            $phar->startBuffering();
            $phar->buildFromDirectory($this->rootDirectory->packageDevRootDirectory() . '/src');
            $phar->setStub("#!/usr/bin/env php\n" . $phar->createDefaultStub('./index.php'));
            $phar->stopBuffering();
        } catch (PharException $e) {
            throw new UnableToCreatePharArchive();
        }
    }
}

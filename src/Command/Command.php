<?php

namespace DevMcC\PackageDev\Command;

interface Command
{
    public function handle(): void;
}

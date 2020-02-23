<?php

namespace DevMcC\PackageDev\Core;

class Output
{
    private const INDENT = '  ';

    public function line(string $message = '', int $indent = null): void
    {
        if ($indent) {
            $message = str_repeat(self::INDENT, $indent) . $message;
        }

        echo $message . PHP_EOL;
    }

    /**
     * @param array[] $list
     */
    public function list(array $list): void
    {
        $length = 0;

        foreach ($list as $listItem) {
            // We're currently just going to worry about a simple list for now.
            $length = max($length, strlen($listItem[0]));
        }

        foreach ($list as list($command, $description)) {
            $formattedCommand = $command . str_repeat(' ', $length - strlen($command));

            $this->line(
                sprintf(
                    '%s%s%s',
                    $formattedCommand,
                    Output::INDENT,
                    $description
                ),
                1
            );
        }
    }
}

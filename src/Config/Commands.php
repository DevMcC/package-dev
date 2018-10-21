<?php

namespace DevMcC\PackageDev\Config;

use DevMcC\PackageDev\Commands as Command;

/**
 * Class CommandsConfig
 *
 * @author  DevMcC <sinbox.c@gmail.com>
 *
 * @package DevMcC\PackageDev\Config
 */
class CommandsConfig
{
    /**
     * A list of available commands.
     *
     * @var array $commands
     */
    private static $commands = [
        'init' => Command\Init::class,
        'link' => Command\Link::class,
        'unlink' => Command\Unlink::class,
        'junction:drop' => Command\JunctionDrop::class,
        'junction:make' => Command\JunctionMake::class,
    ];

    /**
     * Get config.
     *
     * @author DevMcC <sinbox.c@gmail.com>
     *
     * @param  string $command
     *
     * @return mixed
     */
    public static function config(string $command)
    {
        return self::$commands[$command] ?? null;
    }
}

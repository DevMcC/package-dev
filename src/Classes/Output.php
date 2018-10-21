<?php

namespace DevMcC\PackageDev\Classes;

class Output
{
    /**
     * Outputs a message with an additional newline.
     *
     * @author DevMcC <sinbox.c@gmail.com>
     *
     * @param  string $msg
     *
     * @return void
     */
    public static function msg(string $msg)
    {
        echo $msg."\n";
    }
    
    /**
     * Aborting the application with a message.
     *
     * @author DevMcC <sinbox.c@gmail.com>
     *
     * @param  string $msg
     *
     * @throws Exception
     *
     * @return void
     */
    public static function abort(string $msg)
    {
        self::msg($msg."\n");

        throw new \Exception();
    }
}

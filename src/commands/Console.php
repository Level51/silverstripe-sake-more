<?php

namespace Level51\SakeMore;

use Psy\Shell;

/**
 * Start interactive php shell using PsySH.
 *
 * @see https://psysh.org/
 *
 * @package Level51\SakeMore
 */
class Console extends Command
{

    /**
     * Defines the url segment under which this command is callable.
     *
     * @return string
     */
    public function getUrlSegment()
    {
        return 'console';
    }

    /**
     * Description of the functionality of this specific command.
     *
     * @return string
     */
    public function getDescription()
    {
        return 'Enter PsySH console';
    }

    /**
     * Start PsySH shell.
     */
    public function run()
    {
        $shell = new Shell();
        $shell->run();
    }
}

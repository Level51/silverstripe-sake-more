<?php

namespace Level51\SakeMore;

/**
 * Connect to the local MySQL client.
 *
 * @package Level51\SakeMore
 */
class SQL extends Command {

    /**
     * Defines the url segment under which this command is callable.
     *
     * @return string
     */
    public function getUrlSegment() {
        return 'sql';
    }

    /**
     * Description of the functionality of this specific command.
     *
     * @return string
     */
    public function getDescription() {
        return 'Enter SQL console';
    }

    /**
     * Connect to mysql interactive shell.
     *
     * @return string
     * @throws SakeMoreException
     */
    public function run() {
        // Get the db connection config
        global $databaseConfig;

        // Check server/host
        if (!in_array($databaseConfig['server'], ['localhost', '127.0.0.1']))
            throw new SakeMoreException('The SakeMore SQL command supports only connections to localhost / 127.0.0.1');

        // Setup command
        $cmd = sprintf('mysql -u%s -p%s %s', $databaseConfig['username'], $databaseConfig['password'], $databaseConfig['database']);

        // Execute
        $process = proc_open(
            $cmd,
            [
                0 => STDIN,
                1 => STDOUT,
                2 => STDERR
            ],
            $pipes
        );

        return proc_close($process);
    }
}

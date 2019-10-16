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
     */
    public function run() {
        // Get the db connection config
        global $databaseConfig;

        // Start command setup
        $cmd = sprintf('mysql -u%s -p%s', $databaseConfig['username'], $databaseConfig['password']);

        // Check server/host, add database name and host if necessary (non localhost)
        if (in_array($databaseConfig['server'], ['localhost', '127.0.0.1']))
            $cmd .= sprintf(' %s', $databaseConfig['database']);
        else
            $cmd .= sprintf(' -h%s -D%s', $databaseConfig['server'], $databaseConfig['database']);

        // Add port if set
        if ($databaseConfig['port'])
            $cmd .= sprintf(' -P%s', $databaseConfig['port']);

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

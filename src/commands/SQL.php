<?php

namespace Level51\SakeMore;

use SilverStripe\ORM\DB;

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
        $conf = DB::getConfig();

        // Start command setup
        $cmd = sprintf('mysql -u%s -p%s', $conf['username'], $conf['password']);

        // Check server/host, add database name and host if necessary (non localhost)
        if (in_array($conf['server'], ['localhost', '127.0.0.1']))
            $cmd .= sprintf(' %s', $conf['database']);
        else
            $cmd .= sprintf(' -h%s -D%s', $conf['server'], $conf['database']);

        // Add port if set
        if (isset($conf['port']))
            $cmd .= sprintf(' -P%s', $conf['port']);

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

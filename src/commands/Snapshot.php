<?php

namespace Level51\SakeMore;

/**
 * Command wrapping around SSPAK to save or load snapshots.
 *
 * TODO actual implementation, just the skeleton yet
 *
 * @package Level51\SakeMore
 */
class Snapshot extends MultiCommand {

    /**
     * Defines the url segment under which this command is callable.
     *
     * @return string
     */
    public function getUrlSegment() {
        return 'snapshot';
    }

    /**
     * Description of the functionality of this specific command.
     *
     * @return string
     */
    public function getDescription() {
        return 'Test implementation snapshot multi command';
    }

    /**
     * Get a list of available sub commands.
     *
     * Has to return a array where each entry has a description and an action (method name) to execute.
     *
     * @return array
     */
    public function getSubCommands() {
        return [
            'save' => [
                'description' => 'Saves a snapshot to the temp directory of your system. Use the "--db" flag to save only the database.',
                'action'      => 'save'
            ],
            'load' => [
                'description' => 'Loads a snapshot into the local instance. The "src" parameter specifies the .sspak file to load. CAUTION: It overwrites the database and all the assets.',
                'action'      => 'load'
            ]
        ];
    }

    /**
     * TODO implement load function
     */
    public function load() {
        echo "\nTODO implement load function\n";
    }

    /**
     * TODO implement save function
     */
    public function save() {
        echo "\nTODO implement save function\n";
    }
}

<?php

namespace Level51\SakeMore;

use SilverStripe\Control\Director;
use SilverStripe\Core\Manifest\ModuleManifest;

/**
 * Command wrapping around SSPAK to save or load snapshots.
 *
 * @package Level51\SakeMore
 */
class Snapshot extends MultiCommand
{

    /**
     * Defines the url segment under which this command is callable.
     *
     * @return string
     */
    public function getUrlSegment()
    {
        return 'snapshot';
    }

    /**
     * Description of the functionality of this specific command.
     *
     * @return string
     */
    public function getDescription()
    {
        return 'Handles system snapshots. Utilizes SSPak.';
    }

    /**
     * Get a list of available sub commands.
     *
     * Has to return a array where each entry has a description and an action (method name) to execute.
     *
     * @return array
     */
    public function getSubCommands()
    {
        return [
            'save' => [
                'description' => 'Saves a snapshot to the temp directory of your system. Use the "--db" flag to save only the database.',
                'action'      => 'save',
            ],
            'load' => [
                'description' => 'Loads a snapshot into the local instance. The "src" parameter specifies the .sspak file to load. CAUTION: It overwrites the database and all the assets.',
                'action'      => 'load',
            ],
        ];
    }

    /**
     * Loads an existing snapshot.
     */
    public function load()
    {
        if (!$this->environmentIsValid()) {
            return;
        }

        // Check for source
        if (!isset($_GET['src']) ||
            !file_exists($_GET['src'])) {
            echo "You need to specify a valid .sspak file to load as \"src\" parameter (absolute path).";

            return;
        }

        // Remove current assets folder
        $command[] = 'rm -rf public/assets;';

        // Build sspak load command
        $command[] = 'sspak';
        $src = $_GET['src'];
        $command[] = "load {$src} " . Director::baseFolder();

        Util::runCLI(implode(' ', $command));

        echo "The snapshot \"" . array_reverse(explode(DIRECTORY_SEPARATOR, $src))[0] . "\" was loaded. You might have to clear the caches.";
    }

    private function getProjectName(): string
    {
        if ($project = ModuleManifest::config()->get('project')) {
            return $project;
        }

        if (isset($GLOBALS['project'])) {
            return $GLOBALS['project'];
        }

        return 'mysite';
    }

    /**
     * Saves a snapshot to a .sspak file.
     */
    public function save()
    {
        if (!$this->environmentIsValid()) {
            return;
        }

        $rootFolder = Director::baseFolder();
        $command = ['sspak save'];

        // Prepare file name and save location
        $folderName = array_reverse(explode(DIRECTORY_SEPARATOR, $rootFolder))[0];
        $snapshotName = implode('_', array(
            $folderName,
            $this->getProjectName(),
            date('Y-m-d-H-i-s'),
        ));
        $saveLocation = implode(DIRECTORY_SEPARATOR, array(
            sys_get_temp_dir(),
            $snapshotName,
        ));
        if ($this->hasFlag('db')) {
            $command[] = '--db';
        }

        $command[] = "{$rootFolder} {$saveLocation}.sspak";
        Util::runCLI(implode(' ', $command));

        echo "The snapshot was saved to \"{$saveLocation}.sspak\".";
    }

    /**
     * Checks if the snapshot cmd can be run on the current environment.
     *
     * @return bool
     */
    private function environmentIsValid()
    {
        if (Util::isWIN()) {
            echo 'The "snapshot" command is only available for Unix-based OS.';

            return false;
        }

        if (!Util::shellCommandExists('sspak')) {
            echo '"sspak" is not available. Check "https://github.com/silverstripe/sspak#installation" for an installation guide.';

            return false;
        }

        return true;
    }
}

<?php

namespace Level51\SakeMore;

use SebastianBergmann\CodeCoverage\Report\PHP;
use SilverStripe\Control\Controller;
use SilverStripe\Control\Director;

/**
 * Base class for additional development tools.
 *
 * This extends the default DevelopmentAdmin, so is accessible via sake dev/more.
 *
 * @package Level51\SakeMore
 */
class SakeMoreController extends Controller
{
    public function index()
    {
        // Enable for cli only
        if (!Director::is_cli()) {
            return $this->httpError(404);
        }

        $request = $this->getRequest();
        $getVars = $request->getVars();

        // No arguments given, so just print the module info
        if (!isset($getVars['args'])) {
            return $this->printInfo();
        }

        // Get arguments / command url segment
        $args = $getVars['args'];
        $commandUrlSegment = $args[0];

        // Try to get the related command and execute it
        if ($command = Util::getCommandInstance($commandUrlSegment, $request)) {
            try {
                $command->run();
            } catch (SakeMoreException $e) {
                echo PHP_EOL . 'ERROR: ' . $e->getMessage();
            }
        } else {
            return PHP_EOL . 'Command ' . $commandUrlSegment . ' not found' . PHP_EOL;
        }

        return PHP_EOL . 'SakeMore command finished' . PHP_EOL;
    }

    /**
     * Print module info including a list of all available commands.
     */
    private function printInfo()
    {
        echo PHP_EOL . 'SakeMore development commands' . PHP_EOL . '------------------------' . PHP_EOL;
        echo 'Available commands:' . PHP_EOL;

        foreach (Util::getCommands() as $command) {
            echo '    sake dev/more ' . $command['urlSegment'] . ': ' . $command['description'] . PHP_EOL;
        }

        echo PHP_EOL;
    }
}

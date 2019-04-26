<?php

namespace Level51\SakeMore;

use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\ClassInfo;

/**
 * Class providing various util methods.
 *
 * @package Level51\SakeMore
 */
class Util {

    /**
     * Get a list of all available commands.
     *
     * Collects all subclasses of the abstract command base class.
     *
     * @return array
     *   [
     *     UrlSegment => String,
     *     Description => String,
     *     Class => String
     *   ]
     */
    public static function getCommands() {
        $availableCommands = [];

        // Get all "Command" implementors
        $commandClasses = ClassInfo::subclassesFor(Command::class);

        // Remove abstract base class
        array_shift($commandClasses);

        foreach ($commandClasses as $commandClass) {
            try {
                // Skip abstract classes
                $reflection = new \ReflectionClass($commandClass);
                if ($reflection->isAbstract()) continue;

                $command = singleton($commandClass);
                $availableCommands[] = [
                    'urlSegment'  => $command->getUrlSegment(),
                    'description' => $command->getDescription(),
                    'class'       => $commandClass
                ];
            } catch (\Exception $e) {
            }
        }

        return $availableCommands;
    }

    /**
     * Get a command instance for the given url segment.
     *
     * @param string      $urlSegment Of the searched/requested command
     * @param HTTPRequest $request    The current request
     *
     * @return Command|null
     */
    public static function getCommandInstance($urlSegment, $request = null) {
        foreach (self::getCommands() as $command) {
            if ($command['urlSegment'] === $urlSegment) {
                $instance = singleton($command['class']);
                $instance->setRequest($request);

                return $instance;
            }
        }

        return null;
    }

    /**
     * Checks for Windows OS.
     * Taken from: https://stackoverflow.com/a/5879078
     *
     * @return bool
     */
    public static function isWIN() {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }

    /**
     * Checks if the given (Shell) command exists.
     * Taken from: https://stackoverflow.com/a/12425039
     *
     * @param string $cmd
     *
     * @return bool
     */
    public static function shellCommandExists($cmd) {
        $return = shell_exec(sprintf("which %s", escapeshellarg($cmd)));

        return !empty($return);
    }
}

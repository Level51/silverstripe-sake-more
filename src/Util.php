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
                $command = singleton($commandClass);
                $availableCommands[] = [
                    'UrlSegment'  => $command->getUrlSegment(),
                    'Description' => $command->getDescription(),
                    'Class'       => $commandClass
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
            if ($command['UrlSegment'] === $urlSegment) {
                $instance = singleton($command['Class']);
                $instance->setRequest = $request;

                return $instance;
            }
        }

        return null;
    }
}

<?php

namespace Level51\SakeMore;

use SS_HttpRequest;

/**
 * Base class for each SakeMore command.
 *
 * Each command can be executed via the sake dev/more COMMAND_URL_SEGMENT route.
 *
 * @package Level51\SakeMore
 */
abstract class Command {
    /**
     * @var SS_HttpRequest
     */
    private $request;

    /**
     * @param SS_HttpRequest $request
     */
    public function setRequest($request) {
        $this->request = $request;
    }

    /**
     * @return SS_HttpRequest
     */
    public function getRequest() {
        return $this->request;
    }

    /**
     * Get all request arguments.
     *
     * @param bool $withoutCurrent Whether the current active command should stay in the arguments list or not
     *
     * @return array
     */
    public function getAllArgs($withoutCurrent = true) {
        if (!$this->getRequest()) return [];

        $args = $this->getRequest()->getVars()['args'];

        if ($withoutCurrent)
            array_shift($args);

        return $args;
    }

    /**
     * Get only the request arguments not starting with -- (flag).
     *
     * @return array
     */
    public function getArgs() {
        $args = $this->getAllArgs();

        if (empty($args)) return $args;

        return array_filter($args, function ($arg) {
            return strpos($arg, '--') === false;
        });
    }

    /**
     * Get all request argument flags (starting with --).
     *
     * @return array
     */
    public function getFlags() {
        $args = $this->getAllArgs();

        if (empty($args)) return $args;

        return array_filter($args, function ($arg) {
            return strpos($arg, '--') !== false;
        });
    }

    /**
     * Check if given flag is set on the request.
     *
     * @param string $flag The flag to check, either in "--flag" format or just as "flag"
     *
     * @return bool
     */
    public function hasFlag($flag) {
        if (substr($flag, 0, 2) !== '--')
            $flag = '--' . $flag;

        return in_array($flag, $this->getFlags());
    }

    /**
     * Defines the url segment under which this command is callable.
     *
     * @return string
     */
    abstract public function getUrlSegment();

    /**
     * Description of the functionality of this specific command.
     *
     * @return string
     */
    abstract public function getDescription();

    /**
     * Defines the functionality of this command, this method is called on execution.
     */
    abstract public function run();
}

<?php

namespace Level51\SakeMore;

use SilverStripe\Control\HTTPRequest;

/**
 * Base class for each SakeMore command.
 *
 * Each command can be executed via the sake dev/more COMMAND_URL_SEGMENT route.
 *
 * @package Level51\SakeMore
 */
abstract class Command {
    /**
     * @var HTTPRequest
     */
    private $request;

    /**
     * @param HTTPRequest $request
     */
    public function setRequest($request) {
        $this->request = $request;
    }

    /**
     * @return HTTPRequest
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

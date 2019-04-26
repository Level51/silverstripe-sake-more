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
     *
     * @return string
     */
    abstract public function run();
}

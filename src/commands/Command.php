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
abstract class Command
{
    /**
     * @var HTTPRequest
     */
    private HTTPRequest $request;

    /**
     * @param HTTPRequest $request
     */
    public function setRequest(HTTPRequest $request): void
    {
        $this->request = $request;
    }

    /**
     * @return HTTPRequest
     */
    public function getRequest(): HTTPRequest
    {
        return $this->request;
    }

    /**
     * Get all request arguments.
     *
     * @param bool $withoutCurrent Whether the current active command should stay in the arguments list or not
     *
     * @return array
     */
    public function getAllArgs(bool $withoutCurrent = true): array
    {
        if (!$this->getRequest()) {
            return [];
        }

        $args = $this->getRequest()->getVars()['args'];

        if ($withoutCurrent) {
            array_shift($args);
        }

        return $args;
    }

    /**
     * Get only the request arguments not starting with -- (flag).
     *
     * @return array
     */
    public function getArgs(): array
    {
        $args = $this->getAllArgs();

        if (empty($args)) {
            return $args;
        }

        return array_filter($args, function ($arg) {
            return !str_contains($arg, '--');
        });
    }

    /**
     * Get all request argument flags (starting with --).
     *
     * @return array
     */
    public function getFlags(): array
    {
        $args = $this->getAllArgs();

        if (empty($args)) {
            return $args;
        }

        return array_filter($args, function ($arg) {
            return str_contains($arg, '--');
        });
    }

    /**
     * Check if given flag is set on the request.
     *
     * @param string $flag The flag to check, either in "--flag" format or just as "flag"
     *
     * @return bool
     */
    public function hasFlag(string $flag): bool
    {
        if (!str_starts_with($flag, '--')) {
            $flag = '--' . $flag;
        }

        return in_array($flag, $this->getFlags());
    }

    /**
     * Defines the url segment under which this command is callable.
     *
     * @return string
     */
    abstract public function getUrlSegment(): string;

    /**
     * Description of the functionality of this specific command.
     *
     * @return string
     */
    abstract public function getDescription(): string;

    /**
     * Defines the functionality of this command, this method is called on execution.
     */
    abstract public function run(): void;
}

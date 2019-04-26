<?php

namespace Level51\SakeMore;

/**
 * Abstract base class for each SakeMore command providing more than one action.
 *
 * @package Level51\SakeMore
 */
abstract class MultiCommand extends Command {

    /**
     * Get a list of available sub commands.
     *
     * Has to return a array where each entry has a description and an action (method name) to execute.
     *
     * @return array
     */
    abstract public function getSubCommands();

    /**
     * Implement the run action from the Command base class and delegate it to the subCommand handler.
     *
     * @throws SakeMoreException
     */
    public function run() {
        $this->runSubCommand();
    }

    /**
     * Run the sub command depending on the request.
     *
     * @throws SakeMoreException
     */
    public function runSubCommand() {
        $subCommand = $this->getSubCommand();
        $action = $subCommand['action'];
        $this->$action();
    }

    /**
     * Try to get the requested sub command.
     *
     * @return array
     * @throws SakeMoreException
     */
    public function getSubCommand() {
        $args = $this->getArgs();

        $requestedCommandName = '';
        if (!empty($args))
            $requestedCommandName = $args[0];

        $availableSubCommands = $this->getSubCommands();

        // Add showSubCommandsInfo as default action when called without sub-command argument
        if ($requestedCommandName === '' && !isset($availableSubCommands[$requestedCommandName]))
            $availableSubCommands[''] = [
                'action' => 'showSubCommandsInfo'
            ];

        // Check if it's an available command
        if (!isset($availableSubCommands[$requestedCommandName]))
            throw new SakeMoreException('The requested sub command "' . $requestedCommandName . '" is not available within "' . $this->getAllArgs(false)[0] . '"');

        // Get and validate the sub command config
        $subCommand = $availableSubCommands[$requestedCommandName];
        $this->validateSubCommand($subCommand, $requestedCommandName);

        return $subCommand;
    }

    /**
     * Validate if the given sub command is valid / executable.
     *
     * @param array  $subCommand           The sub command config
     * @param string $requestedCommandName The name of the sub command extracted from the request
     *
     * @throws SakeMoreException
     */
    private function validateSubCommand($subCommand, $requestedCommandName) {
        if (!is_array($subCommand))
            throw new SakeMoreException('Invalid sub command configuration for "' . $requestedCommandName . '"');

        // Check if the sub command defines an action
        if (!isset($subCommand['action']))
            throw new SakeMoreException('The requested command "' . $requestedCommandName . '" needs to define an action to execute');

        // Get the action name and check if the method actually exists
        $action = $subCommand['action'];
        if (!method_exists($this, $action))
            throw new SakeMoreException('The action "' . $action . '" is not defined on ' . get_called_class());
    }

    /**
     * Default sub-command which will be executed if the MultiCommand is called without additional arguments.
     */
    public function showSubCommandsInfo() {
        echo PHP_EOL . 'This command requires an additional sub-command. Available commands are:' . PHP_EOL;

        foreach ($this->getSubCommands() as $key => $config) {
            echo '    sake dev/more ' . $this->getAllArgs(false)[0] . ' ' . $key . ': ' . $config['description'] . PHP_EOL;
        }
    }
}

# SilverStripe SakeMore
Module for SilverStripe 4 providing additional command line tools.

Hooks into the default DevelopmentAdmin providing commands/tools under the `sake dev/more COMMAND_URL_SEGMENT` route.

## Commands
- without specific command: Show info/help including a list of all available commands
- **console**: Starts a interactive PHP shell using [PsySH](https://psysh.org/)
- **sql**: Connects to the local mysql client using the default connection details
- **snapshot**: Handles system snapshots by utilizing [SSPak](http://silverstripe.github.io/sspak/)
- ... more to come

### Extend with custom commands
Due to the modular setup adding custom commands is as easy as creating a new class extending the abstract `Level51\SakeMore\Command` class. Each sub class will automatically show up in the list of available commands.

It's also possible to extend the abstract `Level51\SakeMore\MultiCommand` class if your command provides more than one action. In that case the route will be like `sake dev/more YOUR_COMMAND YOUR_SUBCOMMAND`.

## Requirements
- SilverStripe ^4.0
- PHP >= 7.0
- [SSPak](http://silverstripe.github.io/sspak/) for the `snapshot` command

## Maintainer
- Level51 <hallo@lvl51.de>

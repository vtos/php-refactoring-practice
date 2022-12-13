### Requirements

- [Docker Engine](https://docs.docker.com/engine/installation/)
- [Docker Compose](https://docs.docker.com/compose/install/)
- Bash

### Usage

- Clone this repository and `cd` into it.
- Run `bin/install` to install composer dependencies (basically, PHPUnit)
- Run `bin/test` to execute the functional test
- Run `bin/run` to run the application itself to get calculation output (both HTML and JSON) to the console

### Notes

This branch contains the initial version of the application to be refactored. Some additions are:
- dockerizing the application
- adding a functional (a 'descriptive') test to fix the behaviour. The functional test is preserved during the entire refactoring process (see the [master branch](https://github.com/vtos/php-refactoring-practice))
- adding shell scripts to install/run/test the application quickly

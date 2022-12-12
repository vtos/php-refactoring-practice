### Requirements

- [Docker Engine](https://docs.docker.com/engine/installation/)
- [Docker Compose](https://docs.docker.com/compose/install/)
- Bash

### Usage

- Clone this repository and `cd` into it.
- Run `bin/install` to install composer dependencies (basically, PHPUnit) and build classes autoload
- Run `bin/test` to execute unit and functional tests
- Run `bin/run` to run the application itself to get calculation output (both HTML and JSON) to the console

### Notes

The repository contains [branch with legacy code](https://github.com/vtos/php-refactoring-practice/tree/legacy) which had initially been covered with a simple functional test to ensure the refactoring changes do not break its initial workings. The test has been being preserved during the entire process of refactoring.

ANY commit on the master branch keeps the package in working state. This matches the main requirement of the refactoring process - keep the refactored code up and running after each change.

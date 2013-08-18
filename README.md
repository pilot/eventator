eventator
=========

events managing with easiest ticketing systems

[![Build Status](https://travis-ci.org/pilot/eventator.png?branch=master)](https://travis-ci.org/pilot/eventator)

**install**

* download or clone repo `git clone https://github.com/pilot/eventator my_event/`
* create `app/cache` and `app/logs` directories with 777 permissions
* get composer `curl -S https://getcomposer.org/installer | php`
* install dependencies `php composer.phar install`
* create database `php app/console doctrine:database:create`
* create schema `php app/console doctrine:schema:create`
* install assets `php app/console assets:install`
* open browser and follow to the `http://your-events.loc/event/admin` for initial setup
* login and password to the backend **admin** / **admin**
* bingo!

**run tests**

* behat `./bin/beahat features`
* phpspec `./bin/phpspec`

**notes**

* alpha version hold just one event per installation

**todo**

* hold multiply events under the same panel
* support program with section
* add internal tickets seller / manager

**who use it**

* http://2013.symfonycamp.org.ua

**screenshot**

![Eventator](https://dl.dropboxusercontent.com/s/4c7m4bdf01467en/Eventator.png)

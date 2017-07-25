[![Stories in Ready](https://badge.waffle.io/pilot/eventator.png?label=ready&title=Ready)](https://waffle.io/pilot/eventator?utm_source=badge)
eventator
=========

events managing with easiest ticketing systems

[![CircleCI](https://circleci.com/gh/pilot/eventator.svg?style=shield&circle-token=3141cdc2b11468e7dc37985702d7419a8a4930f9)](https://circleci.com/gh/pilot/eventator)

## demo

[symfonycamp.org.ua](http://symfonycamp.org.ua)

* feel free to play with demo
* access to the admin panel by `/event/admin`
* with `admin/admin`

## install

* download or clone repo `git clone https://github.com/pilot/eventator my_event/`
* create `app/cache` and `app/logs` directories with 777 permissions
* get composer `curl -S https://getcomposer.org/installer | php`
* install dependencies `php composer.phar install`
* create database `php app/console doctrine:database:create`
* create schema `php app/console doctrine:schema:create`
* install assets `php app/console assets:install`
* open browser and follow to the `http://your-events.loc/event/admin` for initial setup
* login and password to the backend `admin` / `admin`
* bingo!

## run tests

* behat `./bin/beahat features`
* phpspec `./bin/phpspec`

## who use it

* http://symfonycamp.org.ua

## screenshot

![Eventator](https://dl.dropboxusercontent.com/s/4c7m4bdf01467en/Eventator.png)

## License

All what you can find at this repo shared under the MIT License

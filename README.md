[![Stories in Ready](https://badge.waffle.io/pilot/eventator.png?label=ready&title=Ready)](https://waffle.io/pilot/eventator?utm_source=badge)
eventator
=========

events managing with easiest ticketing systems

[![CircleCI](https://circleci.com/gh/pilot/eventator.svg?style=shield&circle-token=3141cdc2b11468e7dc37985702d7419a8a4930f9)](https://circleci.com/gh/pilot/eventator)

## demo

[demo.eventator.org](http://demo.eventator.org)

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
* http://botcamp.org.ua

*If you use Eventator platform please send email to `alex[at]lazy-ants.com`, I'll add you to the list*

## screenshots

![eventator_carousel speakers_list](https://user-images.githubusercontent.com/28564/30279791-d165b93c-9716-11e7-83f0-9337fde9e2b3.png)
![eventator_schedule_list about](https://user-images.githubusercontent.com/28564/30279792-d16d552a-9716-11e7-8760-95167aea669b.png)
![eventator_venue how_it_was](https://user-images.githubusercontent.com/28564/30279793-d1723d88-9716-11e7-9331-8288b4ec0125.png)
![eventator_sponsors organizers](https://user-images.githubusercontent.com/28564/30279796-d174b626-9716-11e7-96e0-ebe9d626acda.png)
![eventator_contact_us](https://user-images.githubusercontent.com/28564/30279794-d172fe4e-9716-11e7-8af4-fd3054899d72.png)

old view before 1.5 release

![Eventator](https://dl.dropboxusercontent.com/s/4c7m4bdf01467en/Eventator.png)



## License

All what you can find at this repo shared under the MIT License

## about filestack
As image uploader we use https://www.filestack.com/.
To use it You should visit https://dev.filestack.com/apps/ and create new app.
After that You should put your api_key to parameters.yml as filestack.api_key

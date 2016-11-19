#!/usr/bin/env bash

#composer clear-cache
composer install
#composer update
#--setup the db config: config/db.php
#--create the db: mysql -uroot -p -e 'create database sinoptik'
php yii migrate/up
php yii update/index http://bpteam.net/currency.zip
#--setup virtual host
#--run server
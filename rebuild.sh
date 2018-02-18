#!/bin/bash
chmod -R 777 app/cache/ app/logs/ &&
php app/console doctrine:schema:drop --force && php app/console doctrine:schema:create && php app/console doctrine:fixtures:load --append
rm -rf app/cache/* app/logs/*

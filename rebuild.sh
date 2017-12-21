#!/bin/bash
chmod -R 777 app/cache/ app/logs/ &&
/opt/cpanel/ea-php56/root/usr/bin/php app/console doctrine:schema:drop --force && /opt/cpanel/ea-php56/root/usr/bin/php app/console doctrine:schema:create && /opt/cpanel/ea-php56/root/usr/bin/php app/console doctrine:fixtures:load --append
rm -rf app/cache/* app/logs/*


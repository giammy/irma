#!/bin/bash

cd ..
rm -rf irma/app/cache/*
rsync -a --delete --exclude app/sqlite.db irma root@gea.noip.me:/var/www/html
ssh root@gea.noip.me "chown -R www-data:www-data /var/www/html/irma"
#
#app/console doctrine:schema:drop --full-database --force
#app/console doctrine:schema:update --force

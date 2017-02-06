#!/bin/bash

cd ..
rm -rf irma/app/cache/*
rsync -a --delete --exclude app/sqlite.db -e "ssh -p 443"  irma root@gea.noip.me:/var/www/html
ssh -p 443 root@gea.noip.me "chown -R www-data:www-data /var/www/html/irma"
#
# aggiorna Entities
#app/console doctrine:generate:entities AppBundle:Prestito o Utente
#app/console doctrine:schema:drop --full-database --force
#app/console doctrine:schema:update --force

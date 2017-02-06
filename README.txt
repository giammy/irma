
TODO

home - aggiungere logout



Progetto IRMA

*** 20170202 

mirko.romanato@posteo.net

DOMANDE:
- nella tabella prestito serve un campo utente (l'utente che ha preso il prestito)?
- quanti utenti ci sono (come ordine di grandezza?) 200
- quanti prestiti ci sono in corso (sempre indicativamente?) (~30)


Specifiche 

Tabella Prestiti: 
integer     id
string255   protocollo                # Numero di protocollo
string255   titolo1                   # Titolo libro
string255   collocazione              # Collocazione 
string255   titolo2                   # Titolo libro II
datetime    dataPrestito              # data prestito
datetime    dataRestituzione          # data restituzione - null indica NON RESTITUITO
string4096  richiestaProroga          # richiesta proroga (testo) 
string255   bibliotecarioPrestito     # bibliotecario che ha effettuato prestito 
string255   bibliotecarioRestituzione # bibliotecario che ha effettuato restituzione
string4096  note                      # note
string      utente                    # DOMANDA: NON CI VA UN CAMPO CON IL NOME DELL"UTENTE CHE HA PRESO IL PRESTITO?


Tabella Utenti:
integer     id
string255   username       # creato in automatico nome.cognome, se non unico, nome.cognome1
string255   ruolo          # utente (uso futuro?)
string255   nome           # Nome
string255   cognome        # Cognome
string255   residenza      # Residenza
string255   cellulare      # cellulare
string255   email          # email 
bool        consenso       # ha dato consenso a trattamento dati personali
datetime    dataiscrizione # data in cui l'utente e' stato registrato
string255   bibliotecario  # bibliotecario che l'ha iscritto
string255   tipoDocumento  # tipo di documento fornito tipo Carta Identità/Tesserino Unipd
string255   emessoDa        #
string255   numeroDocumento # e suo codice, CI22424 / 21313421LT


Pagine:

Logica dell'applicazione:
il bibliotecario entra autenticandosi (registrazione dei bibliotecari fatta a mano)
e si trova nella homepage.

Qui ci sono 2 grandi bottone "NUOVO PRESTITO" e "RESTITUZIONE PRESTITO"

NUOVO PRESTITO
- Premendo NUOVO PRESTITO compare la lista degli utenti registrati,
  il bibliotecario cerca l'utente o lo crea se non c'e'
- clicca sul nome dell'utente trovato.
- Compare la scheda utente che ha un bottone "NUOVO PRESTITO"
- Preme il bottone e compare la scheda del prestito da compilare
- SALVA

RESTITUZIONE PRESTITO
- compare una pagina con la lista dei prestiti in corso
- il bibliotecario cerca il prestito in questione
- clicca e compare la scheda del prestito
- qui c'e' un bottone "PROLUNGA PRESTITO" e un bottone "RESTITUISCI PRESTITO"


Schermate:

/
homepage, con link per
- NUOVO PRESTITO
- PROLUNGA PRESTITO
- crea nuovo utente
- lista utenti
- lista utenti con materiale in prestito
- lista prestiti da restituire

/edit/prestito/nuovo
/edit/prestito/id
/edit/utente/nuovo
/edit/utente/username
ciascuna di queste pagine serve a creare o editare prestito o utente

/mostra/prestiti/tutti
/mostra/prestiti/nonrestituiti
/mostra/prestiti/id

/mostra/utenti/tutti
/mostra/utenti/conprestiti
/mostra/utenti/username


Esportare /vedi/prestiti/id e /vedi/prestiti/tutti in CSV

Installare su Apache - file di configurazione /etc/apache2/apache2.conf

<VirtualHost *:80>
    ServerName gea.noip.me
    ServerAlias www.gea.noip.me

    DocumentRoot /var/www/html

    Alias /irma "/var/www/html/irma/web"
    <Directory /var/www/html/irma/web>
        AllowOverride All
        Order Allow,Deny
        Allow from All

        <IfModule mod_rewrite.c>
            Options -MultiViews
	    RewriteEngine On
	    RewriteCond %{REQUEST_FILENAME} !-f
	    RewriteRule ^(.*)$ app.php [QSA,L]
        </IfModule>
    </Directory>
</VirtualHost>


Installazione
Apache
Php
symfony
apt-get install php-xml sqlite
sudo apt-get install php7.0-sqlite3



inizializzazione Repository:
git init
git add README.md
git commit -m "first commit"
git remote add origin https://github.com/giammy/irma.git
git push -u origin master

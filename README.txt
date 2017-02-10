
Progetto IRMA
https://github.com/giammy/irma


TODO

- gestire logout???
- schermata utente - bottone mostra tutti i prestiti dell'utente

Manuale

Vi sono 2 schermate
- homepage
- schermata "stat" (accessibile al link in basso "stat" presente nella homepage)

Schermata homepage
L'utente bibliotecario di solito fa queste 2 azioni
- bottone NUOVO PRESTITO
- bottone RASITUISCI/RINNOVA
Nella pagina e' presente un link poco visibile (footer in basso, "stat)
- link a pagina "Statistiche e utilita'"

Pagina NUOVO PRESTITO
Compare la lista degli utenti, il bibliotecario cerca l'utente che vuole il prestito
scorrendo la pagina o con il "CERCA" in cima alla tabella a destra.
Se l'utente c'e', preme il bottone presta e va nella pagina prestito

Se l'utente non c'e' preme "CREA NUOVO UTENTE", compila i dati dell'utente, li salva
e si ritrova nella pagina precedente.

Nella pagina prestito, il bibliotecario compila i dati del prestito e preme SALVA

A questo punto si trova nella pagina dei prestiti in corso.

Se nella homepage preme il tasto RINNOVA O RESTITUISCI, va nella
pagina dei prestiti in corso, qui per ciascun prestito puo' premere il bottone RINNOVA o RESTITUISCI
In questa pagina:
- se preme sul nome del protocollo, entra nella scheda prestito e la puo' cambiare (tipo aggiungere note)
- se preme sul nome dell'utente entra nella scheda dell'utente e la puo' cambiare


Nella pagina stat
- lista utenti: mostra la lista degli utenti e c'e' il bottone per esportare in CSV
- lista prestiti: mostra la lista di tutti i prestiti e c'e' il bottone per esportare in CSV











*** Software gestione prestiti
- circa 200 utenti
- circa 30 prestiti in corso

*** Struttura dati

Tabella Prestiti: 
integer     id
string255   protocollo                # Numero di protocollo
string255   titolo1                   # Titolo libro
string255   collocazione              # Collocazione FORZARE FORMATO "DON.F.924"
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


*** Struttura pagine:

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


*** Pagine:

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


*** Installazione

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


Pacchetti necessari (alcuni):
apache
php
symfony
sudo apt-get install php-xml sqlite php7.0-sqlite3


inizializzazione Repository:
git init
git add README.md
git commit -m "first commit"
git remote add origin https://github.com/giammy/irma.git
git push -u origin master

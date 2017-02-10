
Progetto IRMA
https://github.com/giammy/irma


TODO

- gestire logout???
- schermata utente - bottone mostra tutti i prestiti dell'utente

Manuale

*** SCHERMATA HOMEPAGE
L'utente bibliotecario ha 2 bottoni grandi con le operazioni di uso comune
- bottone NUOVO PRESTITO
- bottone RESITUISCI/RINNOVA
Nella pagina e' inoltre presente un link (footer in basso a destra "stat")
- link a pagina "Statistiche e utilita'" (di solito non servono queste opzioni)

*** SCHERMATA d'arrivo dal bottone NUOVO PRESTITO
Compare la lista degli utenti, il bibliotecario cerca l'utente che vuole 
il prestito scorrendo la pagina o con il "CERCA" in cima alla tabella a destra.
Se l'utente non c'e' preme "CREA NUOVO UTENTE", compila i dati dell'utente, 
li salva e si ritrova nella pagina della lista utenti come prima..
Ora trova l'utente, preme il bottone PRESTA e va nella SCHERMATA PRESTITO

*** SCHERMATA PRESTITO
Nella pagina prestito, il bibliotecario compila i dati del prestito e preme SALVA
A questo punto si trova nella SCHERMATA DEI PRESTITI IN CORSO.

Se nella homepage preme il tasto RINNOVA O RESTITUISCI, va nella
SCHERMATA DEI PRESTITI IN CORSO

*** SCHERMATA DEI PRESTITI IN CORSO
Nella schermata dei prestiti in corso, per ciascun prestito puo' premere il bottone 
RINNOVA o RESTITUISCI che registrano nel database l'informazione.
In questa pagina si puo' anche
- cliccare sul nome del protocollo, si entra nella scheda prestito e la puo' cambiare (tipo aggiungere note)
- cliccare sul nome dell'utente, si entra nella scheda dell'utente e la puo' cambiare

*** SCHERMATA STAT 
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

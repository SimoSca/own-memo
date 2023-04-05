Data Storage
============

per lo storage dei dati ho scelto d'implementare un semplice database `MySql`, 
sviluppando una serie di api tramite la classe `PasswordStorage` che utilizza il trait `BotConnector` per ottenere un'istanza della classe PDO, generalmente abilitata di default per le versioni PHP piu recenti.

L'utilizzo di una classe contenente api e' stato scelto per poter incapsulare tutte le api relative a un comando specifico (ad esempio creando `BarzellettaStorage` per la gestione di una tabella contenente le barzellette).



Start
------

Il database e' `my-site_simone_scardoni`, l'utente a cui sono concessi i pieni permessi di lettura/scrittura e' `my-site` con password `123stella`.
Questi parametri sono proprieta' private di `BotConnector`.

Riporto di seguito i comandi da eseguire per rendere operativo questo test, sperando di fare cosa gradita.


#### MySql

dopo aver effettuato su `mysql` il login come root, lanciare in ordine 

    create database my-site_simone_scardoni;

    use my-site_simone_scardoni;

    GRANT ALL ON my-site_simone_scardoni.* TO 'my-site'@'localhost' IDENTIFIED BY '123stella';


La creazione delle tabelle viene poi gestita mediante il metodo `controllaDB` della classe `PasswordStorage`. 

#### Composer

composer viene semplicemente utilizzato come autoload psr-4.

L'unico comando da eseguire e'

    composer install


Extra
-----

Sottolineo che una gestione piu ponderata consisterebbe nella creazione di uno script volto a eseguire i comandi sopra elencati.
In questo script di inizializzazione del progetto sarebbe poi utile inserire anche i comandi relativi alla creazione delle tabelle, 
evitando cosi' l'impiego del metodo `controllaDB` della classe `PasswordStorage`.


Tabelle
-------

Al fine della prova ho ritenuto sufficiente l'impiego di due tabelle:


#### user

contenente i campi

- `id`, chiave primaria
- `NOME`, il nome del cliente
- `UTENTE`, lo user dell'utente

Per semplificare l'esercizio ho unito `NOME` e `UTENTE` supponendo che un utente possa "appartenere" a un solo cliente. 
Se cosi' non fosse, sarebbe necessario sviluppare una relazione `molti a molti` tra `NOME` e `UTENTI`, implementando quindi ulteriori tabelle.


#### user_service

Supponendo che la coppia `NOME` - `UTENTE` basti ad identificare univocamente un utente, sfruttero' soltanto il campo `id` della tabella `user`, salvandolo come `user_id`, che sara' quindi una chiave esterna per la tabella `user_service`,
che a questo punto risulta avere i campi

- `id`, chiave primaria
- `user_id`, chiave esterna di hook alla tabella `user` (relazione uno a molti)
- `servizio`, tipologia del servizio (ftp, ssh, ws, etc.)
- `password`, password associata all'utente sul servizio specifico



index.html
----------

Dato che nella consegna e' stato chiesto di non toccare i files mi sono trattenuto dal modificare `index.html`,
pur tuttavia molto tentato nel voler rendere giustizia al bottone `Invia`, per il quale non e' stato l' Handler jQuery all'evento `click`.


FINALE
------

Ogni script contiene una serie di commenti che spero possano agevolarne la comprensione.
Sperando che questo lavoro venga apprezzato, auguro una buona lettura!

Grazie per l'attenzione

Simone Scardoni
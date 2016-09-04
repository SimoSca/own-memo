Module Enomis
=============

module with `gulpfile` to enable browser autoreload, and watch to:

- test


To work with global `nodejs` plugins installation, must create the `links` via

````
npm link gulp
npm link browser-sync
````

### TODO

controllare `gulp watch symlinks`

#### Refrence

starting video [Joomla 3.0 Development for Beginners](https://www.youtube.com/watch?v=npx8QR-mn6Y)


PHPUnit
=======

data la gestione con `composer` in `PSR4` utilizzero' il suo autoload, 
di conseguenza un comando tipo per runnare un singolo script:
 
````
phpunit --bootstrap vendor\autoload.php test\libs\ETestTest.php
````


Files
======

la nomenclatura non e' particolarmente vincolante,
ma e' bene ricordare che l'unico nome veramente importante
e' quello del file `.xml` che deve coincidere con quello della directory stessa, ovvero

````
<directory>.xml
````

in questo modo joomla e' in grado di trovare il file.

> anche il file `.php` di bootstrap deve seguire il medesimo schema!

JOOMLA
=======

per fare in modo che joomla possa caricare il modulo, dopo aver creato i primi files devo:

 1. andare nel pannello d'amministratore
 2. `extensions > discover`
 3. la seleziono e la installo
 
 se tutto e' andato bene devo fare attenzione, perche' attualmente non ho ancora fatto in modo che il modulo venga visualizzato.
 
 Per **visualizzarlo** devo ancora settare le sue impostazioni aggiungendolo ai moduli:
 
 1. `Modules`
 2. in questa pagina `New`
 3. cerco il mio modulo ( il `name` impostato nel file `.xml`) 
 4. lo seleziono e poi imposto i parametri dovuti
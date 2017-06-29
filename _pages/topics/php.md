---
title:      Php
permalink:  /topics/php/
---

### PHP FPM

- [https://serversforhackers.com/video/php-fpm-process-management](https://serversforhackers.com/video/php-fpm-process-management)


### Service Container

- [https://jtreminio.com/2012/10/an-introduction-to-pimple-and-service-containers/](https://jtreminio.com/2012/10/an-introduction-to-pimple-and-service-containers/)

### Task Manager

interessante : [http://robo.li/](http://robo.li/), scoperto grazie a questo articolo:

[look-ma-no-nodejs-a-php-front-end-workflow-without-node/](https://www.sitepoint.com/look-ma-no-nodejs-a-php-front-end-workflow-without-node/)


### PHP e NODE server

interessantissimo video che mostra come importare un socket server in una pagina php, quindi usando Apache come normale server,
che puo' mandare messaggi di broadcast o implementare varie opzioni:

[Nodejs withPhp Socket Prgramlama](https://www.youtube.com/watch?v=xIKRwRU9UTs)

In ogni caso di fatto ho sempre due server: quello apache e quello del socket, pertanto devo attivare sia il primo (con lamp ad esempio)
sia il secondo (`node service.js`).


### Virtual Machine

per separare nettamente il filesystem del mio Desktop dal server, guadagnando cosi' in sicurezza, puo'
essere utile usare una `VM`, e per rendere tutto piu semplice meglio trovare una con gia' predisposti:
- `apache`
- `phpunit`
- `xdebug`
- `composer`
per poter avere l'ambiente di base gia' pronto. A tal fine risulta utile utilizzare `Vagrant`,
e usare un opportuno strumenti di `provisioning` (ad esempio `Chef`), ogni volta che su di essa voglio aggiungere qualcosa,
come ad esempio `nodejs`. Naturalmente tenerne traccia con `Git` e' di base!

Io ho provato questo:

[https://github.com/mattandersen/vagrant-lamp](https://github.com/mattandersen/vagrant-lamp)

ma ve ne sono altri interessanti come [questo box](https://vagrantcloud.com/jitb/boxes/box).


### Composer


#### TOKEN


token to access libraries, i.e. composer when attempt to add `MyRepo\Libraries` and ask for token:

````
da550a914d8840da6f7fdbef1a.....
````

aggiungerlo localmente mediante (genera il file `auth.json` nella directory da cui lancio il comando):
````
composer config github-oauth.github.com da550a914d8840da6f7fdbef1a.....
````

o globalmente:
````
composer config -g github-oauth.github.com da550a914d8840da6f7fdbef1a.....
````


### XDebug

e' un plugin Apache-Php utilissimo per gestire l'ambiente di lavoro.

Esso provvede in particolare a:

- `debug` , mediante breakpoint
- `profiling` , ovvero il poter scrivere un resoconto completo del ciclo di vita dello script PHP eseguito (come statistiche sui tempi di ogni singolo metodo).


La cosa interessante e' che non semplicemente e' un'estensione, ma e' un servizio vero e proprio
che apre un suo personale server e si mette in ascolto su una specifica porta (tipicamente `localhost:9000`);
questo fatto rende possibile anche debuggare in **remoto** e poter fare in modo che piu computer (o meglio client)
possano svolgere debug sullo stesso sito, se necessario.

Internet e' pieno di tutorial su come abilitarlo e utilizzarlo, pertanto mi limito a inserire quelli interessanti:

- [xdebug-and-you-why-you-should-be-using-a-real-debugger/](https://jtreminio.com/2012/07/xdebug-and-you-why-you-should-be-using-a-real-debugger/) , mi piace lo stile dell'autore
- [debugging-and-profiling-php-with-xdebug/](https://www.sitepoint.com/debugging-and-profiling-php-with-xdebug/) , mi piace la profilazione con `KCachegrind
- [debugging-php-applications-with-xdebug/](https://devzone.zend.com/1147/debugging-php-applications-with-xdebug/) , spiega bene il workflow, anche da remoto
- [remote-debugging-php-with-vim-and-xdebug/](https://ccpalettes.wordpress.com/2013/06/03/remote-debugging-php-with-vim-and-xdebug/) , per sfizio
- [xdebug-professional-php-debugging](http://code.tutsplus.com/tutorials/xdebug-professional-php-debugging--net-34396)
- [netbeans-waiting-for-connection-netbeans-xdebug-issue](http://www.devside.net/wamp-server/netbeans-waiting-for-connection-netbeans-xdebug-issue), in caso di problemi con `Netbeans`


La cosa interessante che sottolineo e che non mi faceva capire perche' a volte funzionava e a volte no,
e' il fatto che essendo un server, allora deve esserci un client: il mio IDE!!!


**WorkFlow**

Sostanzialmente mi chiedo: come funzia? (tratto da [qui](https://devzone.zend.com/1147/debugging-php-applications-with-xdebug/) )

- devo fare in modo che server (xdebug) e client (il mio ide) si connettano e dialoghino tra loro sulla porta (9000):

	PENSO: sostanzialmente si scambiano informazioni :
	- il client, manda al server comandi del tipo `Play` o `Stop` o `Next` ogni volta che io utente interagisco col client
	ovvero col mio ide; di conseguenza il server decide se finire lo script, oppure andare avanti di passo in passo
	- il server, manda al client informazioni sullo stato attuale dello script, come variabili d'ambiente e track;
	di conseguenza il client e' in grado di aggiornare la GUI

- ogni volta che carico la pagina del browser, xdebug deve sapere se deve agire o meno, ed inoltre deve anche sapere con che client comunicare!!!

Pertanto il lavoro dovrebbe essere:

1. xdebug server in listen on port 9000
2. attivare xdebug sul client (IDE): questi aprira' una connessione `DBGp`, in questo modo essi potranno dialogare tra di loro;
in questa fase si scambiano una `key` che funzia da id.
>NB forse a discrezione dell'IDE, la connessione client-server :9000 non viene aperta immediatamente, ma al primo avvio del browser.

L'ultimo punto riguarda il fire di xdebug, ovvero: quando faccio una richiesta al mio server HTTP con PHP sul quale ho attivato XDEBUG,
come fa il server HTTP a capire che l'esecuzione dello script PHP deve essere wrappata da XDEBUG?
inoltre come fa XDEBUG a capire con quale client deve scambiare informazioni/comandi relativamente a quello script?

Risposta: la richiesta deve contenere un cookie relativo a xdebug `xdebug_session_start`(cosi' il server sa di dover far entrare in causa xdebug),
ed inoltre il valore di quel cookie `key` del client: cosi' relativamente a quella tab(ovvero a quello script) il server sa con chi dialogare,
in quanto la `key` e' l'identificativo univoco del client col quale si era gia' connesso (vedi punto  **(2)** qui sopra).
> qui ho parlato di cookie, ma potrebbe essere una `GET` o un `POST`

Per realizzare questo vi sono vari modi:

- estensione del browser
- far partire una sessione di debug tramite richiesta `GET` , `POST` o `COOKIE` con `XDEBUG_SESSION_START`
- tramite browser [phpstorm-marklets](https://www.jetbrains.com/phpstorm/marklets/), con possibilita' di salvare i link salavati in una cartella bookmarks, denomiata ad esempio `XDEBUG-SublimeText`

**Considerazione**

il workflow si basa sostanzialmente su due connessioni indipendenti:

- client-ide vs server-xdebug , sulla porta 9000
- client-browser vs server-http, come normale connessione: questa appoggiata da parametri per `xdebug_session_start=CLIENT-KEY`

essendo queste indipendenti, puo' avvenire ad esempio che decida di chiudere il browser mentre sto debuggando:
se lo chiudo o svolgo il refresh eliminando il parametro per triggerare xdebug, allora il server risponde al browser con una normale richiesta,
ma... la cosa interessante e' che non avendo fatto nulla sull'ide, allora quest'ultimo sta continuando a debuggare mediante la connessione xdebug-server:9000 !!!

Perttanto in questo caso il debug procede, pur non avendo riscontro grafico!!!


**Check XDebug**

per testare che effettivamente la porta su cui xdebug agisce sia aperta, posso usare i comandi

````
netstat -o -n -a | findstr 127.0.0.1:9000
````
oppure
````
netstat -b
````
se voglio vedere tutti i processi in listen.






THEORY
======

### Dependency Injection & Inverion Of Control

example at [http://krasimirtsonev.com/blog/article/Dependency-Injection-in-PHP-example-how-to-DI-create-your-own-dependency-injection-container](http://krasimirtsonev.com/blog/article/Dependency-Injection-in-PHP-example-how-to-DI-create-your-own-dependency-injection-container)


### MVC

example at [https://www.sitepoint.com/the-mvc-pattern-and-php-1/](https://www.sitepoint.com/the-mvc-pattern-and-php-1/)



### Compilre and OpCache

See 

- [https://github.com/engineyard/ey-php-performance-tools](https://github.com/engineyard/ey-php-performance-tools)
- [https://support.cloud.engineyard.com/hc/en-us/articles/205411888-PHP-Performance-I-Everything-You-Need-to-Know-About-OpCode-Caches](https://support.cloud.engineyard.com/hc/en-us/articles/205411888-PHP-Performance-I-Everything-You-Need-to-Know-About-OpCode-Caches)
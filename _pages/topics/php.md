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


### Libraries

- [Laravel Dusk](https://laravel.com/docs/5.8/dusk), per frontend test (browser) ed eventualmente php pipelines

- [Php Meminfo](https://github.com/BitOne/php-meminfo), per analisi dell'utilizzo di memoria di classi e e porzioni PHP

- [Aws Lambda + Php: Bref](https://bref.sh/docs/), come integrare lambda con php: documentazione ben fatta

- [Rector](https://github.com/rectorphp/rector), per svolgere refactor di classi in interi progetti, ad esempio modifiche di Namespaces, nomi di metodi e altro ancora


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


### TIPS & TRICKS

- [Hunting down memory leaks with PHP Meminfo](https://www.youtube.com/watch?v=NjIlKlFImlo)

- [Tool to analyze memory leaks](https://github.com/BitOne/php-meminfo)


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


### CODE

#### Flock

lectures:

- [https://stackoverflow.com/questions/24425247/difference-between-return-value-of-non-blocking-flock-function-and-the-wouldblo](https://stackoverflow.com/questions/24425247/difference-between-return-value-of-non-blocking-flock-function-and-the-wouldblo), to understand the special `wouldblock` parameter
- [https://stackoverflow.com/questions/19757239/when-is-a-special-lock-file-or-opening-file-in-c-mode-necessary-with-php-flock](https://stackoverflow.com/questions/19757239/when-is-a-special-lock-file-or-opening-file-in-c-mode-necessary-with-php-flock), to understand special `c` __mode__





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



XDEBUG-DBGPPROXY
================


Alcune casistiche interessanti per un ambiente `Dockerizzato`.

Suppongo di:
 
- avere un container `dbgpproxy` con un server `openssh`, cosi' da poter utilizzare le sue porte mediante tunnel ssh

- che `dbgpproxy` sia eseguito su un server a cui ho accesso `ssh`

- che `dbgpproxy` mappi la porta host `1025` nella ssh `22` e che esponga almeno la porta `9000` per essere raggiunta da xdebug (vedi configurazione sotto),
    e la porta `9001` per essere raggiunta dal proprio client (`telnet` o `ide`) al momento in cui dovra' registrare la `idekey`.


Anzitutto la configurazione `xdebug` sul server sara' della forma:

````apacheconfig
;remote

xdebug.remote_enable=1
xdebug.remote_handler=dbgp
xdebug.remote_port=9000
xdebug.remote_host=dbgpproxy
xdebug.remote_log="/var/log/xdebug.log"
````

Dove devo essere sicuro che `dgbpproxy` sia effettivamente raggiungibile dal network del container (eventualmente come `external_link`).
Altra cosa: il `remote_log` a seconda del path specificato potrebbe non avere i dovuti permessi e quindi non generarsi: 
in tal caso generarlo a mano e dare permess `666` o piu' raffinati se necessario; tieni presente che questo log serve solo per debug 
in caso tu non riesca a connetterti.


Ricapitolando, per funzionare `dbgpproxy` e la connessione del proprio pc abbiamo in gioco tre porte:

- porta `9000`, che viene contattata dal server (container) che esegue `PHP` e sul quale e' abilitato `xdebug`

- porta `9001`, che serve per registrare in `dbgpproxy` una coppia `idekey - porta:client` (ad es. `9005`), 
    dove `porta:client` e' la porta che impostiamo nel nostro pc per rimanere in ascolto di `dgbpproxy`
    
- `porta:client` (ad es. `9005`), che e' la porta configurata sul nostro ide e sulla quale si mette in ascolto per ricevere connessioni da `dgbpproxy`,
    che appunto `proxa` le remote calls di `xdebug` eseguito sul server php
    
Fatto questo recap, ora passiamo ai vari tunnel da fare:

- `ssh -L 1025:localhost:1025 remoteuser@dockerhost -N`: visto che suppongo di non avere aperto al mondo il container `dbgpproxy`,
    allora faccio un tunnel per mappare la mia porta locale 1025 nella porta 1025 del server remoto, che via docker mappa la sua 1025 nella 22 (ssh) di dgbpproxy
    
- `ssh -L 9001:localhost:9001 -p 1025 dbgpuser@127.0.0.1 -N -v -v`: ora mappo la porta locale `9001` nella porta `9001` del container `dbgpproxy`
    (che raggiungo con l'utenza `dbgpuser` alla porta locale `1025` del `127.0.0.1`, perche' cosi' avevo mappato al comando precedente)
    
- `ssh -R 9005:localhost:9005 -p 1025 dbgpuser@127.0.0.1 -N`: ora un altra mappatura, ma questa mi servira' per fare in modo che il `dgbpproxy` 
    possa contattare il client locale (mio pc) alla porta locale `9005`
    
Ora supponendo di aver impostato nell'estenzione `xdebug` del mio browser la `idekey` `cicciopasticcio`,
devo registrarmi a `dgbpproxy` per fargli capire che sono io l'utente interessato alle connessioni `xdebug` con quella chiave.
Per meglio capire, il flusso e':

1. il browser contatta il server php in cui ho abilitato xdebug, dicendogli _"ciao, attiva xdebug con questa idekey che ti sto passando"_

2. il server php `xdebug` a questo punto contatta il server `dbgpproxy` alla porta `9000` dicendogli _"mi sono attivato perche' ho ricevuto una richiesta con questa `idekey`"_

3. il server `dgbpproxy` controlla i client registrati per vedere se qualcuno matcha la `idekey` (al + uno), ed in tal caso prova a contattarlo alla porta che ha registrato (es `9005`)

4. se il mio client matcha la `idekey`, allora ricevo una connessione da `dbgpproxy` e finalmente il mio ide mi fa vedere il flow di xdebug.

La cosa interessante e' che si possono registrare piu' `idekey` sulla stessa porta, quindi uno svliuppatore puo' monitorare contemporaneamente piu' progetti,
a patto di personalizzare la `idekey`

> NOTA: questo comportamento dipende dall'ide, ad esempio `PHPStorm` puo' utilizzare direttamente i servername (DNS) per indirizzare `xdebug` sul giusto progetto.

Quindi per registrare il proprio client:

- `telnet 127.0.0.1 9001` (quindi contatto e accedo al server `dgbpproxy`)

- `proxyinit -p 9005 -k cicciopasticcio -m 1` (quindi dgbpproxy mi deve contattare alla porta `9005` quando riceve connessioni di `xdebug` triggerate da un browser con idekey `cicciopasticcio`)

> con `proxystop -k cicciopasticcio` invece mi de-registrero'

> dopo questo comando, posso anche stoppare il tunner ssh `ssh -L 9001:localhost:9001 -p 1025 dbgpuser@127.0.0.1 -N -v -v`

Ora basta attivare il listener di `xdebug` sul proprio ide e il gioco e' fatto!

Ad esempio con `Visual Studio Code` si puo' utilizzare un file di configurazione cosi':

````json5
{
  // proxyinit -p 20000 -k vscdebug -m 1
  // proxystop -k vscdebug
  "version": "0.2.0",
  "configurations": [
    {
      "name": "Listen for XDebug",
      "type": "php",
      "request": "launch",
      "port": 9005,
      // server -> local
      "pathMappings": {
        "/var/www": "${workspaceRoot}/<pat to project>",
      }
    }
  ]
}
````

Per dialogo con server `dbgpproxy` posso usare:

````bash
### interattivo, con telnet
telnet dbgpproxy 9001
proxyinit -p 9005 -k cicciopasticcio -m 1

### One host (sono tutti alternativi)

# funziona, ma non mi da feedback
echo "proxyinit -p 9005 -k cicciopasticcio -m 1" > /dev/tcp/dbgpproxy/9001
# mi restituisce anche una risposta
echo "proxyinit -p 9005 -k cicciopasticcio -m 1" | netcat dbgpproxy 9001

# unregister 
echo "proxystop -k cicciopasticcio" > /dev/tcp/dbgpproxy/9001
````

 
Attenzione al locale!!! Perche' DBGPPROXY contatta il server, e devo stare attento a come configuro gli host!


### Faccio tutto dal mio PC

#### 1 - mappo la dbgpproy:22 (vista dal remoto maintainer: dal suo network dbgpproxy e' la 22)del remoto nella locale 1027

````
ssh -L 1027:dbgpproxy:22 staging-server-01-remoteuser -N
````

quindi ora posso considerare il dbgpproxy:22 come la localhost:1027, e di conseguenza effettuare tunnel

````
Host staging-server-01-dbgpproxy22local1027
    User remoteuser
    Port 1024
    IdentityFile ~/Working/wlogo/Mixed/ssh/remoteuser-staging-server-01
    LocalForward 1027 dbgpproxy:22
````

cosi' mi basta lanciare al posto del comando ti cui sopra (piu' comodo) 

````
ssh staging-server-01-dbgpproxy22local1027 -N
````

> NOTA: lo stesso varra' per i comandi successivi

#### 2 - dbgpproxy registrera' il client come localhost:9007, 

quindi lo contattera' li, e io lo mappo nella locale 9005 (staro' in ascolto col mio client/ide)

````
ssh -R 9007:localhost:9005 -i aws-staging -p 1027 dbgpUser@localhost -N 
````

````
Host staging-server-01-dbgpproxy9007local9005
    HostName localhost
    User dbgpUser
    Port 1027
    IdentityFile <path to identity file>
    RemoteForward 9007 localhost:9005
````

#### 3 - ora registro il mio client con la mia key

````
ssh -L 9003:localhost:9001 -i aws-staging -p 1027 dbgpUser@localhost -N
````

````
Host staging-server-01-dbgpproxy9001local9003
    HostName localhost
    User dbgpUser
    Port 1027
    IdentityFile <path to identity file>
    LocalForward 9003 localhost:9001
#echo "proxyinit -p 9007 -k cicciopasticcio -m 1" > /dev/tcp/localhost/9003
#in dbgpproxy vedo: Server:onConnect ('127.0.0.1', 40900) [proxyinit -p 9007 -k cicciopasticcio -m 1
````

Dopo il comando posso chiudere questa connessione, perche' tanto devo registrare solo una volta.


**Usando il server che esegue docker:**

Posso rifare  tutto uguale ma pensando di usare il tunnel tramite l'host su cui gira docker, e non da un container nello stesso network.
Se ad esempio il container mappa `0000:1025 -> 22`, allora posso modificare il primo tunnel ssh:
`ssh -L 1027:localhost:1025 staging-server-01 -N`.


#### Alternativa al mapping locale


Tutto questo risulta piuttosto tedioso, quindi rivediamolo in una chiave piu' comoda con **ProxyCommand**,
che mi consente di utilizzare direttamente un tunnel con un server intermedio (proxy, o bastion), 
vedi [qui](https://www.cyberciti.biz/faq/linux-unix-ssh-proxycommand-passing-through-one-host-gateway-server/) 
(nota che io uso l'opzione `-W`, presente nelle versioni piu' moderne di `OpenSSH`).

````
# Creo un tunnel per accedere al dbgpproxy:22, 
# usando il staging-server-01-remoteuser come proxy (intermediario, bastion)
Host staging-server-01-dbgpproxy
    HostName dbgpproxy
    User dbgpUser
    Port 22
    IdentityFile <path to identity file>
    ProxyCommand ssh staging-server-01-remoteuser -W [%h]:%p
````

In questo modo con `ssh staging-server-01-dbgpproxy` e' come se facessi connessioni dirette al server `dbgpproxy`,
senza interessarmi del server intermedio (`staging`, o container maintainer in questo caso).

Grazie a questo ad esempio non ho bisogno della mappatura intermedia e temporanea `9003 localhost:9001 ...`, 
ma posso comunicare con `dbgpproxy` con comandi diretti, ad esempio:

````
ssh staging-server-01-dbgpproxy 'echo "proxyinit -p 9007 -k cicciopasticcio -m 1" > /dev/tcp/localhost/9001'
````

Quindi ho diminuito la complessita'!

Ora mi rimane solamente l'apertura remota:

````
ssh -R 9007:localhost:9005 staging-server-01-dbgpproxy
````

o piu' semplice con `.ssh/config`:

````
# Creo un tunnel per accedere al dbgpproxy:22, 
# usando il staging-server-01-remoteuser come proxy (intermediario, bastion)
Host staging-server-01-dbgpproxy*
    HostName dbgpproxy
    User dbgpUser
    Port 22
    IdentityFile <path to identity file>
    ProxyCommand ssh staging-server-01-remoteuser -W [%h]:%p

Host staging-server-01-dbgpproxy-listen9005
    RemoteForward 9007 localhost:900
````

Nota che ho ridefinito il `staging-server-01-dbgpproxy*` aggiungendo l'asterisco in modo da riciclare quando gia' configurato 
per il `ProxyCommand`.

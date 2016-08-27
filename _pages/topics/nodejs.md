---
title:      NodeJs
permalink:  /topics/nodejs/
---

Per la ruggine, consiglio di leggere l'articolo

[https://www.airpair.com/node.js/posts/top-10-mistakes-node-developers-make](https://www.airpair.com/node.js/posts/top-10-mistakes-node-developers-make).

Tutorial step x step

- [http://www.nodelabs.org](http://www.nodelabs.org)

Free Host Nodejs

- [cloudno](https://cloudno.de/)



Nozioni Specifiche
------------------

### export vs module.exports

Sostanzialmente quando carico una pagina javascript (`require`) in `Nodejs` questa pagina viene considerata come modulo e implicitamente viene eseguito il comando:

````
var exports = module.exports = {};
````

ora... la cosa importante da ricordarsi e' che `exports` da sola non e' l'oggetto modulo, ma contiene un `riferimento` (un puntatore) a quell'oggetto sopra creato, pertanto finche' gli aggiungo proprieta' non succede nulla, ma se sovrascrivo la variabile locale `exports` assegnandogli un nuovo valore (stringa, numero, o in generale la referenza a un altro oggetto), questa non avra' piu nulla a che fare con `module.exports`, e per inciso e' proprio `module.exports` che viene usato da `Nodejs` per caricare il modulo!

Riferimento:

- [understanding-module-exports-exports-node-js](https://www.sitepoint.com/understanding-module-exports-exports-node-js/)


### event-driven, non-blocking I/O

questa e' la vera peculiarita' di `Nodejs` e capirla non e' facile.

La spiegazione sarebbe lunga pertanto elenco degli ottimi siti:

- [understanding-node-js](https://www.codeschool.com/blog/2014/10/30/understanding-node-js/), con qualche riga di codice, ed indirettamente affronta l' `Event Loop`
- [http://www.baloo.io/blog/2013/11/30/node-event-driven-programming/](http://www.baloo.io/blog/2013/11/30/node-event-driven-programming/), fa capire la differenza, considerando anche i tempi dei processi
- [why-should-i-use-node-js-the-non-blocking-event-io-framework](http://developers.redhat.com/blog/2016/08/16/why-should-i-use-node-js-the-non-blocking-event-io-framework/), fatto bene, e con un paio di codici che riescono a far capire la differenza
- [node-js-doctors-offices-and-fast-food-restaurants-understanding-event-driven-programming/](http://code.danyork.com/2011/01/25/node-js-doctors-offices-and-fast-food-restaurants-understanding-event-driven-programming/), fa capire con analogie su ristoranti e reception: molto rapido

In ogni caso la parte piu importante da capire nel confronto multi-threads/multi-processes vs event-drivent... consta nella gestione del `context switching time` e nel fatto che il `non-blocking I/O` sostanzialmente e' un mezzo per fare in modo che il Processo (programma) sfrutti a pieno il tempo di clock che il Sistema Operativo concede a quel processo prima di stopparlo per passare a un altro (multi-tasking).

Una frase da tenere a mente:

> Event-driven applications are themselves multiplexing CPU time between clients.

**NB**

In realta' i thread non sono completamente abbandonati, ma viene usato un `Thread Pool` per le operazioni di I/O, tipicamente l'accesso ai socket e i files descriptor (__epool__ ad esempio per monitorare lo stato dei files descriptor)(Vedi [wikipedia](https://it.wikipedia.org/wiki/Thread_pool).).

Infatti si legge sempre che il `non-blocking I/O` vuol dire che appena si incontrano operazioni di I/O `si lascia in sospeso` quella parte e si passa alla callback o al loop successivo. Il `sil lascia in sospeso` vuol dire proprio che si assegna quell'operazione ad un thread del `thread pool` (o eventualmente lo si crea), e sara' questo thread a far sapere al processo `Nodejs` che ora quell' "I/O" ha finito, e conseguentemente e' possibile riprendere tale callback.



#### Event loop

- [understanding-the-nodejs-event-loop](https://nodesource.com/blog/understanding-the-nodejs-event-loop/)
- [mozilla-event-loop](https://developer.mozilla.org/it/docs/Web/JavaScript/EventLoop) , semplicissimo e spiega delle accortezze su `Stack`, `Queue` e `Heap` in meno di 30 secondi!

Risulta utile anche confrontare questo con l' `EventMachine` di `Ruby` (siamo sempre su un `Reactor Pattern`), in particolare le pagine:

- [starting-with-eventmachine-iii](http://javieracero.com/blog/starting-with-eventmachine-iii)
- [starting-with-eventmachine-iv](http://javieracero.com/blog/starting-with-eventmachine-iv)

spiegano bene come l'eventmachine inizializza il blocco che ruby legge con `yeld` , e fa anche un semplicissimo esempio in cui fa vedere che effettivamente le singole callback sono `synchronous`.


Risulta utile farsi un idea della differenza tra **Thread** e **Process**

#### Threads vs Processes

Premettendo che ancora mi e' chiara a livello concettuale, ma non l'ho ancora ben assimilata, la riassumo velocemente:

quando apro un programma, viene eseguito il processo ad esso viene associato un processo, e il processo consta nella creazione di una zona virtuale in cui lui istanziera' risorse e svolgera' i suoi compiti. La cosa importante e' che un processo apre sempre un thread, chiamato `main thread`. Il thread puo' essere considerato come un unita' di esecuzione di un processo. La differenza fondamentale tra avere molti thread o molti processi e' che quando sdoppio un processo creandone un `fork()` sto facendo un operazione molto pensante, perche' devo sostanzialmente creare una nuova zona virtuale. La differenza quindi e' che processi differenti lavorano su zone proprie e distinte, mentre thread differente appartengono allo stesso processo e di conseguenza hanno accesso alla stessa zona (shared memory), ed in quest'ultimo caso bisogna implementare dei semafori (ricordati di `CUDA`).

Ora... questa distinzione non e' utilissima per capire il `non blocking I/O event driven`, ma quando si legge su di esso spesso si cita questa distinzione, ed averla e' sempre utile.

> Ricorda: se consideri un processo come una scatola contenente altre scatole (zone di memoria private etc), allora sei sicuro che almeno una di queste scatole e' un thread (il main thread).

Links:

- [https://www.youtube.com/watch?v=O3EyzlZxx3g](https://www.youtube.com/watch?v=O3EyzlZxx3g) , esempio con giornale: veloce e simpatico
-[https://www.youtube.com/watch?v=hsERPf9k54U](https://www.youtube.com/watch?v=hsERPf9k54U), questo e' + complicato ma very completo
-[lecture.php?topic=process](http://web.stanford.edu/class/cs140/cgi-bin/lecture.php?topic=process), lettura schematica e veloce
-[What-is-the-difference-between-a-process-and-a-thread](https://www.quora.com/What-is-the-difference-between-a-process-and-a-thread)
-[os_multi_threading](http://www.tutorialspoint.com/operating_system/os_multi_threading.htm) **SUGGERITO**, spiega schematicamente e contiene anche disegni facili da interpretare




Tools
------


### Apps x Nodejs

Gestire Nodejs

- [api-platform](https://strongloop.com/node-js/api-platform/)

- [creating-desktop-applications-with-node-webkit](https://strongloop.com/strongblog/creating-desktop-applications-with-node-webkit/) , e articolo che segnala [questo browser](http://nwjs.io/), la cui peculiarita' e' di avere `Nodejs` come Engine, permettendo anche operazioni di salvataggio e creazione files, cosa che in javascript non si puo' fare in un normale browser!


### Scripts


Raccolta di esempi da semplici a complicati:

- [nodejs-by-example](http://amirrajan.net/nodejs-by-example/)
- [nodetuts.com](http://nodetuts.com/), raccolta di tutorial fatti molto bene: lo suggerisco!

In ogni caso i tools che e' bene conoscere sono

- `express`, come framework per applicazioni nodejs

- `browsersync`, come strumento per lo sviluppo ([buon tutorial](https://scotch.io/tutorials/how-to-use-browsersync-for-faster-development)


Piu in generale sto rivalutando `NodeJs` come strumento per server locali.
Se non sono legato a specifiche configurazioni server (ad esempio obbligo verso **PHP**) allora nodejs puo' essere considerato una valida opzione, in quanto leggero e ricco di plugin facilmente utilizzabili.


### Installazione Globale

script pesanti come `Gulp` o simili preferisco installarli globalmente,
ma e' sempre buona prassi tenernel traccia nel progetto almeno come `dev-dependencies`.

In ogni caso qualora riscontri dei problemi, ad esempio errori di **plugin locale non trovato** , posso lanciare

````
npm link gulp
````
ed in questo caso `nodejs` creera una directory `node_modules` con dentro i link alle installazioni globali: comodo!

#### NB

in ogni caso controllare che non sia anche presente la versione `cli` se voglio fare le cose fatte bene, ad esempio:

````
npm install --global gulp-cli
````


### Gulp

Tutorial ben fatto: [automate-your-tasks-easily-with-gulp-js](https://scotch.io/tutorials/automate-your-tasks-easily-with-gulp-js).


### Server Watch

Ho visto la presenza di molti server con l'opzione `watch` per monitorare dei cambiamenti localie aggiornare il browser.

Tipicamente questi funzionano via websocket:

1. inizializzazione
    - creano un websocket-server
    - si mettono in watch per vedere quando un file cambia
2. funzione server
    - quando arriva una richiesta http injectano nella body della risposta codice javascript che crea un websocket-client in ascolto del websocket-server sopra creato
3. aggiornamento
    - quando viene triggerato il watch, dopo eventuali rebuild il websocket-server manda un messaggio al client per dirgli di riaggiornare il browser

spesso bisogna notare che questi server possono triggerare eventi personalizzati nella macchina su cui girano (il mio pc), ma possono anche creare dei nuovi eventi javascript sia in dispatch che in listener dentro al browser stesso, arricchendo cosi' Javascript con opzioni server side.

> Naturalmente in quest'ultimo caso sono strettamente legato al server nodejs


Senza entrare nel dettaglio... sostanzialmente tutto e' fattibile perche' di base con nodejs e' possibile creare un socket, e poi lo sviluppatore deve implementare tutto secondo il protocollo desiderato.

Una nozione utile e' la seguente: per il refresh e' importante usare una `websocket` perche' questa apre un canale di comunicazione continuo tra client e server, e pertanto essi possono continuare a dialogare.
Con un classico `http` al massimo posso continuare a fare chiamate `ajax` con un `setTimeout`, ma a quel punto devo anche implementare una piccola logica su come il server puo' far capire al browser che e' avvenuto un suo aggiornamento locale (ricordo che http e' **stateless**). A questo problema potrei ovviare usando un file di riferimento, e sul quale il server puo' fare un touch per aggiornare la data ogni volta che viene triggerato il watch: in questo modo il client puo' rivolgere la sua chiamata ajax a quel file e di volta volta controllare la data di tale file per scegliere se svolgere il refresh, pertanto:

- quando il browser si aggiorna memorizza la data corrente in una variabile
- alla chiamata ajax confronta la data del file con la data di quando e' stato aggiornato:
    - se la data il file e' piu' giovane, vuol dire che il server ha localmente aggiornato i files, pertanto il browser sa di doversi ricaricare
- ripetere a loop ogni tot di secondi


**Vantaggi**

implementare manualmente o via nodejs (oppure ruby o python) questo monitoraggio con refresh puo' essere utile per fare in modo che il progetto rimanga il meno possibile vincolato all'IDE scelto da ogni singolo sviluppatore, guadagnando cosi' in portabilita'.

Certo, in ogni caso obbligo ogni sviluppatore a installare l'interprete che scelgo, ma una volta installato non limito le preferenze estetiche dello sviluppatore stesso, che mentre a lanciare un comando non dovrebbe avere noie, a cambiare IDE invece potrebbe essere fortemente scoraggiato (imparare nuove shotcut, personalizzare thema, etc...).


### Servers

esempi di server interessanti che implementano autoreload e scambio di messaggi (notifiche) mediante websocket broadcast:

- [real-time-notification-system-using-socket-io/](https://codeforgeek.com/2015/09/real-time-notification-system-using-socket-io/)
- [nodejs-and-a-simple-push-notification-server/](http://www.gianlucaguarini.com/blog/nodejs-and-a-simple-push-notification-server/)


Un esempio interessante potrebbe essere l'ausilio di **Proxy Servers**, per dettagli cercare i comandi:

- `proxypass`
- `proxyreverse`

Inoltre questo e' very fico: [https://github.com/nodejitsu/node-http-proxy](https://github.com/nodejitsu/node-http-proxy)

### Interessante


Su [https://www.sitepoint.com/sitepoint-smackdown-php-vs-node-js/](https://www.sitepoint.com/sitepoint-smackdown-php-vs-node-js/)
e' presente un articolo interessante, in particolare il concetto di `Applications are permanently on` e `An Event-Driven, Non-blockeing I/O`: leggere e capire!!!

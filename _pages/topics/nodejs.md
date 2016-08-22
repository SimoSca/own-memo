---
title:      NodeJs
permalink:  /topics/nodejs/
---

Per la ruggine, consiglio di leggere l'articolo

[https://www.airpair.com/node.js/posts/top-10-mistakes-node-developers-make](https://www.airpair.com/node.js/posts/top-10-mistakes-node-developers-make).


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

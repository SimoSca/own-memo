---
title:      NodeJs
permalink:  /nodejs/
---

Per la ruggine, consiglio di leggere l'articolo

[https://www.airpair.com/node.js/posts/top-10-mistakes-node-developers-make](https://www.airpair.com/node.js/posts/top-10-mistakes-node-developers-make).


In ogni caso i toolse che e' bene conoscere sono

- `express`, come framework per applicazioni nodejs

- `browsersync`, come strumento per lo sviluppo


Piu in generale sto rivalutando `NodeJs` come strumento per server locali.
Se non sono legato a specifiche configurazioni server (ad esempio obbligo verso **PHP**) allora nodejs puo' essere considerato una valida opzione, in quanto leggero e ricco di plugin facilmente utilizzabili.



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

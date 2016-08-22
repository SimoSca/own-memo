---
title:      Reti e Terminologia
permalink:  /developer/reti-e-terminologia/
---

Breve riassunto sulle reti e i principali termini che si possono incontrare nel forum.



Reti
----

Con le Reti si parla spesso di **protocolli** ovvero una serie di procedure atte a scambiare informazioni strutturando uno standard.


### Livelli e Servizi

Ciascun protocollo regola normalmente solo una parte degli aspetti di una comunicazione. I diversi protocolli sono organizzati con un sistema detto "a livelli" : a ciascun livello viene usato unospecifico protocollo.

La divisione in livelli è fatta in modo che ciascun livello utilizzi i servizi offerti dal livello inferiore, e fornisca servizi più "ricchi" al livello superiore. I diversi livelli in un host comunicano tra loro tramite le interfacce. Ogni livello parla solo con quello immediatamente superiore e con quello immediatamente inferiore. I protocolli regolano
invece la comunicazione tra due entità dello stesso livello, che serve a fornire servizi al livello superiore.

I vari livelli sono organizzati in pile di protocolli. Le pile di protocolli sono un modo flessibile per combinare componenti per realizzare un servizio.

Il livello più basso (1) è detto "livello fisico" e si occupa di gestire la trasmissione dei segnali attraverso il mezzo di trasporto (cavo, fibra ottica, infrarossi, ecc...). Il livello più elevato è chiamato "livello applicativo" ed è quello che permette all'utente di creare il messaggio da comunicare.



### iter protocollo di scambio

il protocollo **tcp/ip** oppure **udp** vengono usati dalle applicazioni, che se in versione server si mettono in ascolto di una **porta dedicata** , oppure aprono una **porta ...**. I **socket** fanno parte del sistema operativo, e interagiscono con l'applicazione scambiando i pacchetti che arrivano tramite la **scheda di rete**.

Il protocollo **TCP** apre una connessione, in quanto crea un canale di comunicazione per ricevere e inviare i __ segmenti __ da lui creati ad un altro nodo della rete e controlla che i pacchetti non vengano persi. Invece **IP** e **UDP** si limitano a lanciare i segmenti nella rete, senza verificare nulla (persi nella rete, integrita', ricezione, etc), infatti NON APRONO connessioni.

In genere il protocollo **TCP** dopo aver effettuato la sua __ tree handshake __ riceve istruzioni sull'ampiezza dei **segmenti** , in quanto l'altro nodo gli ha detto quanto deve essere grande la finestra; per questo motivo l'intero "dato" da inviare puo' essere spezzettato in piu' pacchetti (a meno che nello heder non vengano specificate opportune opzioni). Invece **UDC e IP** visto che sono a direzione univoca, mandano l'intero "dato" senza suddividerlo (in genere).


#### TCP/IP

Internet stabilisce come ordine standard per gli interi a 32 bit, quello che prevede che i byte più significativi siano trasmessi per primi (stile Big Endian). Guardando viaggiare i dati da una macchina all’altra, un intero a 32 bit comincia ad essere trasmesso dal byte più significativo, cioè col byte più significativo più vicino
all’inizio del pacchetto. Le librerie socket forniscono per le conversioni delle funzioni che sono: ntohs,ntohl,
htons,htonl.


#### Routing

Il routing IP, ovvero l’instradamento di un pacchetto IP verso la destinazione specificata nell’header del pacchetto IP viene effettuato secondo le seguenti modalità:

* Un certo host deve inviare un pacchetto IP verso una destinazione IP_D (oppure un router ha ricevuto un pacchetto e deve instradarlo verso la destinazione IP_D).
* l’host controlla l’indirizzo IP di destinazione per capire se l’indirizzo IP_D appartiene alla sua stessa sottorete cioè se la destinazione è raggiungibile in modo diretto **mediante il MAC address** cioe' sfruttando il solo livello 2, ovvero se per raggiungere la destinazione non è necessario attraversare router (livello 3) ma al massimo si attraversano dei bridge (vedi subnetting).

* Se la destinazione sta sulla stessa sottorete dell’host, si usa la consegna diretta, cioè l’host trova (col protocollo **ARP**) l’indirizzo di livello 2 di destinazione (MAC_D), incapsula il pacchetto IP in un pacchetto di livello 2 con quell’indirizzo MAC_D come destinazione, e lo invia in rete.
* La destinazione MAC_D a livello 2 vede passare il pacchetto, vede il proprio indirizzo MAC_D e carica il pacchetto passandolo al livello IP ed il gioco è fatto.

* Se invece la destinazione non sta sulla stessa sottorete si usa la consegna indiretta, cioè l’host deve inviare il pacchetto IP al router di default, ovvero un router che l’host conosce e che sta sulla stessa
sottorete dell’host. L’host cerca (ancora con il protocollo ARP) l’indirizzo di livello 2 del default router (MAC_R), incapsula il pacchetto IP in un pacchetto di livello 2 con quell’indirizzo MAC_R come destinazione, e lo invia in rete.  Il router vede passare il pacchetto con il proprio MAC address, lo carica, estrae il pacchetto IP, vede che l’indirizzo IP_D non è il suo e lo instrada con lo stesso meccanismo già visto.

Quindi in sostanza si puo' avere consegna DIRETTA via MAC address o INDIRETTA via IP address.


Puo' essere in generale utile porsi la domanda: come viene fatto il routing all'interno di una rete??


### Socket

la socket e' il programma che fa da tramite tra il protocollo e la scheda di rete/porta. La socket fa parte del sistema operativo e ascolta su una singola porta.

Il sistema operativo puo':

* assegnare una unica socket sulla porta (un processo dedicato), e di conseguenza solo un Guest per volta puo' rimanere connesso su di essa
* assegnare una unica socket sulla porta (processo genitore) che rimane in ascolto di richieste, e aprire un sottoprocesso (figlio) per ogni richiesta che viene fatta su quella porta (ad esempio protocollo SSH: piu utenti si possono connettere alla stessa porta, e ogni singolo utente puo' creare piu connessioni Guest aprendo piu terminali per accedere ssh all'host)


### Tipo di Rete

a seconda del tipo di rete cambia il modo in cui vengono inviati i dati. Ad esempio la rete **ethernet** manda i dati in **BROADCAST**, ovvero lancia i dati a tutti i nodi della rete come fa una ricetrasmittente: fissata la banda (la rete, ad esempio locale), tutti possono sia trasmettere che ricevere. I dati in questo caso condividono lo stesso dominio, e possono essere persi (ad esempio se due nodi lanciano messaggi contemporaneamente questi crashano tra di loro). La rete ethernet e' veloce ma non svolge alcun controllo, pertanto i dati possono essere persi, corrotti o addirittura possono essere ricevuti senza rispettare l'ordine cronologico. Ricordo che eventualmente sono i protocolli a pensare a cosa fare (ad esempio TCP controlla che siano stati ricevuti e ricostruisce l'ordine cronologico).

In genere le reti ethernet hanno una banda massima di dati trasmissibili, intorno ai 1550 bit/s, ma puo' cambiare a seconda dei nodi attraverso i quali la rete passa. Tipicamente il router calcola l' **MTU** che indica la massima quantita' di dati trasmissibile tra due nodi (se uno ne puo' scambiare 500 e l'altro 700, ovviamente si passeranno i pacchetti del formato piu piccolo, vale a dire 500).

Se il protocollo (ad esempio IP), ha **SEGMENTI**(termine associato ai dati del protocollo) piu grandi dell' **MTU**, allora il router IPV4 puo' frammentare il segmento (i frammenti vengono detti frame), mentre il router IPV6 non puo' eseguire frammentazione: pertanto in IPV6 e' il protocollo a provvedere a suddividere i dati.
Ricapitolando quest'ultimo (rivango la terminolgia):

* i dati originali possono essere suddivisi in **SEGMENTI** da parte del protocollo e passati al router
* il router (ipv4) puo' a sua volta suddividere i SEGMENTI in **FRAME** (detti anche **frammenti**)

#### Ethernet

Un computer con scheda di rete Ethernet può inviare i pacchetti di dati solo quando nessun altro pacchetto sta viaggiando sulla rete, ovverosia quando la rete è “tranquilla��?. In caso contrario, aspetta a trasmettere come quando, durante una conversazione, una persona deve attendere che l’altra smetta prima di parlare a sua volta.

Se più computer percepiscono contemporaneamente un momento “tranquillo��? e iniziano ad inviare i dati nello stesso momento, si verifica una “collisione��? dei dati sulla rete, che non implica errori ma la necessità di altri tentativi.
Ogni computer, infatti, attende per un certo  periodo e prova a inviare nuovamente il pacchetto di dati.


### MAC

I calcolatori hanno un MAC address, che serve ad identificarli, e puo' essere utilizzato anche per capire se puo' supportare il **MULTICAST** .

Gli indirizzi IP oltre a identificare il nodo, aiutano anche a capire il livello del nodo, e ve ne sono di privilegiati: ad esempio vi e' un nodo di podcast, il cui scopo e' indirizzare il pacchetto a TUTTI i nodi della sua rete (vi e' un analogo per il multicast, relativamente a un gruppo), oppure un IP speciale e' quello riservato al router.


#### Modem

La soluzione standard per collegarsi ad altre reti o a Internet, o per permettere agli utenti remoti di collegarsi alla propria rete centralizzata, è la normale linea telefonica analogica. Basta quindi collegare un modem al computer e alla presa del telefono per collegarsi ad un Internet Service Provider o ad una filiale.

Un modem può supportare solo una “conversazione��? remota alla volta e ogni computer che vuole collegarsi con l’esterno deve disporre di un proprio modem. 10 computer richiedono perciò 10 modem, ma in questo caso vi è una soluzione più efficiente per una connessione WAN: il router.

Il router utilizza linee ISDN (digitali) e collega tutti i computer della rete locale: basta un router e 10 (o più) computer possono navigare sul web o collegarsi ad una filiale. Inoltre il router offre maggior protezione da accessi indesiderati, è più rapido nell’effettuare la connessione e nello staccare la linea telefonica quando l’attività di rete cessa.


### Router

il router riceve i pacchetti e li rimanda alla rete (locale o esterna, come internet) in broadcast (vedere meglio se li rimanda in broadcast o puo' addirittura indirizzare a un nodo specifico). I router (a stella) connettono piu' nodi, e con maggiori sono i nodi con maggiore e' la probabilita' di perdere dati, perche' aumenta lo spazio del dominio condiviso (ci possono ad esempio essere piu scontri tra pacchetti provvenienti da piu nodi). Credo che in generale il router riesca a instradare a un nodo della rete locale, mentre credo che per i pacchetti internet si limiti da mandare il pacchetto nel web in broadcast, o a uno switcher



### Switcher

versione evoluta del router: puo' essere composto da piu moduli, ciascuno con una propria scheda di rete. Se il nodo A del modulo 1 manda un pacchetto per il nodo B:

* se B sta nel modulo 1, si limita a mandare il pacchetto in broadcast a tutti i nodi del modulo 1

* se B sta in un modulo n (!=1):
	* se ha gia' memorizzato il MAC di B (e di conseguenza il suo modulo), allora manda  il pacchetto al modulo di B
	* se non ha informazioni sul nodo B, allora manda il frammento in broadcast a tutti gli altri moduli, e il modulo, diciamo 27, che contiene B rimanda un messaggio al modulo 1: in questo modo modulo 1 memorizza in una tabella il fatto che B(identificato col suo MAC) e' memorizzato nel modulo 27

da quest'ultimo punto si capisce che lo Switch ottimizza in quanto nella sua rete velocizza l'instradamento in quanto ciascun modulo puo' memorizzare le posizioni dei nodi in una propria tabella.

Inoltre lo switch e' dotato di un buffer, in cui memorizza temporaneamente i pacchetti, per poi lavorarci secondo quanto indicato sopra; proprio per via di questo buffer, e il fatto che le ethernet sono singole, lo switcher evita il problema della __ condivisione del dominio __ riducendo cosi' l'instabilita' della trasmissione dei dati.



### Hub

e' un collegamento fisico tra dei nodi: si limita ricevere pacchetti e a mandarli in broadcast agli altri nodi facenti parte della sua stella. Se alimentato a corrente oltre alla funzione di broadcast riamplia il segnare, evitando cosi' il suo degrado (tipo un range extender).


### Sub

e' un collegamento fisico tra componenti del sistema per passare messaggi di varia natura, quali ad esempio:

* informazioni, tra scheda madre e CPU collega i fili passando cosi' segnali binari.
* informazioni, tra il buffer della tastiera e le componenti che elaborano tale dato (ad esempio il sistema operativo, anche se questo e' gia' a livello software)
* dati, come avviene tra il disco fisso e le memorie USB


### Buffer

e' una memoria tampone in cui vengono salvati temporaneamente i dati per essere utilizzati appena la risorsa si libera; tipicamente e' uno stack di tipo FIFO. Esempi:

* il buffer di tastiera: se digito velocemente la tastiera salva i dati in un buffer, e il buffer viene man mano letto dal sistema. Ecco perche' se scrivo mentre il sistema e' incantato, dopo qualche secondo riappaiono tutti i caratteri (digito, i caratteri stanno nel buffer, il sistema operativo e' bloccato pertanto il buffer continua a riempirsi, poi il sistema si libera e svuota il buffer della tastiera prendendo tutti i suoi dati)

* buffer di CPU: quanto appena visto per la tastiera, oppure se devo mandare dati a una stampante. Infatti la stampante e' lenta, pertanto la CPU salva i dati da stampare nel suo buffer, cosi' la CPU ora si libera per continuare altri processi e la stampante puo' reperire con calma i dati nel buffer

Qundi in generale il buffer, sia fisico che software, puo' essere utile per far comunicare processi che hanno tempistiche tra loro differenti.


### Cache

zona di memoria ad accesso rapido. In sostanza la cache e' un buffer della RAM, e la ram e' un buffer del DISCO FISSO.


### Volatile

Memoria tipicamente alimentata a corrente, che si resetta ogni volta che l'alimentazione cessa. Sono esempi la Ram e le memorie Flash. Il pc ad ogni riaccensione deve ricreare tutti i suoi processi e i dati sulla ram. Se invece la ram non fosse volatile, allora tutto potrebbe essere congelato in essa e di conseguenza il pc sarebbe subito pronto per essere utilizzato eliminando i tempi di caricamento e i costi derivanti dalla sua alimentazione.

Non volatile invece vuol dire che la memoria non si perde: ad esempio il disco fisso e le memorie USB, che non si cancellano ma pagano eccedendo in lentezza rispetto alle memorie volatili.



## Subnet Mask

In una rete di calcolatori, e' presente un **gateway** (ovvero un indirizzo IP) a cui si passa il pacchetto da inoltrare a un altro indirizzo IP. In sostanza il gateway nella rete locale e' il router, pertanto per spedire un pacchetto all' IP-7 (supponiamo che io abbia IP-3), in realta' mando il pacchetto al gateway, e lui lo smista o alla rete locale mandandolo all'IP-7, oppure al resto del mondo, ovvero internet.
La distinzione tra locale e internet ovviamente fa entrare in gioco discorsi sulla sicurezza, firewall ed eventualmente su come il pacchetto possa avere degli header modificati, ma questo non importa.

Tornando alla subnet: e' un formato simile a un ip (4 sequenze da 8 bit) e il router la usa per determinare se due calcolatori appartengono alla stessa rete (o sottorete, detto Network Address) semplicemente facendo un AND logico (bit a bit) tra IP e Subnet mask, in modo da trovare un indiritto ip a cui appartiene la sottorete.

Riassumento: le sottoreti possono comunicare senza bisogno di instradarsi su un router!! vedi http://www.dis.uniroma1.it/~ficarola/notazione-cidr/ e convenzione di descrizione CIDR.

In sostanza se

	````
	IP-7 & SubnetMask == IP-3 & SubnetMask
	````
allora vuol dire che i due ip appartengono alla stessa sottorete, pertanto il router lo inoltra direttamente al calcolatore. Inoltre l'indirizzo che otteniamo secondo questo AND logico e' proprio l'indirizzo della rete a cui apparteniamo.

Utile strumento per identificare tutto quello che puo' interessare una rete e la subnet mask:

http://www.subnet-calculator.com/

##### Termini sugli Ip

* **Broadcast**: quando i bit del numero che rappresenta l'host hanno tutti valore 1, l'indirizzo è detto di broadcast o broadcast address, e rappresenta tutti gli host di quella rete. Inviare un pacchetto all'indirizzo 192.168.5.255 o in forma binaria 11000000.10101000.00000101.11111111 equivale a mandare un pacchetto a tutti gli host della rete 192.168.5;  
* **Broadcast di rete**: abbiamo questo tipo di indirizzo quando tutti i bit, sia della parte relativa all'host sia della parte relativa alla rete hanno valore 1. Inviare un pacchetto a 255.255.255.255 o in binario 11111111.11111111.11111111.11111111 significa inoltrarlo verso tutti gli host della rete corrente;  
* **Loopback**: è utilizzato per funzioni di test del protocollo TCP/IP, non genera traffico di rete e corrisponde all'indirizzo 127.0.0.1;

Agli indirizzi MAC fanno riferimento i protocolli di
livello 2 (data link) -> reti locali, broadcast
• Perchè tradurre indirizzi IP in indirizzi MAC e
viceversa?
– Un host A deve mandare un pacchetto ad un host
B, conoscendo solo l’indirizzo IP; c’è il problema di
determinare l’indirizzo fisico-MAC su cui lavora lo
strato Ethernet
– Il protocollo RARP serve anche agli host per
calcolare, in caso di guasti di alcune parti del
sistema, il proprio indirizzo IP a partire da quello
fisico-MAC


Test
=====



#### file descriptor

per `stdin`, `stdout` e `stderr` apro due terminali ed in entrambi digito `tty`, e vedro' una risposta del tipo `/dev/pts/1` e `/dev/pts/2`. allora in `pts1` digito `echo "Ciao" 1>/dev/pts/2"` e magicamente in `pts2` vedro' apparire la scritta "ciao".

Spiegazione: con `1>` reindirizzo lo `stdout` di `pts1` dovunque voglio... in questo caso con `/dev/pts/2` reindirizzo al file descriptor che e' associato a `pts2`. Infatti il terminale continua a leggere/scrivere sul file descriptor da lui creato alla sua apertura, e che funge da un buffer statico.




#### Rappresentazione Numeri


Utili strumenti online per capire la rappresentazione, anche in virgola mobile:

* http://www.h-schmidt.net/FloatConverter/IEEE754.html



#### Comandi Utili (windows)


* `netstat`
* `netstat -a -b`
* `tracert`
* `netsh trace start capture=yes`



UDP Server - Client
-------------------

ottimo esempio java in

http://michieldemey.be/blog/network-discovery-using-udp-broadcast/




Proxy Server
------------

ottimo esempio su [http://www.linuxfocus.org/Italiano/March2000/article147.html](http://www.linuxfocus.org/Italiano/March2000/article147.html).

Ad esempio `Browsersync` usa proprio dei proxy server!

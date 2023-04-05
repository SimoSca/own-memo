---
title:      DNS & Zones
permalink:  /topics/dns-and-zones/
---

Non ho niente di particolare da dire in merito alla gestione dei DNS, 
però una cosa che mi è capitata di fare è quella di creare una zona DNS per un sottodominio,
per svolgere quello che viene generalmente chiamato 
[Zone Delegation](https://docs.infoblox.com/space/BloxOneDDI/186746617/Configuring+Zone+Delegation#:~:text=A%20DNS%20zone%20delegation%20is,which%20the%20zone%20is%20delegated.).

In pratica il concetto è quello di fare in modo che chi possiede/gestisce un dominio (ad esempio `example.com`)
possa trasferire a terze parti la possibilità di gestire un intero sottodominio (diciamo `mysub.example.com`).

In questo caso ho fatto una prova sfruttando un mio servizio DNS ([Porkbun](https://porkbun.com/)) 
e un mio semplice NS Server che gira con `Bind9` (tra l'altro questo server lo uso anche come proxy con `Squid`, ma non risulta importante in questo contesto).

> Server bind9 - 52.xxx.yyy.zzz - espone porta udp 53/udp

In pratica chi gestisce il dominio principale deve impostare un record **NS** per `mysub.example.com` 
e il valore (o più valori) devono corrispondere ai NS del dominio di terze parti, come indicato [qui](https://www.youtube.com/watch?v=COaARRYXdts).


Nel mio caso specifico ho svolto i passaggi sotto riportati



### 1 - Dominio Principale

Sul dominio principale creo record NS per il sottodominio `mysub.example.com` associandogli un opportuno valore:
io voglio che "il valore" sia l'IP del server Bind9 (che espone porta `53/udp`).

E' importante notare che non potendo impostare un IP, 
di fatto ho creato un record **A** `mysub-ns.example.com` che punta a `52.xxx.yyy.zzz`.


### 2 - Configurazione Bind9

Sul server imposto una "zone" relativa al mio sottodominio, ad esempio

````
zone "mysub.example.com" {
   type master;
   file "/etc/bind/extras/mysub.example.com.hosts";
};
````

> il path del file è arbitrario

ed in particolare il file `mysub.example.com`.hosts:

````
$ttl 38400
mysub.example.com.	IN	SOA	kaizen-ns1.mysub.example.com. kaizen.mysub.example.com. (
   1606594451
   10800
   3600
   604800
   38400 
)
mysub.example.com.	IN	NS	kaizen-ns1.mysub.example.com.
kaizen-ns1.mysub.example.com.	IN	A	127.0.0.1
time.mysub.example.com.	IN	A	151.101.113.176
kaizen.mysub.example.com.	IN	A	192.169.0.1
kaizen.mysub.example.com.	IN	A	192.169.0.2
time1.mysub.example.com.	IN	CNAME	www.timeanddate.com.
time2.mysub.example.com.	IN	CNAME	k.shared.global.fastly.net.
time3.mysub.example.com.	IN	A	151.101.113.176
kaizen2.mysub.example.com.	IN	CNAME	kaizen.inodracs.online.
www.mysub.example.com. IN CNAME google.com.
; NOTE: kaizen-ns1.mysub.example.com is a fake not resolved
````

### 3 - A questo punto...

a questo punto tutto dovrebbe funzionare correttamente.

**IMPORTANTE**:

affinchè tutto funzioni è sufficiente che il NS del sottodominio (ovvero server Bind9 in questo caso)
sia raggiungibile alla porta 53/udp dal server che dovrà svolgere la chamata al DNS.

Se ad esempio l'host del server Bind9 ha la porta 53/udp raggiungibile solo da VPN,
sul mio pc potrò risolvere correttamente il sottodominio e tutti i vari domini da esso creato, solo se anch'io sono in VPN
(dovrebbe essere logico: se non raggiungo il **NS**, come faccio a risolvere i DNS?!?!).



PROVE
-----

Se provo da un server/pc che non raggiunge il NS per `mysub.example.com`:

````
$ nslookup kaizen.mysub.example.com
Server:     127.0.0.53
Address:    127.0.0.53#53

** server can't find kaizen.mysub.example.com: SERVFAIL
````

quindi da dove lo lancio, devo essere sicuro che il server `52.xxx.yyy.zzz` risponda... 
il che vuol dire che "volendo" potrebbe non essere per forza pubblico...


Se dopo aver impostato il **NS** per `mysub.example.com` su porkbu provo a impostare ad esempio `kaizen.mysub.example.com` 
come `A 192.178.0.10` porkbun mi dice:

> ERROR: Could not add DNS record: Cannot create a non-glue record that is beneath a delegated child zone.

quindi non me lo fa impostare perche' creerei una situazione incongruente.


Se successivamente rimuovo il record `NS   mysub.example.com    mysub-ns.example.com 600` che avevo impostato all'inizio e attendo che si pulisca la cache,
scopro che effettivamente non prova più a usare il NS che avevo impostato (`mysub-ns.example.com`) 
e quindi non viene più utilizzato per la risoluzione (di fatto sono praticamente stati scollegati).



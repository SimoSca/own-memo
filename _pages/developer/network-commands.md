---
title:      Networks
permalink:  /developer/networks-commands/
---


semplice elenco di comandi che possono tornare utili per gestire/controllare i network


#### controllo su porta specifica

- `netstat -an | grep 9000`

in questo modo vedo quante tcp ascoltanto la porta 9000


#### Ip Table

simulazione del comando `route show` in Mac OSX

- `netstat -rn`


#### Add Route

Specify a route for network 10.0.0.0/24 to be routed to gateway 10.0.0.1:

In OS X:
````
$ route -n add 10.0.0.0/24 10.0.0.1
````

In Linux like system:
````
$ route -n add -net 10.0.0.0/24 gw 10.0.0.1
````


#### Check Chiamate in un server 

ad es. Mysql:

````
netstat -nat |grep <ip server> | awk '{print $6}' | sort | uniq -c | sort -n
````

> NOTA: sono TCP


#### IP from DNS

````
nslookup <dns>
```` 

### NSLOOKUP Extras


`nslookup`, poi:

- server `8.8.8.8` -> cosi' gli dico come risolvere

- www.example.com -> cosi' vedo come risponde

Se voglio vedere l'owner (vediamo i dettagli del proprietario):

`whois noidivittoria.it`


### Secure ssh

````
ssh -L 3307:localhost:3306 root@www.remote_host
````
Bind Address (port:host:hostport):
dico di mappare la porta locale 3307 nel localhost:3307 della vps www.remote_host .
Quindi da locale se voglio accedere al BD dovro' dire al client di usare la porta 3307


### Bridge SSH host1-pc-host2

Per utilizzare il pc come bridge per copiare da host1 a host2:

````bash
# scp
scp -3 <user1@host1:/file_path1> <user2@host2:/file_path2>
# ssh tunnel
ssh -R 127.0.0.1:9999:host2:22 user_host1@host1   rsync -e "ssh -p 9999" /file_path1 user2@127.0.0.1:/file_path2
````

In particolare, per il secondo comando si ha:

Lets parse it:

- ssh make connection to host1
- `-R 127.0.0.1:9999:host2:22` listen on remote (host1) loopback at port 9999 and forward connection over client host to host2:22
- on host1 rsync run
- `-e "ssh -p 9999"` force using non default ssh port for connection
- rsync local path `/file_path1` to remote path `user2@127.0.0.1:/file_path2` we make connection to user2@host2 but as address we use mapped earlier loopback port

Da una mia domanda su:

- [https://superuser.com/questions/1236472/localhost-as-ssh-bridge-between-two-server](https://superuser.com/questions/1236472/localhost-as-ssh-bridge-between-two-server)


Pero' cosi' non funzionava, quindi illustro i dettagli dei passaggi svolti:

##### Preparazione/Fix

in una console occupo le porte tenendo aperta la connessione (`-N`):

````bash
ssh -R 127.0.0.1:9999:host2:22 user_host1@host1 -N
````

in un altra console accedo a `host1` e da li provo a eseguire la seconda parte del comando:

````bash
rsync -e "ssh -p 9999" /file_path1 user2@127.0.0.1:/file_path2
````

Ora... l'idea e' che di fatto da `host1` sto provando a fare un sync della cartella/file `file_path1` all'indirizzo
 `user2@127.0.0.1(porta 9999):/file_path2`. Quindi:

**1**

dato che l'host `[127.0.0.1]:9999` non esisteva, mi chiede di aggiungerlo ai known hosts (rispondo yes)

**2**

ora so che `[127.0.0.1]:9999` in `host1` viene mappato su `host2:22`, tramite l'ssh lanciato dalla shell sul mio pc (infatti utilizzo `user2`).
Di conseguenza sull' `host1` **DEVO** avere la chiave ssh dell'`host2` (permessato a 400).

**FUNZIA**

Dopo i precedenti passaggi, sull'`host1` posso eseguire il comando

````bash
rsync -e "ssh -p 9999 -i my_ssh_key_for_host2" /file_path1 user2@127.0.0.1:/file_path2
````

> ricorda che __my_ssh_key_for_host2__ deve essere presente su `host1` e non sul pc!!


##### ALL IN ONE

Dopo la prima configurazione (**Preparazione/Fix**) i comandi possono sempre essere eseguiti in una volta sola mediante:

````bash
ssh -R 127.0.0.1:9999:host2:22 user_host1@host1 'rsync -e "ssh -p 9999 -i my_ssh_key_for_host2" /file_path1 user2@127.0.0.1:/file_path2'
````

> Note single quote in second part!


#### SPIEGAZIONE PIU' DETTAGLIATA


Se host1 e’ l’host sorgente (contenente i files da copiare) e host2 e’ l’host di destinazione (dove verranno copiati i dati), allora bisogna, e il nostro PC deve fare da bridge, allora bisogna:

##### MIO PC (bridge)

sul nostro pc eseguire il comando (-i e -p solo se sono necessari per connettersi a host1)

````bash
ssh -i my_ssh_key_for_host1 -p port_host_1 -R 127.0.0.1:9999:host2:22 user_host1@host1 -N
````

in questo modo apro un tunnel tra host1 e host2, in particolare tutte le richieste su host1:9999 tramite il mio tunnel vengono inoltrate a host2:22 (ovvero via ssh)

##### PC SORGENTE

tramite il nostro tunnel ora il pc sorgente puo’ collegare la sua porta `999` a `host2:22` (via ssh), quindi sostanzialmente deve usare un rsync che da se stesso (127.0.0.1:9999) va alla remota (host2:22), quindi essendo un classico rsync che tenta di accedere a host2, e’ necessario che su host1 vi sia la nostra chiave rsa relativa all’host2.

Il comando e’  

````bash
rsync -e "ssh -p 9999 -i my_ssh_key_for_host2" /file_path1 user2@127.0.0.1:/file_path2
````

in questo modo copiando il file locale su `user2@127.0.0.1(porta 9999):/file_path2`, che di fatto dato il bridge viene mappato in `user2@host2(porta 22):/file_path2`



### JUMP SERVER

o Host Server: utilizzato sostanzialmente come proxy per accedere a un secondo server (o ulteriore livello), 
quando ad esempio il server non e' all'interno di un network raggiungibile dal client.

In questo esempio suppongo di avere la chain:

````
Client --> Host1 --> Host2 --> Host3 ( --> HostDb)
````

dove per ipotesi `Host{N}` e' accessibile con utente `user{N}` tramite chiave `id_rsa_{N}` presente sul server `N-1`; 
in questa condizione, il client e' considerabile `Host0`.

Se non ci fossero le chiavi, basterebbe usare `ssh -J user1@Host1, user2@Host2 user3@Host3` ma con le chiavi tutto cambia...

ad esempio con un solo jump mi basterebbe lanciare il comando

````
ssh -J -i id_rsa_1 user1@Host1 -i id_rsa_2 user2@Host2
````

Ma con tre server purtroppo l'impiego della virgola e la chiave non funziona (tra l'altro il comando diventerebbe lungo e difficile da ricordare),
pertanto in questo caso e' necessario modificare il file `~/.ssh/config` secondo quanto segue:

````
Host jump1
    HostName Host1
    User user1
    IdentityFile <path in client host>/id_rsa_1
# Host 2 e' raggiungibile da Host 1 con id_rsa_2
Host jump2
    HostName Host2
    IdentityFile <another path in host 1>/id_rsa_2 
    User user2
    ProxyJump jump1
# Host 3 e' raggiungibile da Host 2 con id_rsa_3
Host jump3
    HostName Host3
    IdentityFile <another another path in host 2>/id_rsa_3
    User user3
    ProxyJump jump2
# E da jump3 posso accedere al db: eseguo: mysql -u yyyyyyy -pxxxxxx -h HostDB
````

Con questo setup posso agilmente eseguire i comandi:

````
# accesso a jump3 (ma posso anche accedere agli intermedi come ad esempio jump2)
ssh jump3
# tunnel mysql (udp redirect): access mysql from client
ssh -L localhost:3306:HostDB:3306 jump3 -N
# tunnel mysql mounting local socket: access mysql from client
ssh -L /tmp/sql.sock:HostDB:3306 jump3 -N
````

Da notare che negli esempi sopra, l'impiego della socket risulta utile se sul client viene utilizzato un software 
che non prevede l'impiego dei jump server ma che in ogni caso consente di connettere il client mysql direttamente tramite socket, 
ad esempio `Sequel Pro` per l macbook.


### DNS and RESOLUTION

Trovo il DNS resolver di uno specifico sito con whois, ad esempio vado in [https://www.whois.net/](https://www.whois.net/) e poi estraggo i **Name Server** per dedurre quelli utilizzati dal provider.

Ad esempio con `example.com` trovo `A.IANA-SERVERS.NET`.


A questo punto ispeziono con nslookup

````bash
myserver:~# nslookup 
> www.example.com
Server:		62.149.128.4
Address:	62.149.128.4#53

Non-authoritative answer:
Name:	www.example.com
Address: 93.184.216.34
> server A.IANA-SERVERS.NET
Default server: A.IANA-SERVERS.NET
Address: 199.43.135.53#53
Default server: A.IANA-SERVERS.NET
Address: 2001:500:8f::53#53
> www.example.com
Server:		A.IANA-SERVERS.NET
Address:	199.43.135.53#53

Name:	www.example.com
Address: 93.184.216.34
> set q=txt
> www.example.com
Server:		A.IANA-SERVERS.NET
Address:	199.43.135.53#53

www.example.com	text = "v=spf1 -all"
> 
````

dove uso:
- `server` per specificare il DNS resolver
- `q=txt` se sono interessato ai record TXT

eventualmente puo' risultare piu' immediato impiegare DIG, ad esempio (non riporto l'output per brevita')

````
# non specifico il dns resolver
dig www.example.com
# specifico il dns resolver
dig @8.8.8.8 www.example.com
# specifico il dns resolver e il fatto che voglio i record txt
dig @8.8.8.8 -t txt www.example.com
# Altro ma con diverso server name
dig @A.IANA-SERVERS.NET -t txt www.example.com
````


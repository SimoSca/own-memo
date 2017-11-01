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


#### IP from DNS

````
nslookup <dns>
```` 


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

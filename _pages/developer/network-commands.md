Network
=======

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
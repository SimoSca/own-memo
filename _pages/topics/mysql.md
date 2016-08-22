---
title:      MySql
permalink:  /topics/mysql/
---

Comandi utili
-------------

#### Creao DB e Utente con permessi

 ````
create database miodb;
grant all privileges on miodb.* to miodbuser@localhost identified by 'userpwd';
````

oppure se non voglio legare l'utente ad un host, sostisuisco `localhost` con `'%'` (NB: mettere gli apici!!!).

e per vedere i permessi di quell'utente:

````
show grants for 'miodbadmin'@'%';
````


#### Modificare permessi

Prima provare ad aggiornarli sulla tabella utenti:

````
UPDATE mysql.user SET host = '10.0.0.%' WHERE host = 'internalfoo' AND user != 'root';
````
e poi aggiornare

````
FLUSH PRIVILEGES;
````

**Extra**

To list users:

````
select user,host from mysql.user;
````
To show privileges:

````
show grants for 'user'@'host';
````
To change privileges, first revoke. Such as:

````
revoke all privileges on *.* from 'user'@'host';
````
Then grant the appropriate privileges as desired:

````
grant SELECT,INSERT,UPDATE,DELETE ON `db`.* TO 'user'@'host';
````
Finally, flush:

````
flush privileges;
````

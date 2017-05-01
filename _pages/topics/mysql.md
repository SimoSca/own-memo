---
title:      MySql
permalink:  /topics/mysql/
---

Usefull
=======


````
SELECT table_schema "DB Name", 
   Round(Sum(data_length + index_length) / 1024 / 1024, 1) "DB Size in MB" 
FROM   information_schema.tables 
GROUP  BY table_schema; 
````

### GENERAL examples

[http://www.thegeekstuff.com/2014/11/mysql-insert-command](http://www.thegeekstuff.com/2014/11/mysql-insert-command)


MYSQL
=====


About `mysql` there's few cases that I wanna take count.

Simple explanation:

- [https://www.tutorialspoint.com/mysql/mysql-handling-duplicates.htm](https://www.tutorialspoint.com/mysql/mysql-handling-duplicates.htm)



### RENAME TABLE
````
mysql> ALTER TABLE tmp RENAME TO person_tbl;
````


### COMPLEX MODIFY TABLE
````
mysql> CREATE TABLE tmp SELECT last_name, first_name, sex
    ->                  FROM person_tbl;
    ->                  GROUP BY (last_name, first_name);
mysql> DROP TABLE person_tbl;
mysql> ALTER TABLE tmp RENAME TO person_tbl;
mysql> ALTER IGNORE TABLE person_tbl
    -> ADD PRIMARY KEY (last_name, first_name);
````


### COALESCE

not example here, but `COALESCE` is very usefull to manage data on runtima


### POINTER


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

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

### OPTIMIZE

Usefull for big size db data!

to shrink the db size, from command line launch (`--all-databases` to affect all databases!)

````
mysqlcheck -p${DbPassword} -o --all-databases
````
> this can take some time... be patient!!!


### DB SIZE

this show how backup and optimize size via `inndbb` engine:

- [http://www.pc-freak.net/blog/fix-mysql-ibdata-file-size-ibdata1-file-growing-large-preventing-ibdata1-eating-disk-space/](http://www.pc-freak.net/blog/fix-mysql-ibdata-file-size-ibdata1-file-growing-large-preventing-ibdata1-eating-disk-space/)


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


### Check table consistency

In some mysql dunp check if the DB depends on other DBs!!!

In example in my db dump I've:


````
21706870:FROM `dbEnel`.`joo_users` `us1` where (`us1`.`username` = `us`.`username`)) AS `firstname`,(select substring_index(substring_index(`us2`.`name`,' ',3),' ',-(1)) AS `last_name` from `dbEnel`.`joo_users` `us2` where (`us2`.`username` = `us`.`username`)) AS `lastname` from `dbEnel`.`joo_users` `us` where (not((`us`.`username` collate utf8_general_ci) in (select `enel-moodle`.`mdl_user`.`username` from `enel-moodle`.`mdl_user`)));

````

And via 
````
egrep -in --color=always  '`.*`\.' test.sql 
````

I've discovered that this dump requires the presence of external db.table:

```
`enel-moodle`.`mdl_user`.`username`
````

Do this check before import a large db!!
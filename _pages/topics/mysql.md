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


**Hierarchy**

RELAZIONI
---------

relazioni di tipo padre-figlio si possono esprimere anche usando attributi `rgt` e `lft`, con la condizione che `lft < rgt`. Ad esempio in `Joomla!` abbiamo la `#__groups`.


that is how managing parent, child... also with the use of `Tree Traversal` via `right` and `left`:

- [https://www.sitepoint.com/hierarchical-data-database-2/](https://www.sitepoint.com/hierarchical-data-database-2/)

The follow is generic and very very useful:

- [http://mikehillyer.com/articles/managing-hierarchical-data-in-mysql/](http://mikehillyer.com/articles/managing-hierarchical-data-in-mysql/)


#### Examples

- [http://we-rc.com/blog/2015/07/19/nested-set-model-practical-examples-part-i](http://we-rc.com/blog/2015/07/19/nested-set-model-practical-examples-part-i)

- [https://github.com/werc/TreeTraversal/tree/master/sql](https://github.com/werc/TreeTraversal/tree/master/sql), that's a good repository!!!



MYSQL
=====


About `mysql` there's few cases that I wanna take count.

Simple explanation:

- [https://www.tutorialspoint.com/mysql/mysql-handling-duplicates.htm](https://www.tutorialspoint.com/mysql/mysql-handling-duplicates.htm)



### RENAME TABLE
````
mysql> ALTER TABLE tmp RENAME TO person_tbl;
````

### Check last modified table

````
SELECT UPDATE_TIME,TABLE_NAME FROM   information_schema.tables WHERE  TABLE_SCHEMA = 'databaseName' ORDER BY UPDATE_TIME;
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


Note that with `innoDB` the size is not really reduced, since in `InnoDB` 
the optimize perform only a `copy` + `drop older` foreach table into DB. 
This optimize only the allocation of memory, and not it's usage/size!!!


### DB SIZE

this show how backup and optimize size via `inndbb` engine:

- [http://www.pc-freak.net/blog/fix-mysql-ibdata-file-size-ibdata1-file-growing-large-preventing-ibdata1-eating-disk-space/](http://www.pc-freak.net/blog/fix-mysql-ibdata-file-size-ibdata1-file-growing-large-preventing-ibdata1-eating-disk-space/)

In **InnoDB** gain in size you have to:

1. modify mysql configuration to enable `innodb_file_per_table` and `innodb_file_format = barracuda` (thi last to gain compatibility with older versions) 

REFS:
- [https://www.percona.com/blog/2013/09/25/how-to-reclaim-space-in-innodb-when-innodb_file_per_table-is-on/](https://www.percona.com/blog/2013/09/25/how-to-reclaim-space-in-innodb-when-innodb_file_per_table-is-on/)
- [https://easyengine.io/tutorials/mysql/enable-innodb-file-per-table/](https://easyengine.io/tutorials/mysql/enable-innodb-file-per-table/)

2. ALTER each TABLE to set ROW_COMPRESSION to **COMPRESS** (and not dymanic). IE using the script:

````bash
#!/bin/bash


DATABASE="enel-moodle"

#ROW_FORMAT=DYNAMIC
ROW_FORMAT=COMPRESSED

MySql="mysql -u root -pcicciopasticcio"

TABLES=$(echo "SHOW TABLES" | $MySql -s $DATABASE)

for TABLE in $TABLES ; do
    echo "ALTER TABLE $TABLE ROW_FORMAT=$ROW_FORMAT;"
    echo "ALTER TABLE $TABLE ROW_FORMAT=$ROW_FORMAT" | $MySql $DATABASE
done
````


REFS:
-[https://dev.mysql.com/doc/refman/5.6/en/innodb-row-format-dynamic.html](https://dev.mysql.com/doc/refman/5.6/en/innodb-row-format-dynamic.html)
 

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
FROM `my_current_db`.`joo_users` `us1` where (`us1`.`username` = `us`.`username`)) AS `firstname`,(select substring_index(substring_index(`us2`.`name`,' ',3),' ',-(1)) AS `last_name` from `my_current_db`.`joo_users` `us2` where (`us2`.`username` = `us`.`username`)) AS `lastname` from `my_current_db`.`joo_users` `us` where (not((`us`.`username` collate utf8_general_ci) in (select `other-db-moodle`.`mdl_user`.`username` from `other-db-moodle`.`mdl_user`)));

````

And via 
````
egrep -in --color=always  '`.*`\.' test.sql 
````

I've discovered that this dump requires the presence of external db.table:

```
`other-db-moodle`.`mdl_user`.`username`
````

Do this check before import a large db!!
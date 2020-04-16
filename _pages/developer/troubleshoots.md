---
title:      Compiler
permalink:  /developer/troubleshoots/
---

BREW HELM OLD VERSIONS
----------------------

per installare una vecchia versione non più presente in homebrew vedi 
[https://medium.com/@schmijos/installing-old-homebrew-formulas-dc91de0c329c](https://medium.com/@schmijos/installing-old-homebrew-formulas-dc91de0c329c)

#### STEPS

Trovare la formula [https://github.com/Homebrew/homebrew-core/tree/master/Formula](https://github.com/Homebrew/homebrew-core/tree/master/Formula)

esempio

https://github.com/Homebrew/homebrew-core/blob/9db72db1b627b9c9944856125f2103a971c228f7/Formula/helm.rb

andare alla vecchia versione (vecchio commit), per trovarla facilmente ricercare nella ricerca di git il nome del pacchetto e premere "commits". IMPORTANTE PUNTARE AL RAW FILE

https://raw.githubusercontent.com/Homebrew/homebrew-core/2fbed24cb83d0ecc69b8004e69027e0d8eed5f9d/Formula/kubernetes-helm.rb

fare 

````bash
brew install https://raw.githubusercontent.com/Homebrew/homebrew-core/2fbed24cb83d0ecc69b8004e69027e0d8eed5f9d/Formula/kubernetes-helm.rb
brew switch kubernetes-helm 2.9.1
brew pin kubernetes-helm
````


PHP COMPOSER PROBLEM
---------------------

Problema: sul composer.lock vedevo che il package ha la versione 0.9.7, ma nella `vendor/<tenant>/<package>` invece aveo la 0.9.6,
    e con composer install rimaneva 0.9.6... quindi non aggiornava!

1. con `composer show -i`  ho visto che secondo composer il package e' alla versione 0.9.7... ed anche in `vendor/composer/installed.json` effettivamente vedo 0.9.7... quindi ho un qualcosa che non va...

Con `composer clear-cache` rimuovo la cache nella mia vendor

A questo punto ho provato `rm vendor/composer/installed.json` e poi con `composer install --no-dev` vedo che finalmente li ha reinstallati, ed inoltre ha ricreato anche il `vendor/composer/installed.json`



VSCODE PROBLEM
--------------

Se il `vscode-remote` s'inceppa, posso killare i processi del mio utente:

````bash
pkill -f <my username>/.vscode
````


ANDROID ADB
-----------

/Users/<myuser>/Library/Android/sdk/platform-tools

Connect to network device:

````
adb tcpip 5555
adb devices
adb -s 192.168.14.147:5555
adb connect 192.168.14.147:5555
````



LINUX - MEMORY FULL
-------------------

All starts from mysql startup errors like

````
Apr  6 11:42:08 lamp mysqld: InnoDB: Please refer to
Apr  6 11:42:08 lamp mysqld: InnoDB: http://dev.mysql.com/doc/refman/5.6/en/innodb-troubleshooting-datadict.html
Apr  6 11:42:08 lamp mysqld: InnoDB: for how to resolve the issue.
Apr  6 11:42:08 lamp mysqld: 2020-04-06 11:42:08 11247 [ERROR] InnoDB: Table dbTmp/tmp_excel_import in the InnoDB data dictionary has tablespace id 1424223, but tablespace with that id or name does not exist. Have you deleted or moved .ibd files? This may also be a table created with CREATE TEMPORARY TABLE whose .ibd and .frm files MySQL automatically removed, but the table still exists in the InnoDB internal data dictionary.
...
Apr  6 11:42:08 lamp mysqld: 2020-04-06 11:42:08 11247 [Note] InnoDB: 128 rollback segment(s) are active.
Apr  6 11:42:08 lamp mysqld: 2020-04-06 11:42:08 11247 [Note] InnoDB: Waiting for purge to start
Apr  6 11:42:08 lamp mysqld: 2020-04-06 11:42:08 11247 [Note] InnoDB: 5.6.15 started; log sequence number 38477440181906
Apr  6 11:42:08 lamp mysqld: 2020-04-06 11:42:08 11247 [Note] Server hostname (bind-address): '*'; port: 3306
Apr  6 11:42:08 lamp mysqld: 2020-04-06 11:42:08 11247 [Note] IPv6 is available.
Apr  6 11:42:08 lamp mysqld: 2020-04-06 11:42:08 11247 [Note]   - '::' resolves to '::';
Apr  6 11:42:08 lamp mysqld: 2020-04-06 11:42:08 11247 [Note] Server socket created on IP: '::'.
Apr  6 11:42:08 lamp mysqld: 2020-04-06 11:42:08 11247 [ERROR] /opt/mysql/server-5.6/bin/mysqld: Error writing file '/var/run/mysqld/mysqld.pid' (Errcode: 28 - No space left on device)
Apr  6 11:42:08 lamp mysqld: 2020-04-06 11:42:08 11247 [ERROR] Can't start server: can't create PID file: No space left on device
````

So I thought that problem was in memory and I've free 1 GB of space, but with `df -h` I found something strange:

````
Filesystem      Size  Used Avail Use% Mounted on
/dev/dm-0        18G  18G   16G  0% /
udev             10M    0   10M   0% /dev
````

So it's all used, but i've space available... I've supposed that was a problem in fs corruption, so I've tried:
```` 
The simplest way to force fsck filesystem check on a root partition eg. /dev/sda1 
is to create an empty file called forcefsck in the partition's root directory. 
This empty file will temporarily override any other settings and force fsck to check the filesystem on the next system reboot
````

But nomore reboot (hang) because mysql break it! So I log to device in recovery mode and remove the file `/etc/rc2.d/S02mysql`
And added the file `forcefsck`, then rebooted all..

But mysql still doesn't work! Same problem: memory available but all cooupied... after thinking about it the solution:

> there's about X GB reserved to root, and are completely used!

And I see `0%`, so there's really `X Gb` free for "emergency"... this implies that I need to free more space!

That's it!
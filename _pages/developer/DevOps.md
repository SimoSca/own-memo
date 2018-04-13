---
title:      DevOps
permalink:  /developer/devops/
---


SyStemist
---------

### Manage User's Groups

- [https://serverfault.com/questions/390096/acl-multiple-default-groups](https://serverfault.com/questions/390096/acl-multiple-default-groups)


### Files Permissions:

Question: **Can a script be executable but not readable?**

Answer:

The issue is that the script is not what is running, but the interpreter (bash, perl, python, etc.). And the interpreter needs to read the script. This is different from a "regular" program, like ls, in that the program is loaded directly into the kernel, as the interpreter would. Since the kernel itself is reading program file, it doesn't need to worry about read access. The interpreter needs to read the script file, as a normal file would need to be read.

From:

[https://unix.stackexchange.com/questions/34202/can-a-script-be-executable-but-not-readable](Can a script be executable but not readable?)


### SSH restrictions

You can limit the commands an ssh user can run by editing the `authorized_keys` file by insert something like `command="<some command>"`;

This is very helpfull if you need to allow a user only make backup, like `$ mysqldump` or `$ cp`.

Read more in:

- [https://unix.stackexchange.com/questions/107541/add-ssh-user-with-minimum-rights-for-backup](https://unix.stackexchange.com/questions/107541/add-ssh-user-with-minimum-rights-for-backup)
- [https://research.kudelskisecurity.com/2013/05/14/restrict-ssh-logins-to-a-single-command/](https://research.kudelskisecurity.com/2013/05/14/restrict-ssh-logins-to-a-single-command/)
- [https://sixohthree.com/1458/locking-down-rsync-using-ssh](https://sixohthree.com/1458/locking-down-rsync-using-ssh)



### Backup/Sync

See:

- [https://www.digitalocean.com/community/tutorials/how-to-create-an-off-site-backup-of-your-site-with-rsync-on-centos-6](https://www.digitalocean.com/community/tutorials/how-to-create-an-off-site-backup-of-your-site-with-rsync-on-centos-6)
- [https://www.cyberciti.biz/faq/linux-unix-apple-osx-bsd-rsync-copy-hard-links/](https://www.cyberciti.biz/faq/linux-unix-apple-osx-bsd-rsync-copy-hard-links/)
- [https://www.linux.com/blog/howto-using-rsync-move-mountain-data](https://www.linux.com/blog/howto-using-rsync-move-mountain-data)
- [http://jc.coynel.net/2013/08/secure-remote-backup-with-openvpn-rsync-and-encfs/](http://jc.coynel.net/2013/08/secure-remote-backup-with-openvpn-rsync-and-encfs/)


### Environment

Use environment variables into scripts:

- to enable variables TO ALL USERS and to all MODES (interactive, login, and so on): put all you need into `/etc/bash.bashrc`.


When scripts are runned from external processes:

-> **cronjob:** fare un load a mano direttamente dal cron

-> **supervisor:** ok per le variabili da dentro lo script che viene eseguito; per usarle nel file di configurazione usare un costrutto del tipo `%(ENV_nome-della-variabile)s`



### Send Mail

Various links

- [smtp server](https://unix.stackexchange.com/questions/36982/can-i-set-up-system-mail-to-use-an-external-smtp-server)
- [configure sendmail inside docker container](http://stackoverflow.com/questions/26215021/configure-sendmail-inside-a-docker-container)
- [configure sendmail on Ubuntu](http://stackoverflow.com/questions/10359437/sendmail-how-to-configure-sendmail-on-ubuntu)



### BASH


#### Pipes

- [http://www.linuxjournal.com/article/2156?page=0,1](http://www.linuxjournal.com/article/2156?page=0,1)
- [http://stackoverflow.com/questions/1507816/with-bash-how-can-i-pipe-standard-error-into-another-process](http://stackoverflow.com/questions/1507816/with-bash-how-can-i-pipe-standard-error-into-another-process)

#### Fifo

potrebbe in generale essere utile il comando `mkfifo` al posto o in congiunzione con i Pipes.
Cercare un tutorial.

#### Trap

[esempio con trap e nome files temporanei](http://linuxcommand.org/wss0160.php)


#### Specific commands:

- [htop](http://www.thegeekstuff.com/2011/09/linux-htop-examples/), with examples
- [strace](http://hokstad.com/5-simple-ways-to-troubleshoot-using-strace), with examples

- `SIGTERM`

### Comandi utili

- `uname -a && cat /etc/*release` find VPS OS
- `ps auxwww | grep sshd` , listo gli utenti ssh connessi
- `sed -i.bak '$d' ~/.ssh/known_hosts` , elimino l'ultima riga di un file 
- `ps -aufp 10140` , dal pid posso ricavare dettagli tramite:
- `sudo -E docker ps` , con **-E** carica le variabili d'ambiente
- `htop`
- `top`
- `iotop`
- `docker stats`
- `vmstat`

### Comandi Network

- `netstat`
- `netstat -lnpt` , lista programmi che occupano le porte tcp (optione t)
- `nmap`
- `nslookup` , piu indicato rispetto a ping
- `telnet ip port` , per vedere se e' libera
- `netstat -a -n | grep 9000` , guardo lo stato delle porte


### Shell

Tasks lunghi lanciati da `SHELL` hanno un grosso problema: se si chiude il terminale in genere i jobs vengono bloccati!!!

In previsione di jobs lunghi possono essere affrontati in due modi:

- **semplice** mediante il comando `$ nohup <command/script with parameter> &` ; in questo modo il comando lanciato non viene associato alla shell 
(vedi un [esempio](https://www.computerhope.com/unix/unohup.htm))

- **meno semplice** (il mio preferito) mediante il comando `screen`, che pur essendo piu' complicato consente di gestire al suo interno piu' shell 
e di usare poi la combinazione `ctrl + d` per detachare le shell e quindi consentirci di chiudere il terminale di partenza come `nohup`.
Il vantaggio e' che con `screen` si puo' svolgere un `retach` e quindi recuperare tutte le sessioni detachate precedentemente


### Firewall

- `iptables` to enable/disable Output, Input and Forward netowrk packages traffic. **See some tutorial!!!**


### Check Performance

[https://askubuntu.com/questions/87035/how-to-check-hard-disk-performance
](https://askubuntu.com/questions/87035/how-to-check-hard-disk-performance
)


### File Enctrypt/Descrypt

Esempi per chiave asimmetrica `openssl`:

- [http://www.czeskis.com/random/openssl-encrypt-file.html](http://www.czeskis.com/random/openssl-encrypt-file.html)
- [https://gist.github.com/colinstein/de1755d2d7fbe27a0f1e](https://gist.github.com/colinstein/de1755d2d7fbe27a0f1e)
- [https://raymii.org/s/tutorials/Encrypt_and_decrypt_files_to_public_keys_via_the_OpenSSL_Command_Line.html](https://raymii.org/s/tutorials/Encrypt_and_decrypt_files_to_public_keys_via_the_OpenSSL_Command_Line.html)


### Server Backup/Syncronization

- `unison`, aiuta a fare in modo che piu' server siano sostanzialmente il mirror di un server base, con la possibilita' di escludere cartelle o altro
- `Rsnapshot`, utile per backup: gestisce la sua gerarchia di cartelle e storico con degli hardlink, quindi fondamentalmente mantiene una copia completa dei dati sul daily.0, e solo i file cambiati sui vari daily.1, daily.2, etc. 


SERVER
------

- [https://www.digitalocean.com/community/tutorials/how-to-host-multiple-websites-securely-with-nginx-and-php-fpm-on-ubuntu-14-04](https://www.digitalocean.com/community/tutorials/how-to-host-multiple-websites-securely-with-nginx-and-php-fpm-on-ubuntu-14-04)
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



### RunLevels

In un systema linux possiamo avere il concetto di runlevel, ovvero momento in cui vengono eseguiti gli script.
Per esempio il comando `chkconfig` puo' visualizzare la lista di script che dovrebbero partire in corrispondenza di determinati eventi.
Per dettagli:

- [https://access.redhat.com/documentation/en-us/red_hat_enterprise_linux/6/html/deployment_guide/s2-services-chkconfig](https://access.redhat.com/documentation/en-us/red_hat_enterprise_linux/6/html/deployment_guide/s2-services-chkconfig)



### Send Mail

Various links

- [smtp server](https://unix.stackexchange.com/questions/36982/can-i-set-up-system-mail-to-use-an-external-smtp-server)
- [configure sendmail inside docker container](http://stackoverflow.com/questions/26215021/configure-sendmail-inside-a-docker-container)
- [configure sendmail on Ubuntu](http://stackoverflow.com/questions/10359437/sendmail-how-to-configure-sendmail-on-ubuntu)


### Online Tools

Strumenti utili per svolgere verifiche relative a esposizioni server e/o IP DNS:

- [https://www.shodan.io/](https://www.shodan.io/), e' un port-scanner in cui posso ad esempio chiedergli di ispezionare le porte aperte su un server

- [https://mxtoolbox.com/](https://mxtoolbox.com/SuperTool.aspx?action=dns%3agoogle.it&run=networktools#), 
    utile per avere informazioni sui DNS (dns lookup, dns check, whois, etc). Anche [qui](https://mxtoolbox.com/NetworkTools.aspx).


### BASH


#### Pipes

- [http://www.linuxjournal.com/article/2156?page=0,1](http://www.linuxjournal.com/article/2156?page=0,1)
- [http://stackoverflow.com/questions/1507816/with-bash-how-can-i-pipe-standard-error-into-another-process](http://stackoverflow.com/questions/1507816/with-bash-how-can-i-pipe-standard-error-into-another-process)
- [https://www.linuxjournal.com/content/using-named-pipes-fifos-bash](https://www.linuxjournal.com/content/using-named-pipes-fifos-bash)

#### Fifo

potrebbe in generale essere utile il comando `mkfifo` al posto o in congiunzione con i Pipes.
Cercare un tutorial.

#### Trap

[esempio con trap e nome files temporanei](http://linuxcommand.org/wss0160.php)

### Script Usage

[https://vaneyckt.io/posts/safer_bash_scripts_with_set_euxo_pipefail/](https://vaneyckt.io/posts/safer_bash_scripts_with_set_euxo_pipefail/)


#### Specific commands:

- [htop](http://www.thegeekstuff.com/2011/09/linux-htop-examples/), with examples
- [strace](http://hokstad.com/5-simple-ways-to-troubleshoot-using-strace), with examples

- `SIGTERM`

#### System Checks

Stato servizi:

````bash
# listo tutti i servizi (con feedback on/off)
service --status-all

# for init scripts:
ls /etc/init.d/

## Servizi automaticamente eseguiti all'avvio
# for runlevel symlinks:
ls /etc/rc*.d/
```` 

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

- **uso stdin/stodout** [qui](https://jameshfisher.com/2018/03/31/dev-stdout-stdin/). Tenere presente anche `/proc/self/fd` cosi' come `/proc/$$/fd`


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


### Self-Signed Certificates

Per lavorare in locale con **HTTPS** ho bisogno di generare dei `self-signed` certificates da associare a un certo dominio (ad es un wildcards),
cosi' posso eventualmente testare anche le `CORS`. Di seguito i comandi, ma prima vedi i riferimenti:

- [self-signed-certificates](https://donatstudios.com/Self-Signed-Certificate-On-macOS-Apache), dove ho trovato i comandi

- [https://stackoverflow.com/questions/991758/how-to-get-pem-file-from-key-and-crt-files](how-to-get-pem-file-from-key-and-crt-files) , per generare il .PEM (ovvero ca certificate) 

Io usero' come wildcard `*.enomis.ninja` e i files di output li nomino nella stessa maniera solo per coerenza e tracciabilita':

````bash
openssl req -newkey rsa:2048 -x509 -nodes \
    -keyout *.enomis.ninja.key \
    -new \
    -out *.enomis.ninja.crt \
    -subj /CN=*.enomis.ninja \
    -reqexts SAN \
    -extensions SAN \
    -config <(cat /System/Library/OpenSSL/openssl.cnf \
        <(printf '[SAN]\nsubjectAltName=DNS:*.enomis.ninja')) \
    -sha256 \
    -days 3650

// apache
openssl rsa -in *.enomis.ninja.key -out *.enomis.ninja.nopass.key

// pem
cat \*.enomis.ninja.crt \*.enomis.ninja.key > *.enomis.ninja.ca.pem
````

> Con queste configurazioni il DNS `enomis.ninja` non risulta sotto certificato, 
> in quanto nei comandi sopra e' stata impostata una wildcard per il secondo livello.


OTTIMA ALTERNATIVA ai comandi di cui sopra, che spiega come generare direttamente da un file di configurazione (sempre via `openssh`):

- [how-to-get-https-working-on-your-local-development-environment-in-5-minutes](https://medium.freecodecamp.org/how-to-get-https-working-on-your-local-development-environment-in-5-minutes-7af615770eec)


### Creazione Certificati per Produzione

Qualora abbia bisogno di creare certificati da usare per produzione, ad esempio da mandare ad una `Certificate Authority` (**CA**), 
posso fare quanto segue:

1) creo un file con preimpostati i parametri che mi servono, es

````
[ req ]
default_bits = 2048
prompt = no
default_md = sha256
req_extensions = req_ext
distinguished_name = dn

[ dn ]
C=GB
ST=<state>
L=<city>
O=<organization>
OU=IT
CN =www.<second level>.<domain>.it

[ req_ext ]
subjectAltName = @alt_names

[ alt_names ]
DNS.1 = www.<second level>.<domain>.it
DNS.2 = <second level>.<domain>.
DNS.3 = <other->.<second level>.<domain>.
````

2) sul server genero un **CSR** con questo comando:

````
openssl rsa:2048 -keyout mysite_private.key -config <( cat csr_details.txt )
````

Da qui se voglio posso anche cenerare un certificato non validato:

````
openssl x509 \
       -signkey /etc/apache2/ssl/sky/main/<mysite_private.key> \
       -in /etc/apache2/ssl/sky/main/<mysite.csr> \
       -req -days 365 -out /etc/apache2/ssl/sky/main/<placeholder-mysite.crt>
````

Letture utili:

- [https://gist.github.com/Soarez/9688998](https://gist.github.com/Soarez/9688998)
- [https://www.youtube.com/watch?v=sEkw8ZcxtFk](https://www.youtube.com/watch?v=sEkw8ZcxtFk)
- [https://www.digitalocean.com/community/tutorials/openssl-essentials-working-with-ssl-certificates-private-keys-and-csrs](https://www.digitalocean.com/community/tutorials/openssl-essentials-working-with-ssl-certificates-private-keys-and-csrs)

Altra nota:

````
To clarify, a "CSR" file is not a key. There are four file-types involved in SSL, and on your server you must have the first two of them: a ".crt" and the corresponding ".key."

.key file:
This is the actual cryptographic key. Today, it should be 4096 bits long. A brand new key should be created for each re-issue of any certificate, since this is what provides the fundamental security.

.crt (certificate) file:
This is the cryptographic certificate corresponding to the key.

.csr (certificate signing request) file:
This file is generated as the first step to "signing" the certificate by a Certifying Authority (CA). Once signing is complete, this file is no longer needed.

ca.crt (Certifying Authority certificate) file:
This file is the bottom link in the "chain of trust" that convinces web browsers and so forth to accept that your certificate is valid. This is done by "signing" the certificate. This is the file that needs to be kept "profoundly secret."
````

Esempio di script:

````bash
#!/usr/bin/env bash

set -xe

MYSITE="enomis.ninja"
CONFIG="self-signed-enomis.conf"
CONFIGROOT="root-csr.conf"
DEST="/etc/apache2/ssl/enomis/"

### CREATE CUSTOM SELF-SIGNED ###

if [ ! -d $DEST ] ; then mkdir -p $DEST ; fi
# csr is just for remember in production
openssl req -new -sha256 -newkey rsa:2048 -nodes -keyout ${DEST}/${MYSITE}.key -config <( cat "$CONFIG" ) -out ${DEST}/${MYSITE}.csr
# Eventualmente per test locali provare:
openssl x509 \
       -signkey ${DEST}/${MYSITE}.key \
       -in ${DEST}/${MYSITE}.csr \
       -req -days 365 -out ${DEST}/${MYSITE}.crt
openssl x509 \
       -signkey ${DEST}/${MYSITE}.key \
       -in ${DEST}/${MYSITE}.csr \
       -req -days 365 -out ${DEST}/${MYSITE}.crt
# check information
#openssl x509 -in ${DEST}/${MYSITE}.crt -text -noout
## Verify:
#UNIQ_COUNT=`( \
#    openssl x509 -noout -modulus -in ${DEST}/${MYSITE}.crt | openssl md5 ; \
#    openssl rsa -noout -modulus -in ${DEST}/${MYSITE}.key | openssl md5 ; \
#    openssl req -noout -modulus -in ${DEST}/${MYSITE}.csr | openssl md5
#) | uniq | wc -l`
## You should find only one row...
#if [ $UNIQ_COUNT -ne 1 ] ; then echo "Warning" ; fi


### CREATE CUSTOM CA ###
openssl req -x509 -sha256 -days 3650 -newkey rsa:3072 \
    -config ${CONFIGROOT} -keyout ${DEST}/rootCA.key \
    -out ${DEST}/rootCA.crt
# check information
#openssl x509 -in ${DEST}/rootCA.crt -text -noout

#UNIQ_COUNT=`( \
#    openssl x509 -noout -modulus -in ${DEST}/rootCA.crt | openssl md5 ; \
#    openssl rsa -noout -modulus -in ${DEST}/rootCA.key | openssl md5 ; \
#) | uniq | wc -l`
#if [ $UNIQ_COUNT -ne 1 ] ; then echo "Warning" ; fi
````


### DNS SERVER / DNSMASQ

Utile per sviluppo in locale con utilizzo di DNS: l'idea e' di evitare di continuare a utilizzare il file `/etc/host`,
e invece creare localmente un DNS Server (ovvero un daemon locale, che nelle guide sotto e' `dnsmasq`) e manimpolare opportunamente i resolver.

- [https://www.fourkitchens.com/blog/article/local-development-apache-vhosts-and-dnsmasq/](https://www.fourkitchens.com/blog/article/local-development-apache-vhosts-and-dnsmasq/)
- [https://passingcuriosity.com/2013/dnsmasq-dev-osx/](https://passingcuriosity.com/2013/dnsmasq-dev-osx/)
- [https://gist.github.com/ogrrd/5831371](https://gist.github.com/ogrrd/5831371) -> sintetico startup

In sostanza il resolver e' il client che va a contattare il dns server (dnsmasq in questo caso).

E' sempre possibile testare la risoluzione con `scutil --dns`.

In generale quindi le parti da configurare sono due: **Resolver** (in `/etc/resolver/`) e in questo caso `dnsmasq` (in `/usr/local/etc/dnsmasq.conf`)

> Per Dnsmasq ricordarsi di riavviare il server con `sudo launchctl stop homebrew.mxcl.dnsmasq && sudo launchctl start homebrew.mxcl.dnsmasq`



SERVER
------

- [https://www.digitalocean.com/community/tutorials/how-to-host-multiple-websites-securely-with-nginx-and-php-fpm-on-ubuntu-14-04](https://www.digitalocean.com/community/tutorials/how-to-host-multiple-websites-securely-with-nginx-and-php-fpm-on-ubuntu-14-04)
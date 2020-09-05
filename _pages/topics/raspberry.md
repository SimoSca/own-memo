---
layout:     default
title:      Raspberry
permalink:  /topics/raspberry/
---


Sections:

- [Preamble](#preamble)
- [RASPBERRY AS NAS](#raspberry-as-nas)
- [FINE TUNNING](#fine-tunning)
- [Docker](#docker)



PREAMBLE
========

Per caso mi sono ritrovato con un raspberry `Pi 3 B` e non sapendo bene cosa farci ho iniziato ad utilizzarlo a caso...
prima di entrare in qualche esperienza d'utilizzo in ogni caso il setup iniziale e' abbastanza semplice e lo scrivo qui come promemoria.

In primis dobbiamo decidere che sistema operativo utilizzare e poi scrivere l'immagine sulla `Micro SD` che il raspberry utilizzera' come partizione di root per il boot.

Utilizzando un pc windows, ho optato per la soluzione piu' semplice, ovvero installare il programma `Raspberry Pi Imager`, cosi' da preparare il setup iniziale ( [qui](https://www.raspberrypi.org/downloads/) i links per il software e l'elenco delle immagini).

A questo punto quindi rimane da capire quale immagine utilizzare, esempi interessanti sono:

- Raspberry Pi OS

- Ubuntu 20.04.1 LTS

il primo ha gia' tutta una serie di feature facilmente avviabili, come ad esempio il VNC Server, mentre il secondo l'ho citato perche' sono abituato ad utilizzarlo, 
e quindi faro' sicuramente esperimenti con esso.

#### ACCESSI

Per iniziare premetto che non avevo voglia di cercare una tastiera e un monitor da attaccare al `raspberry` per utilizzarlo, ma fortunatamente possiamo agire a due livelli:

- a livello sistemistico via SSH
- a livello UX con VNC

in pratica posso prima accedere via SSH dal terminale di un qualsiasi PC e poi in un secondo momento abilitare `VNC Server` sul raspberry 
e installare un `VNC Client` su qualsiasi pc dal quale voglio poter utilizzare il raspberry via interfaccia grafica (probabilmente lo stesso pc che viene utilizzato per l'accesso SSH).

I passaggi sono quindi 

- dopo aver inserito l' SD Card e avviato il raspberry, collegarlo al cavo LAN (cosi' che acceda direttamente al network e renderlo quindi disponibile per l'accesso SSH)
- trovare l'ip del raspberry (ad esempio accedendo al pannello del router o installando un programma di network scanning su un device all'interno del network del raspberry)
- accedere via ssh usando:
    - user: `pi` | pass  `raspberry` , nel caso l'OS sia raspbian
    - user: `ubuntu` | pass  `ubuntu` , nel caso l'OS sia ubuntu
    
A questo punto suggerisco subito un 

````bash
apt update
# Opzionale
apt upgrade 
````

A questo punto e' possibile customizzare il raspberry come si preferisce. Ad esempio a mio avviso installare `VNC` puo' essere una buona idea se si vuole accedere a qualche interfaccia.

A seconda dell' OS utilizzato la cosa puo' risultare piu' o meno comoda, quindi senza entrare nei dettagli riporto alcuni link:

- [Raspberry OS](https://www.raspberrypi.org/documentation/remote-access/vnc/), 
    come dicevo all'inizio con raspian basta svolgere pochissimi step per abilitare vnc, e questo link riporta al tutorial piu' semplice che ho trovato (praticamente non bisogna fare nulla oltre all'installazione)

- [Ubuntu 20.04.1 LTS](https://engineerworkshop.com/blog/how-to-install-a-vnc-server-on-rpi-for-remote-desktop/), ubuntu server
    NOTA: lo scopo in questo caso e' minimizzare le risorse impiegate, quindi evitare xfce e simili, ma utilizzare lightdm


### VNC ON UBUNTU

Su ubuntu l'impiego di VNC non e' cosi' semplice come per `raspian`, infatti bisogna svolgere varie azioni:



#### 1) installo un display manager per il mio ubuntu server
Ad esempio seguendo [questo](https://phoenixnap.com/kb/how-to-install-a-gui-on-ubuntu)
````
sudo apt install tasksel -> leggerissimo
sudo apt install lightdm -> e durante l'installazione lo configuro come default al posto di gdm3
# start lightdm
sudo service lightdm start
````

Come ogni altro servizio poi posso stopparlo con 

````
sudo service lightdm stop
````

 
#### 2) faccio partire VNC

E poi avvio vnc, ad esempio:
````
sudo x11vnc -xkb -noxrecord -noxfixes -noxdamage -display :0 -auth /var/run/lightdm/root/:0 -usepw
````
Dove eventualmente l'auth posso trovarlo con 
````
ps aux | grep lightdm
````

e qui un altro [articolo sull'installazione con lightdm](https://www.it-swarm.dev/it/server/come-configurare-x11vnc-accedere-con-la-schermata-di-accesso-grafica/961435785/)

````
[VNCServer]
enabled=true
command=/usr/bin/x11vnc -auth guess -display :0

This content is supposed to go into a new file, e.g.
/etc/lightdm/lightdm.conf.d/vncserver.conf
````

Roba per systemd
````
[Unit]
Description=VNC
Requires=display-manager.service
After=display-manager.service

[Service]
Type=simple
ExecStart=/usr/bin/x11vnc -xkb -env FD_XDM=1 -auth guess -noxrecord -noxfixes -noxdamage -rfbauth /etc/vnc_passwd -forever -bg -rfbport 5900 -o /var/log/x11vnc.log
ExecStop=/usr/bin/killall x11vnc

[Install]
WantedBy=multi-user.target
````

Ad esempio posso attivare con

````
x11vnc -xkb -env FD_XDM=1 -auth /run/user/122/gdm/Xauthority -noxrecord -noxfixes -noxdamage -forever -bg -rfbport 5900 -o /var/log/x11vnc.log
````
dove ho customizzato il -auth .... recuperandolo da:
````
ps wwwaux | grep auth
ps aux | grep Xorg
````
Articolo che parla dell'attivazione VNC con LIGHTDM:

[https://www.it-swarm.dev/it/xorg/ubuntu-18.04-lts-x11vnc-non-funziona-piu/998311006/](https://www.it-swarm.dev/it/xorg/ubuntu-18.04-lts-x11vnc-non-funziona-piu/998311006/)

Infine per switchare tra i vari manager grafici 

````
sudo dpkg-reconfigure gdm3
sudo service gdm3 start
ma poi...
sudo dpkg-reconfigure lightdm
````



RASPBERRY AS NAS
================

NAS Storage con Raspberry e Samba
---------------------------------

Questo e' un tuttorial a mio avviso fatto piuttosto bene, anche se magari la parte sull'impiego dei permessi lascia un po a desiderare (ma parlo per professione, ed in ogni caso bisogna considerare che tutto dipende dal livello di protezione che si vuole raggiungere nel proprio network),
in ogni caso risulta un buon riferimento: [https://www.youtube.com/watch?v=A0Tz5S7TZMM](https://www.youtube.com/watch?v=A0Tz5S7TZMM).

Forse la cosa interessante e' che viene utilizzato un hard disk recuperato da un pc.


Open Media Vault
----------------

Qui il raspberry viene utilizzato come media center. La cosa bella e' che questo e' gia' pronto per l'uso, senza alcun effort.

Vedi [questo video](https://www.youtube.com/watch?v=EMyysNJQpl8): in sostanza basta installarlo sul raspberry e poi avviarlo.

Al limite [qui](https://pimylifeup.com/raspberry-pi-openmediavault/) un link a un tutorial ben fatto che spiega come installare il software.

> Non testato... pero' sembra interessante. In ogni caso questo server solo come SMB, e non funge da Media Center


FASE OPERATIVA: COMANDI ESEGUITI
--------------------------------

### MOUNT MEMORIE USB

Anzitutto backup di `fstab`:

````bash
cp /etc/fstab /etc/fstab_`date +"%Y-%m-%d"`_original
````

Ecco qui alcuni comandi utilizzati e altri lanciati per realizzare quanto necessario:

````
apt install net-tools -> per avere ifconfig
fdisk -l -> trovo il media (anche con lsblk)
lsblk -f
lsblk -o +UUID
blkid -o value -s TYPE /dev/sdb2
mkdir /media/nas4
chown -R ubuntu:ubuntu /media/nas4/
mount /dev/sda2 /media/nas4/
````

poi per fare in modo che anche al reboot tutto funzioni, utilizzare:

Esempio di `/etc/fstab`
````
# My Nas
/dev/sda2	/media/nas4	vfat	defaults,uid=ubuntu,gid=ubuntu	0	0
/dev/sdb1	/media/nas3	vfat	defaults,uid=ubuntu,gid=ubuntu	0	0
````

dove `vfat` o `hfsplus` li ho trovati col comando tipo `blkid -o value -s TYPE /dev/sdb2`

Altro esempio di `/etc/fstab` piu' avanzato, in quanto

- prevede l'uso di un file di swap
- per il mount utilizza lo `UUID` che nel caso di memorie USB risulta piu' sicuro 
    (mi e' capitato che dopo aver spento il raspberry poi i volumni montati non combaciassero con la porta usb che mi aspettavo)

````
# Custom
/swapfile	none	swap	sw	0	0
# My Nas
UUID=0CD4-5C65	/media/nas4	vfat	defaults,uid=ubuntu,gid=ubuntu	0	0
UUID=832C-83A6	/media/nas3	vfat	defaults,uid=ubuntu,gid=ubuntu	0	0
UUID=61E9-D996	/media/nas2	vfat	defaults,uid=ubuntu,gid=ubuntu	0	0
````

> MEMORIA USB
>
> In caso vada formattata la memoria, ho usato [questa guida](https://www.redips.net/linux/create-fat32-usb-drive/).


Contestualmente e' utile anche predisporre dei cron:

````
### Server Test
* * * * *  root echo "$(date)" > /tmp/testcron-root

*/10 * * * *  root mount -a > /dev/null 2>&1
````


### SAMBA

A questo punto si puo' pensare di procedere anche con **SAMBA**, che server per rendere alcuni volumi locali accessibili via network
(ovvero renderli `volumi di rete`).

````bash
apt install -y samba
cp /etc/samba/smb.conf /etc/samba/smb_`date +"%Y-%m-%d"`.conf
````
Esempio di configurazione da inserire in `/etc/samba/smb.conf`

````
[raspy-nas-4]
    comment = Enomis Rapsy SMB
    path = /media/nas4
    read only = no
    browsable = yes
    writeable = yes #-> forse non serve...
````

Dove `raspy-nas-4` diventera' il nome che vedo nel file manager (browsing).

A questo punto posso avviare samba, e configurare la password d'accesso 
(sostanzialmente quando provo a montare il volume di network in locale, mi richiede "utente" e "password" per garantire che non tutti possano accedere alla risorsa di rete)

````bash
smbpasswd -a <username>
service smbd start/restart
````

In caso di problemi di network provare ad esempio

````bash
ufw allow samba #-> not used...
````

### DLNA SERVER 

Per essere tutto visibile da SMART TV, devo installare un server DLNA, ad esempio `mini-dlna`:  
[qui un tutorial](https://mauriziosiagri.wordpress.com/2012/11/18/minidlna-un-server-dlna-leggero-ed-efficace-in-ubuntu-linux/)
in base al quale ho poi dedotto quanto segue:

````bash
cp /etc/minidlna.conf /etc/minidlna_`date +"%Y-%m-%d"`.conf
ln -s /media/nas3/Anime /var/lib/minidlna/Anime
sudo service minidlna stop
````

Nel file di condivisione mi sono limitato a settare:

````
# Set custom media server name
friendly_name=MediaServer EnomisRaspy
# Enable simlinks usage
wide_links=yes
````


FINE TUNNING
============

Seguendo [questo buon tutorial](https://www.digitalocean.com/community/tutorials/how-to-add-swap-space-on-ubuntu-18-04)
in cui tratta:

- impiego `swapfile`
- `Adjusting the Swappiness Property`
- `Adjusting the Cache Pressure Setting`


DOCKER
======

Compilazione multi architetuttura: tutorial semplice e chiaro [qui](https://ludusrusso.cc/2018/06/29/docker-raspberrypi/).


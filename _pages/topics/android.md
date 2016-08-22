---
title:      Android
permalink:  /topics/android/
---

elenco di trick per android.




Terminale
----------

nel caso debba gestire il devide android dal PC, in particolare il terminale, ho due opzioni, sotto esposte.


#### usare adb shell

tramite l'sdk tolls:

1. Open Command prompt (assuming Windows,) navigate to where you installed the SDK tools (e.g. "C:\android-sdk\platform-tools\") and type in the following: `adb devices`
2. If all of the above was done successfully, a prompt should pop up asking you to allow connections from this computer. Accept the pairing request, and the prompt should show your phone's ID. If this doesn't happen - it usually means that the drivers aren't installed correctly.
3. Back at Command prompt, type in adb shell and press Enter, and you should be connected to the phone's terminal.

Testato, e funzia


#### usare un demone ssh

ovvero sshd. Vedi

http://forum.xda-developers.com/wiki/Guide:Using_the_Terminal



Monitor Device Android
-----------------------

Riferimento: http://code.tutsplus.com/tutorials/analyzing-android-network-traffic--mobile-10663


Basato su triplice accoppiamento:

* `wireshark - netcat` sul PC fisso
* `tcpdump - netcat` sul device android

To start Android Device Monitor (or):

* From Android Studio, choose Tools > Android > Android Device Monitor or click the Android Device Monitor   in the toolbar.
* From the command line, in the SDK tools/ directory, enter the following command:
monitor



#### copiare files

una volta entrati nella cartella `platform-tools` dell'android *SDK**, lanciare il comando

    `adb pull /data/local/tmp/out.pcap C:\Users\black\tcpdump`

per copiare il file `out.pcap` dal device alla destinazione locale; se invece voglio fare al contrario, andando dal PC al DEVICE, allora uso il comando `push`:

`adb push C:\Users\Jonathan\Desktop\video.mp4 /sdcard/`


### IMPORTANTISSIMO

1. Sul device android, dal suo terminale, digito

    `tcpdump -i wlan0 -s0 -w - | nc -l 12345`

> ATTENTO: in tutte le guide che ho letto prima della porta 12345, scelta a caso, viene messa l'opzione `-p`, ma stranamente sul device Tablet con questa opzione appena monitora un gruppo di pacchetti si chiude immediatamente... misteri della vita!!!

2. Sul pc, nella cartella di `platform-tools` dell' SDK android, dal terminale digito:

    `adb forward tcp:54321 tcp:12345 && nc 127.0.0.1 54321 | "C:\Program Files\Wireshark\Wireshark.exe" -k -S -i -`

Le porte sono state scelte a caso, pertanto l'accoppiamento 12345 e 54321 non significa nulla, e' solo di comodita'!!!

NBç oltre a questo metodo, che funzia, potrebbe essere interessante anche provare a seguire questa guida:

http://www.howtogeek.com/106191/5-killer-tricks-to-get-the-most-out-of-wireshark/

e questa per l'utilizzo generale di wireshark:

http://www.howtogeek.com/104278/how-to-use-wireshark-to-capture-filter-and-inspect-packets/


#### Altri Comandi

* `adb forward --list`, per vedere l'elenco di tutte le forward create
* `adb forward --remove-all`, per cancellare tutte le forward ivi impostate



#### Wireshark Torubleshoots

Quando uso wireshark ho il problema di monitor in `loopback`, ossia di quando provo a monitorare del traffico locale, come il `localhost`.

Per ovviare a questo rimpiazzo `WinPcap` con `NPcap`:
installando quest'ultimo devo spuntare sia la parte del `loopback` che quella del `wincap compatibility mode`

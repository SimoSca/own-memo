---
title:      Troubleshoots
permalink:  /windows/troubleshoots/
---

raccolgo alcuni problemi che possono presentarsi in `Windows`, rischiando di mandare in grande disperazione il mio essere!!


***

### Audio non funzionante

l'audio non funziona, e svolgendo le prove sul dispositivo appare un messaggio di sistema del tipo

````
dispositivo attualmente in uso da un altra applicazione
````

Per risolverlo (o almeno cosi' e' stato per me):

* schiaccia i tasti `Windows+R`
* nella finestrina che si apre digita `services.msc`
* nella finestra dei servizi windows trova il `servizio audio` e arrestalo poi  riavvialo (con clic destro)

tratto da [http://it.ccm.net/forum/affich-75725-l-audio-windows-8-non-funziona](http://it.ccm.net/forum/affich-75725-l-audio-windows-8-non-funziona)



### "Impossibile connettersi a un servizio di Windows. Impossibile connettersi al servizio Client di Criteri di gruppo."

non e' una soluzione definitiva: dopo aver seguito gli step che indichero' il problema non era stato completamente risolto, ma grazie a questi suggerimenti ho potuto riutilizzare Google Chrome, e successivamente al suo invio, una schermata Blue ha fatto spegnere il pc... ma dopo il riavvio tutto ha ripreso a funzionare normalmente!!!

> Ho letto che questo problema potrebbe essere dovuto a Google Chrome, pertanto eventualmente provare a disinstallarlo e riavviare il pc.

Passaggi:

Il malfunzionamento, in molti casi, sembra essere riconducibile al metodo con cui viene lanciato il Client di Criteri di gruppo (in inglese, Group Policy Client). Pare infatti che, se durante l’installazione degli aggiornamenti di Windows Update si verifica un blocco del computer, alcune voci del Registro di configurazione necessarie ad avviare questo servizio vengano perdute, rendendo impossibile il suo caricamento. Adottate quindi la seguente procedura:

1) lanciate l’editor del Registro di configurazione e raggiungete la posizione HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Services, qui verificate la presenza della chiave Gpsvc. Se trovate questa cartella il problema può essere risolto senza interventi radicali, se invece queste impostazioni sono del tutto assenti è probabile che si renda necessaria la reinstallazione del sistema operativo.

2) posizionatevi ora su HKEY_LOCAL_MACHINE\SOFTWARE\Microsoft\Windows NT\CurrentVersion\SVCHOST. Qui dovrebbe essere presente una chiave con valore multi-stringa etichettata GPSvcGroup, se questa chiave mancasse, provvedete a crearla e assegnategli il valore GPSvc.

3) verificate ora nella stessa posizione la presenza di una cartella etichettata GPSvcGroup, entrate al suo interno e accertatevi che contenga una chiave AuthenticationCapabilities alla quale deve essere assegnato il valore 0x00003020 (o 12320 decimale) e un’altra chiave CoInitializeSecurityParam alla quale deve corrispondere il valore 0x00000001, entrambi le chiavi sono di tipo Dword. Se la struttura appena descritta fosse assente, in tutto o in parte, provvedete alla creazione degli elementi mancanti.

4) chiudete l’editor per rendere permanenti le modifiche e riavviate il computer.

5) Extra, non testato: una volta riavviato aprire "Gestione Computer" e controllare che il servizio Goup Policy Client sia attivo.

Inoltre ho trovato, ma non testato, altre soluzioni:

- video :

[forum ](http://answers.microsoft.com/en-us/windows/forum/windows_7-performance/why-wont-windows-connect-to-the-group-policy/b73107f8-8447-4599-87a5-65ecc6a63aa0?page=2&auth=1)

[vieo 1](https://www.youtube.com/watch?feature=player_embedded&v=4m5KEmckWK4#t=115)

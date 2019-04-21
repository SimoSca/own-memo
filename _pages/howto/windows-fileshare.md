---
layout:     default
title:      Windows 10 FileShare
permalink:  /howto/windows10-fileshare/
---

Per condividere files si puo' usare `Windows 10` come **Samba Server**.

Nel mio caso essendo un patito di amministrazione dei sistemi ho svolto alcune migliorie:


##### Rinominare PC

Per rimonimare il pc (`Window`) per il network, al fine di renderlo fruibile e riconoscibile:

````
impostazioni sistema informazioni sul sistema > rinomina questo pc
````


Vedi [qui](https://www.cnet.com/how-to/how-to-change-your-computers-name-in-windows-10/)


##### Condividere Cartella

scegliere la cartella da condividere, poi tasto destro, `proprieta` e `condivisione`.



##### Finezza

dato che per l'accesso da network non volevo usare l'account d'amministratore (l'unico presente sul pc),
ho optato per creare un secondo account (sempre su `window`) da utilizzare come account gestionale.

Non ho fatto grandi lavori, mi sono limitato a seguire quanto scritto [qui](https://support.microsoft.com/it-it/help/4026923/windows-10-create-a-local-user-or-administrator-account)

e poi nel caso voglia accedervi per altre cose ho aggiunto pin come scritto [qui](https://www.howtogeek.com/232557/how-to-add-a-pin-to-your-account-in-windows-10/).

> NOTA: naturalmente poi sono tornato sulla cartella di condivisione e l'ho condivisa con questo utente appena creato.


##### Connessione!

Ora apro il `Finder` del mio `MacBook`, poi in basso a sinistra:

````
locations > network > <pc server samba windows>
````

doppio click, `connect as` (in alto a destra) e infine accedo utilizzando le credenziali dell'utente che desidero (nel mio caso quello creato per _"finezza"_).


**That's it!!!**
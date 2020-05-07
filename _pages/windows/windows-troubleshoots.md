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

[video 1](https://www.youtube.com/watch?feature=player_embedded&v=4m5KEmckWK4#t=115)



### INTERNET

#### Internet connesso ma browser non funzionante

Vedi: [http://ccm.net/forum/affich-864669-internet-connected-but-browsers-not-working-windows-10](http://ccm.net/forum/affich-864669-internet-connected-but-browsers-not-working-windows-10)

In sostanza:

- Start --> In run type cmd (as administrator) --> Then "CMD" will be displayed --> 
- `$ netsh int ip reset resetlog.txt`
- Reboot your computer



### Google Chrome

#### browser chrome risulta essere gestito da un organizzazione (managed by orgnization)

Nel mio caso e' capitato dopo un'update di AVG durante il quale e' stata installata l'estensione di AVG per Chrome.

Anzitutto cercando "Chrome Who is my administrator" sono atterrato su una pagina che sostanzialmente confermava che il mio account effettivamente non era soggetto a nessuna organizzazione.

Successivamente in `chrome://policy` nella sezione `Chrome Policies` ho trovato il criterio `QuicAllowed` che prima non esisteva.

**Primo Tentavivo**

A quel punto temendo che si trattasse di un malware ho fatto qualche ricerca e ho trovato 
questo lungo thread [https://www.ilsoftware.it/articoli.asp?tag=Gestito-dalla-tua-organizzazione-in-Chrome-potrebbe-trattarsi-di-un-malware_19055](https://www.ilsoftware.it/articoli.asp?tag=Gestito-dalla-tua-organizzazione-in-Chrome-potrebbe-trattarsi-di-un-malware_19055). 
Grazie a esso ho risolto secondo quanto segue:

con `regedit` ho rimosso la chiave `HKEY_LOCAL_MACHINE\Software\Policies\Google\Chrome` e dopo aver riavviato il pc tutto e' tornato funzionante.

> Precedentemente avevo rimosso delle estensioni installate e sospette, tra cui anche quella di `AVG` (non sospetta, ma prbabilmente e' lei la colpevole).
> Ho letto che anche `Avira` puo' giocare questo scherzo.

**Secondo Tentativo**

Inizialmente quanto fatto prima sembrava funzionare ma dopo aver completamente spento e riacceso il computer (e non riavviato e basta),
e' riapparsa nuovamente la chiave di registro eliminata. Cosi' dopo qualche ricerca specifica in imerito a `QuicAllowed`, ho trovato questo 
[https://support.avg.com/answers?id=9060N000000gNvAQAU](https://support.avg.com/answers?id=9060N000000gNvAQAU)
e quindi ho provato anzitutto a rimuovere il flag da AVG: `menu > impostazioni > protezione di base > protezione web > rimosso flag "Abilita scansione QUIC/HTTP3"`; 
successivamente ho spento, riacceso e magicamente quel flag non appariva piu' nelle policy! (tuttavia la chiave di registro e' ancora presente). 

Eventualmente avrei riprovato a eliminare nuovamente la chiave `HKEY_LOCAL_MACHINE\Software\Policies\Google\Chrome`, verificare che AVG non avesse piu' quel flag e riavviato nuovamente il PC.

> NOTA: non e' detto che sia obbligatorio disabilitare quel flag, infatti il messaggio di chrome in se non indica una vulnerabilita'. 


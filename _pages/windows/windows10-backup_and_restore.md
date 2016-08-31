---
title:      Backup e Restore
permalink:  /windows/install-backup-restore/
---

scopo di questo file e' illustrare l'iter di installazione di molti programmi: piu esattamente si tratta di RE-INSTALLAZIO, ovvero ricreare velocemente le configurazioni di specifici programmi.

Le sorgenti sono principalmente associati ai programmi associati alle directory presenti in "Z:\Root\ProgramsConfig": ciascuna potrebbe contenere uno specifico `simone.md` o `readme.md` in cui ho scritto espliticamente l'iter da svolgere per il backup e successivo restore.


Backup
======



OPERAZIONI MANUALI
-------------------

### GUI

Cartelle con omonimi Stored in "Z:\Root\..." e backup automatico tramite freefile sync e windows task:

>>NB: il backup avviene usando gli omonimi in C: come sorgente e quelli in Z:\root come target:

- Desktop/UtilityRun
- Musica
- user\black\Projects
- user\black\ProgramsConfig
- xampp (target in ProgramsConfig dentro a Z:)
- ProgramsCRoot sincronizza alcune directory del path C:\ (ad esempio ffmpeg,cygwin e putty)

Inoltre per comodita' con freefilesync e' possibile richiamare la sincronizzazione, ma ATTENZIONE alla source e al target!




OPERAZIONI SEMIAUTOMATICHE
---------------------------

### copie a specchio:

essendo perfettamente a specchio, uso rsync (mediante cygwin).
Lancio lo script "C:\Users\black\Projects\Batch\windowsBackupRsync.bat", dove lancio comandi del tipo

    rsync -av "/cygdrive/c/Program Files (x86)/Steam/Backups/" "/cygdrive/z/Root/ProgramsConfig/Steam/Backups"

e nello specifico sincronizzo:

    /Program Files (x86)/Steam/Backups/
    /Program Files (x86)/Steam/steamapps/
    /Users/black/AppData/Roaming/Mozilla/Firefox/Profiles/
    /Users/black/AppData/Roaming/Thunderbird/Profiles/
    /Users/black/AppData/Roaming/Sublime Text 3/Packages/Users/black/AppData/Local/Google/Chrome
    /Users/black/AppData/Roaming/FileZilla/


### Sync

cartelle automaticamente sincronizzate mediante Dropbox

- Maintenance/Sublime_text a Z:\root\programsconfig\sublimetext


### Collegamenti

con originali Stored in "Z:\Root\..."

- Video
- Immagini






Installazione/Restore
=====================


OPERAZIONI MANUALI
-------------------

Basta seguire l'apposito file .txt o .md nell'omonima directory:


- JDownloader2

- SourceTree

OPERAZIONI SEMIAUTOMATICHE
---------------------------

Restore che avvengono mediante risincronizzazione di directory:

- Thunderbird: copio il contenuto di "Z:\Root\ProgramsConfig\MozillaThunderbird\profileToRestore.profile"  nella directory .profile gia' presente in "C:\Users\black\AppData\Roaming\Thunderbird\Profiles\"

- Firefox: copio il contenuto di "Z:\Root\ProgramsConfig\Firefox\profileBackup.default" in  "C:\Users\black\AppData\Roaming\Mozilla\Firefox\Profiles\<string>.default"

- Sublime Text 3: copio il contenuto di "Z:\Root\ProgramConfig\SublimeText\Sublime Text 3\Packages" in "C:\Users\black\AppData\Roaming\Sublime Text 3\Packages"

- FileZilla Client: copio solo il file `sitemanager.xml` da "Z:\Root\ProgramsConfig\FileZilla" a "C:\Users\black\AppData\Roaming\FileZilla"


- vedere google chrome, anche se non l'ho testato

EXTRA
-------

- Dropbox: ricordarsi di cambiare la directory da quella locale in quella presente dentro a Z:\

- Copy: cambio la directory spostandola in Z:\Copy nuclear.quantum@gmail.com, ma prima rinomino la vecchia directory se gia presente, altrimenti per ciascun file crea una copia, in quanto vanno in conflitto (la prima volta di default prova a eseguire il download da remoto)

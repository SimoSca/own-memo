---
title:      Tools
permalink:  /developer/tools/
---

Di tools ve ne sono tanti: server, strumenti per watch, database, ide, etc...  
per non rendere tutto dispersivo, elenco solo quelli principali e che per un motivo
o per un altro ho scelto di non inserire nella sezione developer.


Vagrant
--------------

avere skills su vagrant e' fondamentale se voglio avere un lavoro portabile e condivisibile con altri sviluppatori.

La documentazione ufficiale di [Vagrant](https://www.vagrantup.com/docs) e' fatta benissimo, pertanto non voglio estendere troppo questo argomento,
ma reputo utile spiegare quali sezioni attualmente sono importanti:

- `provisioning > chef-solo`  , come strumento di automazione ( al posto di shell script manuale )
- `synced folders`
- `networking` , almeno il forwarding per sviluppo in locale
- `vagrant share` , mi basta anche `getting started > share` per pubblicare temporaneamente online
- `multi-machine` , per runnare un cluster, oppure avere due macchine separate: un web host su una e database separato sull'altra
(questo aiuta a capire l'importanza della scalabilita' dei servizi)


Dei plguin utili risultano essere

- [berkshelf](http://berkshelf.com/) , per l'automazione con `Chef` (scaricare il client di `Chef` nominato: `ChefDK`)
- `hostmanager` , receip per automatismi sull'host
- `omnibus` , per controllare la versione di `Chef` installata

**Chef**

Consiglio un ripasso leggendo [https://docs.chef.io/chef_overview.html](https://docs.chef.io/chef_overview.html)

Per capirci, a livello lessicale gioca sul concetto di cuginare:  il libro di cucina (`cookbook`) e' un insieme di ricette (`receip`),
cosi' come un sistema automatizzato funzionante e' un insieme di singole utility;
in pratica il `cookbook` e' un insieme eterogeneo di `receip`.
 Ad esempio potrei avere il ricettario `server`, composto da 2 automatismi: uno per installare `apache` (con gia' configurazioni personalizzate)
 e l'altro per installare `nodejs` con server configurato.

la piattaforma di automazione consta di piu parti che schematizzo velocemente

- `workstation`, tipicamente il pc sul quale lavora chi sviluppa il server, e che contiene un software in grado di svolgere operazioni di controllo e push dei dati su di esso
- `server` che mi funziona da cloud: chi sviluppa salva li tutti i suoi dati per renderli disponibili al mondo (tipo repository git)
- `nodes` qualsiasi macchina che sfrutta chef e che ne viene automatizzata
- `client` il software che reperisce i dati del server, e che opera su un nodo

Il [supermakelet chef](https://supermarket.chef.io/) e' dove vengono raccolti tutti i ricettari.

La cosa interessante e' che e' gestibile con `Ruby`


**Tutorial**

- [https://scotch.io/tutorials/get-vagrant-up-and-running-in-no-time](https://scotch.io/tutorials/get-vagrant-up-and-running-in-no-time), ma ormai e' datato.

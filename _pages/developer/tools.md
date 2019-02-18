---
title:      Tools
permalink:  /developer/tools/
---

Di tools ve ne sono tanti: server, strumenti per watch, database, ide, etc...  
per non rendere tutto dispersivo, elenco solo quelli principali e che per un motivo
o per un altro ho scelto di non inserire nella sezione developer.


Virtualbox
-----------

La maggior parte del lavoro e' nettamente piu comodo svolgerla con `Vagrant`. In ogni caso per quanto concerne la parte GUI, ho riscontrato dei problemi con `osx` e li ho risolti grazie a questo articolo

[increase-mac-os-virtual-machine-screen-resolution-virtualbox-vmware-player](http://www.sysprobs.com/increase-mac-os-virtual-machine-screen-resolution-virtualbox-vmware-player)

dove nello specifico ho inserito

````
VBoxManage.exe setextradata "vagrant-box-osx_default_1472223119403_66496" VBoxInternal2/EfiGopMode 5
````


Vagrant
-------

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
- `vbguest`, to autoupdate virtualbox guest additions

#### Confronto tra provisioning

- [https://www.openstack.org/summit/tokyo-2015/videos/presentation/chef-vs-puppet-vs-ansible-vs-salt-whats-best-for-deploying-and-managing-openstack](https://www.openstack.org/summit/tokyo-2015/videos/presentation/chef-vs-puppet-vs-ansible-vs-salt-whats-best-for-deploying-and-managing-openstack)
- [http://blog.takipi.com/deployment-management-tools-chef-vs-puppet-vs-ansible-vs-saltstack-vs-fabric/](http://blog.takipi.com/deployment-management-tools-chef-vs-puppet-vs-ansible-vs-saltstack-vs-fabric/)


In generale tenere presente che questi sistemi aiutano anche a gestire Cluster o Gruppi di Macchine con una che funge da Server(amministratore), pertanto risultano assai complessi e non e' bene studiarli, se si vogliono solo integrare con vagrant.

In ogni caso un operazione molto bella che alcuni di loro svolgono e' il `push`, per fare in modo che l'amministratore aggiorni in automatico i sottoserver: questa ad esempio potrebbe essere utile per me, usando il server di sviluppo/stage per testare il codice e una volta avuto l'ok svolgere un aggiornamento (push) sul server stabile; in questo caso il provisioning mi aiuterebbe perche' tutti i comandi li salverei su files che poi versionerei con Git.


#### Chef

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

**CONSIGLIO**
questo tutorial e' il migliore per iniziare!!

- [vagrant-is-easy-chef-is-hard-part2](https://adamcod.es/2013/01/15/vagrant-is-easy-chef-is-hard-part2.html)


#### Ansible

vedi [vagrant-ansible-quickstart-tutorial](https://adamcod.es/2014/09/23/vagrant-ansible-quickstart-tutorial.html)

Visto che l'ho provato per il `Mac` ed effettivamente e' piuttosto semplice, almeno a livello base, posso suggerire queste letture:

- [an-ansible-tutorial](https://serversforhackers.com/an-ansible-tutorial) , la migliore per carburare
- [automating-development-environment-ansible](http://www.nickhammond.com/automating-development-environment-ansible/) , offre un esempio completo in un singolo file
- [how-to-create-ansible-playbooks-to-automate-system-configuration-on-ubuntu](https://www.digitalocean.com/community/tutorials/how-to-create-ansible-playbooks-to-automate-system-configuration-on-ubuntu) , spiega qualcosa sul comando `register`, ma e' di poco conto



### Boxes

Box interessanti:

- [https://github.com/AndrewDryga/vagrant-box-osx](https://github.com/AndrewDryga/vagrant-box-osx) NB: molta attenzione alle note legali sulla concessione del servizio. Il qui presente declina ogni responsabilita' da un impiego errato di questo link.

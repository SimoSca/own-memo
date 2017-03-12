ANSIBLE
========

`Ansible` non solo funzia per il provisioning, ma anche per il controllo remoto di gruppi di hosts.

La cosa bella e' che usa ssh, quindi in linea di principio basta installare il software su una macchina `Manager` 
e da li lanciare opportunamente i comandi!

Inoltre supporta virtualizzazione con `vagrant` o containers con `docker`.

Penso possa reputarsi valido per progetti semplici.



Tratto da documentazione di Ansible:



Install
-------

````
$ sudo apt-get install software-properties-common
$ sudo apt-add-repository ppa:ansible/ansible
$ sudo apt-get update
$ sudo apt-get install ansible
````


HOSTS
-----

file in `/etc/ansible/hosts` e configurare come preferisco, ad esempio: (vedi [qui](http://docs.ansible.com/ansible/intro_inventory.html))

````
enkatanazza ansible_host=xxx.46.78.xxx

[myvh]
xxx.46.78.xxx
; oppure in versione statica


; "targests" is a special ansible group
[targets]
localhost       ansible_connection=local
enkatanazza     ansible_connection=ssh        ansible_user=superboss
````


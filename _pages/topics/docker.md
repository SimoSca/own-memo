---
title:      Docker
permalink:  /topics/docker/
---


Escludendo tutte le basi nozionistiche su docker (`entrypoint`, `cmd`, `Dockerfile`, etc.),
riporto alcune fonti che tenere presente non fa mai male!


#### GESTIONE PERMESSI

Questo e' molto importante perche' a discapito di quello che avviene per `Docker Desktop`, 
in cui la `Virtual Machine` provvede automaticamente ad aggiustare i permessi in base all'utente che sta eseguendo la shell,
sui classici server linux questo non avviene, e quindi ci si ritrova a dover scegliere varie strategie per gestire questa problematica.

Un articolo mooolto buono da leggere e' il seguente:

- [https://jtreminio.com/blog/running-docker-containers-as-current-host-user/#ok-so-what-actually-works](https://jtreminio.com/blog/running-docker-containers-as-current-host-user/#ok-so-what-actually-works)


#### Generic Build

Build con overrides + kubernetes:

- [https://jean85.github.io/slides/2018-09-symfony-docker-pugmi/#/5/3](https://jean85.github.io/slides/2018-09-symfony-docker-pugmi/#/5/3)



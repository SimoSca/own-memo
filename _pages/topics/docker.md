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


#### Processo Principale PID 1

I container vengono eseguiti con un **processo principale** con **ID 1**.

Questo e' importante sopratutto per quando riguarda la procedura di `shut down` del container.

Un punto di notevole importanza in cui questo entra in gioco e' nella modalita' di `entrypoint` che viene utilizzata
(`exec` vs `shell`).

Per dettagli:

- [gracefully-stopping-docker-containers](https://www.ctl.io/developers/blog/post/gracefully-stopping-docker-containers/), 
spiega come avviene il flow dei segnali di spegnimento di docker. Ha anche un paio di esempi utili a capire.

- [trapping-signals-in-docker-containers](https://medium.com/@gchudnov/trapping-signals-in-docker-containers-7a57fdda7d86)

- [working with signals](https://www.linuxjournal.com/article/10815), per capire come funzionano i segnali di sistema

Suggerisco inoltre di guardare i vari link suggeriti in queste immagini:

- [https://github.com/phusion/baseimage-docker](https://github.com/phusion/baseimage-docker)

- [https://github.com/krallin/tini](https://github.com/krallin/tini)

Non direttamente correlato, ma molto utile:

- [whats-the-difference-between-eval-and-exec](https://unix.stackexchange.com/questions/296838/whats-the-difference-between-eval-and-exec), 
in questo thread e' presente un ottimo esempio in cui mostra i PID


TOOLS
-----

- [dive](https://github.com/wagoodman/dive), per "navigare" dentro alle immagini
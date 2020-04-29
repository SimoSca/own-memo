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

Sempre relativamente a questo punto, vedere **users map** (nota anche come __users nampespaces__):

- [https://www.linux.com/blog/learn/2017/8/hardening-docker-hosts-user-namespaces](https://www.linux.com/blog/learn/2017/8/hardening-docker-hosts-user-namespaces)


#### Logging

In Docker risulta utile capire come avviene il log in stdout o stderr del main process del container, per eventualmente aggiungere dei logs che possono tornare comodi.

Il container in genere looga in `/proc/1/fd/1` e `/proc/1/fd/2`, mente ad esempio `/dev/stdout` punta a `/proc/self/fd/1` e quindi potrebbe non andar bene per loggare nel processo principale.

Per dettagli leggere questo bellissimo articolo che analizza in dettaglio i file descriptors e le pipe di PHP-FPM: 

[https://rtfm.co.ua/en/linux-php-fpm-docker-stdout-and-stderr-no-an-applications-error-logs/](https://rtfm.co.ua/en/linux-php-fpm-docker-stdout-and-stderr-no-an-applications-error-logs/).


#### Permissions/Users

Per la gestione dei comandi del processo principale di docker con specifici utenti possono tornare utili `su-exec` (alpine) e `gosu` (linux).

Qui un esempio con `su-exec` per php-fpm: 

[https://medium.com/@callback.insanity/forwarding-nginx-logs-to-docker-3bb6283a207](https://medium.com/@callback.insanity/forwarding-nginx-logs-to-docker-3bb6283a207).

e qui un esempio concreto su come creare un link allo stdout e stderr del main process:

[https://github.com/webdevops/Dockerfile/blob/c4a5b7f22cdce0e33ac87a6e146d56549f528d43/docker/base/ubuntu-18.04/conf/bin/config.sh#L21](https://github.com/webdevops/Dockerfile/blob/c4a5b7f22cdce0e33ac87a6e146d56549f528d43/docker/base/ubuntu-18.04/conf/bin/config.sh#L21)


#### Generic Build

Build con overrides + kubernetes:

- [https://jean85.github.io/slides/2018-09-symfony-docker-pugmi/#/5/3](https://jean85.github.io/slides/2018-09-symfony-docker-pugmi/#/5/3)


#### Named Volume

Come avere un volume comune su piu' container nello stesso docker-compose 
senza bisogno di scrivere esplicitamente il path di mount in ciascun service: 

- [http://blog.code4hire.com/2018/06/define-named-volume-with-host-mount-in-the-docker-compose-file/](http://blog.code4hire.com/2018/06/define-named-volume-with-host-mount-in-the-docker-compose-file/)


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


#### Execute Remote SOCKET

There's two methods:

- [https://medium.com/better-programming/docker-tips-access-the-docker-daemon-via-ssh-97cd6b44a53](https://medium.com/better-programming/docker-tips-access-the-docker-daemon-via-ssh-97cd6b44a53), for newest docker versions

- [https://medium.com/@dperny/forwarding-the-docker-socket-over-ssh-e6567cfab160](https://medium.com/@dperny/forwarding-the-docker-socket-over-ssh-e6567cfab160): `ssh -nNT -L $(pwd)/docker.sock:/var/run/docker.sock user@someremote` + `export DOCKER_HOST=$(pwd)/docker.sock
` (or `docker -H $(pwd)/docker.sock <docker commands>`)


### Create/Push Docker images to private registry

See this to implement simple flow:

- [https://dev.to/imichael/automagically-build-and-push-docker-images-to-a-registry-using-gitlab-276p](https://dev.to/imichael/automagically-build-and-push-docker-images-to-a-registry-using-gitlab-276p)



TOOLS
-----

- [dive](https://github.com/wagoodman/dive), per "navigare" dentro alle immagini



BUILD IMAGES
============

Per la build/gestione dell'immagine sono interessanti i seguenti strumenti:

- [gosu](https://github.com/tianon/gosu)

- [go-replace](https://github.com/webdevops/go-replace)

Inoltre a titolo culturale, sapere anche come docker risolve le build con [Build Kit](https://blog.mobyproject.org/introducing-buildkit-17e056cc5317)

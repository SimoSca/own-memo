---
layout:     default
title:      Php-FPM
permalink:  /topics/php-fpm/
---

### PHP FPM

- [https://serversforhackers.com/video/php-fpm-process-management](https://serversforhackers.com/video/php-fpm-process-management)

Qui un esempio con `su-exec` per php-fpm: 

[https://medium.com/@callback.insanity/forwarding-nginx-logs-to-docker-3bb6283a207](https://medium.com/@callback.insanity/forwarding-nginx-logs-to-docker-3bb6283a207).

e qui un esempio concreto su come creare un link allo stdout e stderr del main process:

[https://github.com/webdevops/Dockerfile/blob/c4a5b7f22cdce0e33ac87a6e146d56549f528d43/docker/base/ubuntu-18.04/conf/bin/config.sh#L21](https://github.com/webdevops/Dockerfile/blob/c4a5b7f22cdce0e33ac87a6e146d56549f528d43/docker/base/ubuntu-18.04/conf/bin/config.sh#L21)

### Details

Qui alcuni dettagli su una parte non sempre chiarissima,
ovvero della disinzione tra le impostazioni dei parametri presenti nel `php.ini` e che vorremmo overridare per progetto, ovvero:

- `php_admin_value[...]` , nel file del pool fpm
- `phpvalue[...]` , nel file del pool fpm
- setup delle impostazioni mediante file .user.ini all'interno delle directory di progetto

In particolare ricrdo che se si utilizza `apache` con `php-fpm` **non e' possibile** inserire le configurazioni nel file `.htaccess`,
in quanto valido solamente con l'estenzione `apache-php` di apache.

ad esempio in `.htaccess` inserire `php_value  upload_max_filesize  10M` portera' ad un errore se su `apache` si usa `fastcgi`,
poiche' esso e' ammesso solo con `mod_php`.

Qui invece il dettaglio sulle modalita' di override nel file `*.conf` del pool fpm e di come vengono interpretati rispetto al `local` e `master`:

````
; NOTA: questo parametro funziona, ma bisogna capire come conteggia il timing
;request_terminate_timeout = 40

php_admin_value[post_max_size] = 30M
php_admin_value[upload_max_filesize] = 30M

;; se impostato, allora diviene sia il local che il master (vedi phpinfo())
;php_admin_value[max_execution_time] = 13

; se non e' impostato php_admin_value[max_execution_time]  allora questo diviene il "master" ma e' overridable ad esempio con ini_set('max_execution_time', '19'); -> 19 local o sempre come local se il parametro e' impostato nellon .user.ini.
; se nulla e' impostato tranne questo, allora 11 diviene il valore sia local che master
;php_value[max_execution_time] = 11

; nota: se impostato max_execution_time  in  .user.ini e non settato php_admin_value o forza un ini_set('max_execution_time', '19'); , lui, allora il valore dello .user.ini diviene il local

````


se php-fpm request_terminate_timeout e' raggiunto ottengo il messaggio <title>503 Service Unavailable</title>
se Timeout in Virtualhost allora ottengo il messaggio <title>504 Gateway Timeout</title>
<head><title>504 Gateway Time-out</title></head>
grep -Rl "504 Gateway Time-out" -> e' il load balancer!!!


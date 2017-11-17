---
layout:     default
title:      Shell Script
permalink:  /topics/shell-script/
---


Raccolta di articoli e suggerimenti relativi allo shell script.



#### LOCK FILE - PID FILE

Considerazioni e esempi per l'utilizzo di lock sugli script 
(per evitare che piu' istanze dello stesso vengano eseguite contemporaneamente):

- [pratico, con due esempi semplici](https://dmorgan.info/posts/linux-lock-files/)
- [Tutorial semplice ma completo con spiegazioni dettagliate](http://www.kfirlavi.com/blog/2012/11/06/elegant-locking-of-bash-program/)
- [esempi su lock/pid shared/exclusive](https://loonytek.com/2015/01/15/advisory-file-locking-differences-between-posix-and-bsd-locks/)
- [spiegazione sui PID](http://bencane.com/2015/09/22/preventing-duplicate-cron-job-executions/)

Ora la domanda: a cosa serve lo `shared lock`?

l' `exclusive` puo' servire per essere sicuro di non runnare lo stesso script piu' volte, 
mentre lo shared puo' essere utilizzato:

- per "lockare" piu' script (non necessariamente lo stesso), e quindi con un `lsof` vedere quanti processi lo stanno bloccando.
    Un semplice esempio che mi viene in mente e' quello di un applicazione che magari lancia piu' processi, e io voglio monitorare quanti processi l'applicazione sta eseguendo.
    
- per operazioni di lettura, ad esempio in C++

Spiego meglio l'ultimo: se ho piu' processi che devono leggere una certa fonte, allora posso usare lo `shared` perche' tanto in lettura tutti i processi possono leggere la medesima fonte,
se poi un processo tenta di modificare la fonte, ovviamente non puo' farlo mentre gli altri processi la stanno leggendo.

In sostanza posso fare l'analogia con la lettura/scrittura su un **DB**, ed e' per questo che in genere avviene l'associazione:

- shared <-> read   
- exclusive <-> write   
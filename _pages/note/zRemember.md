---
title:      Remember
permalink:  /note/zremember/
---

Tante piccole cose da ricordare... che sia un ToDo o una semplice banca dati.


#### Ampache

semplice piattaforma per streaming PHP: e' locale, pertanto la posso installare al pari di Elgg, Wordpress e via dicendo.
Puo' essere un'alternativa personalizzabile a Windows Media Player o simili.


#### sejda

http://www.sejda.org/

terminale per la gestione dei pdf. Potrebbe essere un extra rispetto alla gestione dei pdf mediante le Gemme di Ruby (spawn e combine_pdf).


#### cryptografy

* **block chain**, usato come metodo per crittografia bitcoin
* **mix network**, successione di nodi crittografici asimmetrici, ciascuno dei quali sul medesimo messaggio aggiunge/rimuove un layer crittografico

#### Sicurezza

attacchi tipici

* **csfr** , per siti con persistent state, ovvero siti che usano salvare sessioni degli utenti: le sessioni sono automaticamente recuperate tramite cookie, pertanto aprendo un link malevolo, questi potrebbe reindirizzarmi ad un sito tipo paypal con url parameters che mi possono far svolgere azioni inaspettate.

* **parameter tampering** , se cambio il valore di un parametro nell'url o in un form data potrei vedere dati che non mi appartengono. Per esempio se accedo al pannello si un utente mediante `site.com/panel?id=35`, allora impostando `id=37` allora potrei vedere il pannello dell'utente con id 37. Naturalmente mediante un metodo semplice sarebbe controllare, come faccio in elgg, che l'utente che sta svolgendo la richiesta sia effettivamente l'utente 37. Un altro metodo e' quello di aggiungere alla pagina un parametro input, diciamo AuthCODE e salvarlo nel DB: in questo modo quando mi arrivano i form data paramter, oltre al controllo sull'id posso verificare anche che AuthCODE sia effettivamente corrispondente a quello user. Naturalmente ogni volta dovrei cambiare quel codice, per essere sicuro di non avere grossi problemi.


#### Royalties free

vedi http://blog.html.it/10/06/2016/file-audio-royalties-free-le-risorse/?utm_source=newsletter&utm_medium=email&utm_campaign=Newsletter:+HTML.it&utm_content=14-06-2016+file-audio-royalties-free-le-risorse

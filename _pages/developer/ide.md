---
title:      Ide
permalink:  /developer/ide/
---


Raccolta di nozioni per i miei Ide preferiti.


NetBeans
---------

### Template/Moduli/Progetti

NetBeans di base e' completo, ma vi sono alcune parti non semplici da gestire,
come ad esempio il fatto che non vi e' un `project type` per semplici folder,
ovvero il fatto di dover sempre implementare un template complicato per importare un semplice folder come progetto.

Fortunatamente NetBeans e' altamente configurabile sia nella GUI che nei servizi,
risulta pertanto possibile creare `moduli` e `netbean application` per sfruttare al massimo questo ide.

Di seguito raccolgo i link utili a tal fine:

- [nbm-projecttype-last](https://platform.netbeans.org/tutorials/nbm-projecttype.html) , il migliore per partire
- [nbm-projecttype](https://platform.netbeans.org/tutorials/71/nbm-projecttype.html)
- [hack-your-own-custom-project-t](https://dzone.com/articles/hack-your-own-custom-project-t)
- [nbm-quick-start](https://platform.netbeans.org/tutorials/nbm-quick-start.html)
- [platform](https://netbeans.org/kb/trails/platform.html)
- [platform-features](https://netbeans.org/features/platform/features.html)
- [import-3rd-party](https://netbeans.org/kb/articles/freeform-import.html)

Predisposizione typescript:

- [https://jaxenter.com/typescript-angular-2-and-netbeans-ide-an-unbeatable-trio-125443.html](https://jaxenter.com/typescript-angular-2-and-netbeans-ide-an-unbeatable-trio-125443.html)


Sublime Text 3
---------------

vedi [https://www.sitepoint.com/essential-sublime-text-javascript-plugins/](https://www.sitepoint.com/essential-sublime-text-javascript-plugins/)



PhpStorm
-----------------

inizialmente ci ho messo un po per farlo funzionare con `XDebug`, utilizzando una `Vagrant VM` come server,
pertanto riporto di seguito i passaggi piu importanti, senza giustificarli:


### File > Settings

#### sezione Languages & Frameworks

- in `PHP`  creo un nuovo interpete di tipo `Vagrant` e lo faccio puntare alla directory della mia `VM`

- in `PHP > Debug` alla voce `Xdebug` inserisco la porta 9000 e spunto tutto in quella sezione

#### sezione Build, Execution, Deployment

- in `debugger` sotto la voce `Built-in server` inserisco la porta .... (che non sia quella che uso per accedere al server della `VM`)

- in `Deployment` creo un nuovo item, e poi per questo imposto:

`Connection`
	- `Type` : `Local or mounted folder`
	- `upload/download` , `Folder` : inserisco il folder del mio progetto
	- `web server root Url`: l'url per raggiungere il main del mio progetto (ad es `http://localhost:8888/joomlagram`)

`Mappings`
	- `Local Path`: sempre la root
	-poi solo slash e backslash

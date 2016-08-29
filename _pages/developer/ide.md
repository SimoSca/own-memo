---
title:      Ide
permalink:  /developer/ide/
---


Raccolta di nozioni per i miei Ide preferiti.

Temi:

in generale dei color scheme di mio gradimento sono:

- `Obsidian`
- `DarkRoomContrast`


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

Mie configurazioni:

````
{
	"color_scheme": "Packages/User/Solarized (Dark) (SL).tmTheme",
	"default_line_ending": "unix",
	"font_size": 8,
	"ignored_packages":
	[
		"Markdown"
	],
	"show_panel_on_build": false,
	"tab_size": 4,
	"theme": "Soda SolarizedDark.sublime-theme"
}
````

Coliri zona testo:

plugin `ColorSublime` che consente di switchare velocemente il tema per avere un riscontro immediato (come `ScrollColor` di `Vim`), e installa anche una serie di temi di default.


vedi [https://www.sitepoint.com/essential-sublime-text-javascript-plugins/](https://www.sitepoint.com/essential-sublime-text-javascript-plugins/)

Temi installati (presi direttamente dal package control.io, sezione temi)

- `https://packagecontrol.io/packages/Theme%20-%20Freesia` , con text `Kalopsia dark` o `capo dark` o `DarkRoomContrast`
- `https://packagecontrol.io/packages/Theme%20-%20DefaultPlus` , non installato
- `https://packagecontrol.io/packages/Theme%20-%20Aprosopo` , interessante ma non installato
- `https://packagecontrol.io/packages/Theme%20-%20Afterglow` , very easy



PhpStorm
-----------------

`XDebug` e `PHPStorm` comunicano in due modi:

- direttamente, mediante una chiamata esplicita di PHPStorm (premendo il pulsante del BUG, inteso come scarafaggio)
- mettendo PHPStorm in ascolto di una connessione diretta con XDebug

> per entrambe e' necessario recarsi in  `File > Settings`


### Direttamente

Inizialmente ci ho messo un po per farlo funzionare con `XDebug`, utilizzando una `Vagrant VM` come server,
pertanto riporto di seguito i passaggi piu importanti, senza giustificarli:


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


### A CONNESSIONE

in

`Languages & Frameworks > PHP > Debug > DBGp Proxy`

imposto:

- `IDE key` : ad esempio `PHPStorm`
- `Host` : `localhost:<myPort>`
- `Prt`: ho lasciato la default `9001`

in questo modo mi basta cliccare sul simbolo della cornetta dell'editor per aprire una connessione, e se voglio utilizzarlo mi basta appendere la query string:

````
http://localhost:30003/joomlagram/?XDEBUG_SESSION_START=PHPStorm
````

Cosi' facendo questa funziona sempre, e rirunna tutto anche quando uso `broser-sync` come `proxy`.

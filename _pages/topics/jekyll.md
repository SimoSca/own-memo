---
title:      Jekyll Templating
permalink:  /topics/jekyll/
---



Github Pages
------------

Sostanzialmente `jekyll` l'ho adottato per la sua praticita' con `Gihub`, pertanto segno i links piu importanti:

- [build-blog-jekyll-github-pages](https://www.smashingmagazine.com/2014/08/build-blog-jekyll-github-pages/), utile per iniziare

- [jekyll-github-pages-and-cloudflare-for-pagespeed-win](https://scotch.io/tutorials/jekyll-github-pages-and-cloudflare-for-pagespeed-win), come startup da prendere con le pinze

- [get-started](https://24ways.org/2013/get-started-with-github-pages/), utile ma non aggiornatissimo

- [jekyllbootstrap](http://jekyllbootstrap.com/), potrebbe tornare utile, ma e' datato



Commands
--------

Comandi base:

- `gem install github-pages` , per aggiungere un plugin utile. Potrei anche salvarlo direttamente dentro al file [Gemfile](../Gemfile)

- `jekyll serve --watch` , per monitorare localmente

- `jekyll serve --drafts` , per vedere il cestino

- `bundle show minima` , se voglio trovare il path di un template: a quel punto copio in questo repo i files che mi interessano



Themes
------

Lista di temi reperibili:

- [http://mattvh.github.io/solar-theme-jekyll/](http://mattvh.github.io/solar-theme-jekyll/)

- [http://pavelmakhov.com/jekyll-clean-dark/](http://pavelmakhov.com/jekyll-clean-dark/)

- [http://richbray.me/frap/gt/](http://richbray.me/frap/gt/)

- [http://chrisanthropic.github.io/comical-jekyll-theme/](http://chrisanthropic.github.io/comical-jekyll-theme/)

- [https://cdn.ampproject.org/c/siawyoung.com/immaculate/](https://cdn.ampproject.org/c/siawyoung.com/immaculate/)

- [http://madebygraham.com/midnight/](http://madebygraham.com/midnight/)

- [http://noita.penibelst.de/](http://noita.penibelst.de/)

Modificare il tema in maniera programmatica:

- [http://stackoverflow.com/questions/31327045/switch-theme-in-an-existing-jekyll-installation](http://stackoverflow.com/questions/31327045/switch-theme-in-an-existing-jekyll-installation)



Navigazione
-----------

- [http://jekyll.tips/jekyll-casts/navigation/](http://jekyll.tips/jekyll-casts/navigation/)


Collections
-----------


- [http://ben.balter.com/2015/02/20/jekyll-collections/](http://ben.balter.com/2015/02/20/jekyll-collections/)



General
-------

Nozioni generiche:

- [http://pixelcog.com/blog/2013/jekyll-from-scratch-core-architecture/](http://pixelcog.com/blog/2013/jekyll-from-scratch-core-architecture/)

- [https://captnemo.in/blog/2014/01/20/pluginless-jekyll/](https://captnemo.in/blog/2014/01/20/pluginless-jekyll/)



Ricapitolo
===========

ricapitolo il lavoro svolto su questo sito.

#### tree

ho impostato la tree base nel seguente modo:

- `_includes` , per i normali include di jakyll
- `_layout`, per i layout,
- `_data`, per raccogliere files `.yml` che fungono da storage di configurazioni globali: `site.data.<nome-file>.<proprieta' impostata nel file>`
- `_pages`, raccolto semplici pagine
- `_plugins`, raccolgo i plugins, che non sono altro che script `Ruby`
- `_posts`, per raccogliere i post (che a differenza delle pagine hanno una data)
- `_sass`, per la gestione degli stili
- `css`, deve contenere almeno un files da inserire nell'header per applicare i `css`

come extra non presente in jekyll ho aggiunto il folder

- `_codes`,

il cui scopo e' contenere tutti i codici che voglio poter reperire mediante il sito.
Per rendere tutto funzionante questo folder risulta associato ad una **Collections** (vedi `_config.yml`)
e al file `_data/codes.yml` (per associargli parametri extra, come il path del `repository` github).


In ultimo e' presente anche il folder

- `scripts`

che uso banalmente per javascript.

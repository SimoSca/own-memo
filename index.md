---
layout: default
title: Home
---

Questo sito e' stato creato per uso personale, al fine di raggruppare nozioni e codici apprese negli anni e talvolta utili da ricordare.

Spesso i codici risulteranno molto "verbosi" e le letture spesso non saranno grammaticalmente corrette.

Come spiegato sono appunti personali, pertanto il lettore potrebbe non comprendere quanto scritto o non essere in accordo;
rimango tuttavia disponibile al dialogo, pertanto se tu lettore hai domande o opposizioni da fare sentiti libero di commentare aprendo issues:
se avro' l'opportunita' sara' mio piacere rispondere e arricchire la mia conoscenza.


### Sviluppo Locale

per permettere l'aggiornamento automatico della **tree** di `_codes` ho scelto di usare una combinazione di

- `guard` e `guard-rake` per poter miscelare il "watch" di guard con i "task di rake"
- `rake` tasks

dunque per il watch risulta necessario dare il comando

`````
guard
`````

Risulta in ogni caso comodo aprire in un altro terminale oppure in modalita' detach anche il sito locale, cosa realizzabile con

```
bundle exec jekyll serve --watch
```

oppure mediante qualche linksimbolico via `apache` o `xampp`.


### TODO

applicare un qualche formato di live-reload, sfruttando il fatto che di base il sito risulta come una `single-page-app` con l' `head` in comune (e di conseguenza uno script per il reload, ad esempio via `web socket`).

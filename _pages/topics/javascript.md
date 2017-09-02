---
layout:     default
title:      Javascript
permalink:  /topics/javascript/
---


### SouceMaps

utili per lo sviluppo: si usano per il debug dei fogli di stile, in particolare quando unisco uno o piu css magari anche minificandoli.

Se as esempio unisco `f1.css` ed `f2.css` in un file `ftotal.css` che poi caricon nella pagina, allora quando svolgo il debug sul browser l'unico foglio di stile che vado a ispezionare e' appunto `ftotal.css`, ma e' un casino in quanto tutto su una rica sola!

Invece io vorrei essere in grado di risalire al fatto che la regola `h1.f1` pur essendo caricata tramite `ftotal.css`, in realta' devo ispezionarla in `f1.css` ad una riga specifica.

Grazie alla SourceMaps posso realizzare questo fatto!!

Inoltre questo stesso ragionamento si potrebbe fare ad esempio per `jquery.min.js` o altri script javascript minificati per risparmiare banda, ma ingestibili in fase di sviluppo o debug!

**vedi:**

[http://blog.teamtreehouse.com/introduction-source-maps](http://blog.teamtreehouse.com/introduction-source-maps)



### Prototype ed ereditarieta'

In javascript ogni oggetto (quindi tutto) ha un prototipo, che e' un oggetto dal quale va a pescare tutte le sue proprieta'. Pur non essendo standard (vedi ECMA5 e ECMA6) ai fini di comprensione posso pensare che il riferimento al prototipo sia salvato in `oggetto.__prototype__`.

La confusione arriva quando si pensa alle funzioni, infatti esse possono essere utilizzate:

* come normali funzioni (`myfunc(){}`), e quindi casi dell'oggetto `Function`
* come costruttori di oggetti mediante l'operatore new ( `new Auto()`)

dunque la funzione in se ha un suo `__proto__`, ma se viene utilizzata come costruttore allora le cose cambiano!

se scrivo `var a = new Auto();` allora la funzione `function Auto(){}` viene utilizzata come costruttore (ovvero `function Auto` viene utilizzato come `a.constructor`), ed in tal caso la runtime machine svolge tre azioni:

* assegna l'attributo `this` alla specifica istanza
* alla funzione auto aggiunge la proprieta' `prototype`, e tutte le istanze create si basano su essa
* assegna internamente alla funzione il prototipo `this.__proto__ = Auto.prototype;`

Quindi ricapitolando:

1 - tutti gli oggetti hanno `__proto__` (se ECMA6)
2 - quando cerco una proprieta', javascript risale la **proto-chain** per trovare il primo `__proto__` in cui e' presente tale proprieta'
2bis - se l'oggetto e' istanziato mediante `new` con una `funzione come costruttore`, allora automaticamente il `__proto__` punta a `funzione.prototype`, pertanto di fatto e' `funzione.prototype` ad entrare nella **proto-chain**.

Tenendo a mente queste differenze, dovrebbe essere piu semplice capire i path che vengono utilizzati per implementare le classi via prototype: in particolare quindi esistono 2 tipi di prototype:

* `prototype` degli oggetti (Function, Array, ...) che in ecma6 sono __proto__
* `function prototype` ovvero il fatto che una funzione assieme a `new` fornisce sia `__proto__` che un `constructor.prototype` (constuctor e' la funzione stessa)


Suggerisco di leggere anzitutto questo articolo, poiche' punta subito l'attenzione su differenza tra `__proto__` e `prototype`, spesso non ben chiarita:

http://sporto.github.io/blog/2013/02/22/a-plain-english-guide-to-javascript-prototypes/


E poi questo, che contiene piccoli esempi piuttosto utili:

https://javascriptweblog.wordpress.com/2010/06/07/understanding-javascript-prototypes/




### Hoisting

caratteristica di javascript di poter utilizzare alcuni parametri e funzioni ancor prima che vengano dichiarati, cosa che in linguaggi come java o c++ non vale, mentre e' presente anche in PHP.

Interessante articolo:

http://adripofjavascript.com/blog/drips/variable-and-function-hoisting



#### Patterns

Alcuni pattern che possono tornare utili in javascript:

- `Intersection Observer`, some tutorials: [1](https://pawelgrzybek.com/the-intersection-observer-api-explained/) [2](https://jeremenichelli.github.io/2016/04/quick-introduction-to-the-intersection-observer-api/)
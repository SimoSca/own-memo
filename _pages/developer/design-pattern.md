---
title:      Design Pattern
permalink:  /developer/design-pattern/
---

Informazioni generali da tenere presente in quanto sviluppatore.


ARGOMENTI GENERICI
------------------

Termini ed argomenti generici per creazione di codice:

- inversion of control -> esempio interessante con factory : [https://www.youtube.com/watch?v=-kpEP4JeEdc](https://www.youtube.com/watch?v=-kpEP4JeEdc)

- declarative and imperative programming

- composition over inheritance: [https://www.youtube.com/watch?v=wfMtDGfHWpA&t=1s](https://www.youtube.com/watch?v=wfMtDGfHWpA&t=1s)

- continuous integration 

- curried functions: modo per gestire funzioni con + argomenti: vedi [https://blog.carbonfive.com/2015/01/14/gettin-freaky-functional-wcurried-javascript/](https://blog.carbonfive.com/2015/01/14/gettin-freaky-functional-wcurried-javascript/). Es di libreria javascript che la usa e' `Ramda`



Design Pattern
---------------

Indubbiamente importanti! Dato che ve ne sono un'infinita' al piu numerabile, elenco solo quelli che reputo piu importanti:

- `reactor` , implementato ad esempio nella gemma `EventMachine` di `ruby`
- `setter injection` e `interface injection`
- factory pattern
- singleton pattern
- strategy pattern
- observer pattern e pub/sub pattern (per le differenze vedi [questo breve ma esplicativo articolo](https://hackernoon.com/observer-vs-pub-sub-pattern-50d3b27f838c))

Vedi questo tutorial semplice e immediato:

[http://it.phptherightway.com/pages/Design-Patterns.html](http://it.phptherightway.com/pages/Design-Patterns.html)

Altri path interessanti sono visualizzabili al link

- [https://sourcemaking.com/design_patterns/memento](https://sourcemaking.com/design_patterns/memento)

- [http://it.phptherightway.com/pages/Design-Patterns.html](http://it.phptherightway.com/pages/Design-Patterns.html)


#### Vari esempi

qui si possono trovare validi esempi di design pattern e refactor:

- [https://sourcemaking.com/](https://sourcemaking.com/) 


Altro sito ([https://deviq.com](https://deviq.com)):

- [open closed principle](https://deviq.com/open-closed-principle/)
- [repository-pattern](https://deviq.com/repository-pattern/)
- [specification pattern](https://deviq.com/specification-pattern/)
- [strategy design pattern](https://deviq.com/strategy-design-pattern/) -> qui ne cita tanti altri molto importanti

Altro:

- [SOLID](https://scotch.io/bar-talk/s-o-l-i-d-the-first-five-principles-of-object-oriented-design)



DESIGN SPECIFICI
----------------

#### Repository Pattern

Tra le teorie lette, credo che una delle migliori interpretazioni consti nel supporre che questo pattern lavori in ottica di dominio,
ovvero che i suoi metodi gestiscano le entita', e che quindi esso non debba occuparsi di svolgere direttamente query a DB e serializzazioni:
per quello potrebbe andar bene un adapter.

- [https://deviq.com/repository-pattern/](https://deviq.com/repository-pattern/)
- [https://code.tutsplus.com/tutorials/the-repository-design-pattern--net-35804](https://code.tutsplus.com/tutorials/the-repository-design-pattern--net-35804)
- [https://stackoverflow.com/questions/48805723/repository-and-data-mapper-coupling](https://stackoverflow.com/questions/48805723/repository-and-data-mapper-coupling)
- [https://stackoverflow.com/questions/16176990/proper-repository-pattern-design-in-php](https://stackoverflow.com/questions/16176990/proper-repository-pattern-design-in-php)


#### Command Query Segregation


Vedi [https://www.culttt.com/2015/01/14/command-query-responsibility-segregation-cqrs/](https://www.culttt.com/2015/01/14/command-query-responsibility-segregation-cqrs/)



PHP USAGE
---------

#### Utilizzo dei Traits

Articolo interessante che illustra come **Laravel** utilizza alcuni `Trait`, 
e il design concettuale col quale implementarli. Molto bellino:

[https://andy-carter.com/blog/using-laravel-s-eloquent-traits](https://andy-carter.com/blog/using-laravel-s-eloquent-traits)

#### Gestione Exception

- [https://martinfowler.com/articles/replaceThrowWithNotification.html](https://martinfowler.com/articles/replaceThrowWithNotification.html)

- [https://www.alainschlesser.com/structuring-php-exceptions/](https://www.alainschlesser.com/structuring-php-exceptions/)

#### Laravel Multi Tenant

- [https://stackoverflow.com/questions/39917731/single-shared-queue-worker-in-laravel-multi-tenant-app](https://stackoverflow.com/questions/39917731/single-shared-queue-worker-in-laravel-multi-tenant-app)


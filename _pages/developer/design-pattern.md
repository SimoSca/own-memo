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

- most famous pattern here [https://refactoring.guru/design-patterns/php](https://refactoring.guru/design-patterns/php)


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

[https://it.phptherightway.com/pages/Design-Patterns.html](http://it.phptherightway.com/pages/Design-Patterns.html)

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


### NOT PATTERNS

Quelli di seguito non sono dei veri e propri pattern, ma use cases che comunque possono essere interessanti, 
anche e solo a livello accademico.

#### Natural Language Simulation

non so bene quale sia il termine relmente corretto per riferirsi a questo caso (e quindi non so come cercarlo "nell'internet").
Mi riferisco a una serie i classi che servono per costriuire dei linguaggi naturali, come ad esempio i `query builder` 
come quello offerto da `Eloquent` (`Laravel`), che simila il costrutto nativo del linguaggio di query `$qb->select()->where()->orWhere()->limit()`.
Probabilmente il pattern piu' attinente a questa casistica e' il `Builder`.

Un caso pratico interessante potrebbe essere l'uso delle **conditions** infatti quando si costruiscono un'insieme di condizioni dentro agli if,
stiamo simulando di molto il linguaggio naturale (letto cosi' e fa cosi').

[Qui]((https://pimcore.com/docs/pimcore/current/Development_Documentation/Tools_and_Features/Targeting_and_Personalization/Conditions.html)) un esempio banale di classe di condizione:
considerando che sostanzialmente ogni condizione deve offrire un booleano, ed inoltre ipotizzando anche l'associativita' con parentesi (`a and not b and (c or d) or e), 
un esempio di "condition builder" che implementa questo potrebbe essere

````php
<?php
$cb = new ConditionsBuilder();
$cond = $cb->condition(ClassCondA)->and()->not()->condition(ClassCondB)->and()->condition(function(ConditionBuilderInterface $cb2){
    return $cb2->contition(ClassCondC)->or()->condition(ClassCondD);
})->or()->condition(ClassCondE);
$isOk = $cond->check();
$cb->reset(); // or get new $cb2 = $cb->new(); or clone the actual $cb->clone(); 
echo $cond;
````

se poi implementasse un `_toString()` come ipotizzato, diverrebbe fantastico anche al fine di debug.

Poi la cosa ancora migliore sarebbe anche associargli un `Lexer` (lessico) per verificare che la query sia costruita secondo specifiche regole,
ad esempio due congiunzioni logiche consecutive `->or()->and()->` o che termini con `->not()` dovrebbe essere considerato inconsistente!

Interessante potrebbe essere anche un print dei risultati booleani, cosi' da capire come ogni singola condizione passata fosse true o false (con pretty print ad esempio);

Vedi qui sotto per il Lexer.

> il concetto di `Conditions` potrebbe sposarsi bene anche coi `Validators`, che potrebbero essere i wrapper di un insieme di Conditions;


#### Lexer

Qui un esempio base di `Token` (singola componente del linguaggio), `Lexer` e `Parser` che effettivamente puo' essere messo in aggiunta
a tutti i pacchetti che prevedano una creazione di un builder proprio di linguaggio (query builder ad esempio), cosi' da verificare a priori varie inconsistenze:

- [https://www.codediesel.com/php/building-a-simple-parser-and-lexer-in-php/](https://www.codediesel.com/php/building-a-simple-parser-and-lexer-in-php/)

#### Expression

Sempre ricollegato a questo tema di conversione tra gestione a classi e uso di un linguaggio logico, abbiamo le espressioni,
ma contrariamente a quanto visto sopra, tutto passare da linguaggio nativo a costrutto in php:

- [https://symfony.com/doc/current/components/expression_language/syntax.html](https://symfony.com/doc/current/components/expression_language/syntax.html)



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


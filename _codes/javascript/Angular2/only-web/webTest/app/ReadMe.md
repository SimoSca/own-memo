APP
====


Per svolgere vari test su **Angular2** ho deciso di suddividere l'applicazione in packages logici. 
A ciascuno di questi package logici e' associata una directory, mentre la resa grafica consta di blocchi con bordi colorati, 
e ad ogni colore e' associato un package.

Per realizzare questi test ho trovato ottimi tutorials, quali:

* [Angular2 Template](https://angular.io/docs/ts/latest/guide/template-syntax.html)
> Questo sopra e' il migliore in assoluto:
> specifica tutto e porta anche alcuni esempi

* [Angular2 typescript](http://www.angulartypescript.com/), ottimo sito con tutorial ed esempi comprensibili e semplici

* [Hello World](http://blog.codeleak.pl/2015/06/angular2hello-world.html), per ottenere un'infarinatura generale su angular2, binding, component, directives e dependency injection. Lo suggerisco in quanto molto conciso.

* [two-way](http://angular-2-training-book.rangle.io/handout/components/app_structure/two_way_data_binding.html), il primo che spiega il two way data-binding senza usare `[(ngModel)]`

* [@Host](http://blog.thoughtram.io/angular/2015/08/20/host-and-visibility-in-angular-2-dependency-injection.html), per un uso avanzato sui provider e gli `@Host`



ANGULAR2 DATA
---------------------------

Una cosa a cui fare attenzione e' la differenza tra i concetti di `two-way` e `flow` dei `data`:

* **two-way data-binding**
	 indica il legame che si crea tra la view e il modello del componente: 
	Angular2 aggiorna **automaticamente** la view se nel modello (classe) si modifica il dato, mentre per fare il contrario bisogna bindare esplicitamente, magari tramite il fire di un evento.

* **data-flow**
	indica come avviene il flusso dei dati nella gestione padre-figli dei componenti e direttive:
	il flusso standard e' unidirezionale, dal padre al figlio. Per realizzare un two-way sulle direttive si deve utilizzare il costrutto `[(target)]`.

Io ho suddiviso in due distinguendo tra i binding `model-view` e i binding `mother-child`,  per distinguere i livelli di astrazione, 
ma in realta' bisogna ricordarsi che se si usano componenti i model e le view sono strettamente legati, pertanto a volte questa distinzione potrebbe risultare forzata.
Forse maturando esperienza con Angular2 scopriro' che questa distinzione non esplicitamente vista nel web, risulta errata.

Per meglio ricapitolare consiglio di leggere [sull'architettura di Angular2](http://www.angulartypescript.com/angular-2-architecture/).



DOM vs HTML
----------------------

Ai fini della comprensione risulta poi utile ricordare la differenza tra `HTML attrubutes` e `DOM property`,
 come spiegato in `HTML attribute vs. DOM property` di [Angular2 Template](https://angular.io/docs/ts/latest/guide/template-syntax.html)



Suggerimento target-source
---------------------------------------------

Per meglio ricordare come usare una direttiva e' bene tenere presente questo esempio:

````
<my-father>
	<my-counter [count]="number1" (countChange)="number1=$event">Number 1:</my-counter>
</my-father>
````

in cui si cita la componente `my-counter` che viene richiamata come direttiva dalla componente `my-father`.

Su `my-counter` si svolge un bind sia in input che in output relativo al `data-flow` `parent-children`:

* al primo membro abbiamo il dato target, dove il target e' la proprieta' o l'evento del componente stesso (`my-component`)

* al secondo membro abbiamo invece il dato sorgente, relativo a proprieta' o eventi dell'elemento padre (`my-father`)


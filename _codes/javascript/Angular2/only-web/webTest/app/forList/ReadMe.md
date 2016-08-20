List
====

Questo package contiene tutto il necessario per realizzare i seguenti:

* realizzazione di una lista mediante componenti e sottocomponenti

* uso di un service provider, per ottenere i dati della lista come se fossero presi dall'esterno

* update delle view in `*ngFor` con e senza `trackBy`


Creazione
=========

procedimenti per la priva fase, ovvero impiego di un servizio e creazione di una lista che possa rendere l'elenco (`WeaponSimple`).

SERVICE
--------------

il servizio in questo caso e' realizzato tramite un mock array che contiene una sequenza di dati preimpostati,
ma nella realta' svolgerebbe una chiamata ajax ad un servizio API per ottenere una lista.

Tratto da [http://www.angulartypescript.com/angular-2-services/](http://www.angulartypescript.com/angular-2-services/)


Procedimento
============

1. creo la classe `NinjaWeapon`, 
	che mi fa semplicemente da scheletro per i dati

2. creo `ninjaWeapon-data`, 
	che mi funzia da data mock, altrimenti dovrei chiamare ad esempio un server via HTTP per ottenere i dati.
	In questo caso esporto un array di `NinjaWeapon`: qui si vede il forte legame con la tipizzazione!

3. creo `ninjaWeapon.service`,
	che risulta essere una direttiva `@Injectable` del core di angular2, e sarebbe proprio il responsabile della chiamata HTTP ad un servizio esterno.
	Notare che infatti viene ritornata una promessa!

4. uso il service dentro al componente che ininizializza le liste (`forList.component`)

5. creo `forList.simple.component` per realizzare la view di questo componente mediante `*ngFor`

6. come extra creo altri files per testare il render dei menu.


OTTIMIZZAZIONE
==============

volendo studiare **Angular2** una delle prime domande che mi pongo consta nel capire come avviene il processo di rendering,
cosa capibile mediante la sezione `Change Detection`.

Essendo un discorso ampio, per capire come ho fatto a realizzare i componenti di prova (`WeaponDynamic` e `WeaponDynamicTrackby`)
e le loro differenze , e' meglio andare alla pagina [Dynamic.md](Dynamic.md).


INFO GENERALI
=============


Change Detection
-----------------------------

il problema che voglio affrontare e' quello di capire se Angular2 effettua il render dell Componente o meno, una volta che questi cambia.
Lo scopo finale e' quello di capire se il cambiamento avviene quando in un `*ngFor` decido di usare anche il `trackby`.

Anzitutto devo notare che non e' cosi' facile rivelare un cambiamento, in quanto devo sapere bene cosa faccio. 
A tal fine provo gli eventi del core di angular:

* `OnChanges`
* `DoCheck`

Ora quello problematico e' `OnChanges`, che assume comportamenti differenti:

- se ho una variabile del tipo `person: string`, ad esempio `person="Gigi"`, allora la sua modifica comporta il trigger di OnChanges, in quanto modifico una stringa.
- se ho una variabile del tipo `person: Object|Array`, ad esempio `person={nome: "gigi"}` allora la cosa cambia poiche' person memorizza **IL RIFERIMENTO** all'oggetto: 
	se svolgo `person.nome="Gianni"`, dal punto di vista di Angular2 non e' avvenuto alcun cambiamento, in quanto `person` continua a mantenere solo il riferimento all'oggetto, 
	e di conseguenza non gli importa se l'oggetto stesso e' stato modificato.

Per ovviare al secondo punto si puo' usare `DoCheck` che viene invocata ogni volta che viene svolto il controllo per capire se il cambiamento e' avvenuto o meno.

Per meglio capire questi dettagli e come `Ng` ragiona, suggerisco le letture:

* [angular-2-best-practices-change-detector-performance/](https://www.lucidchart.com/techblog/2016/05/04/angular-2-best-practices-change-detector-performance/)
* [angular-2-change-detection-explained.html](http://blog.thoughtram.io/angular/2016/02/22/angular-2-change-detection-explained.html)


#### Tentativo

* Tento:
	In ogni caso come soluzione ho optato per un bind nel template mediante un costrutto del tipo
	`{{writeDate() | date}}`
	e nella classe ho `writeDate(){ return new Date();}`
	in questo modo se la data cambia, deduco che ho svolto la modifica.

* Rispondo:
	questo tentativo fallisce in quanto:

	>By default, Angular 2 Change Detection works by checking if the value of template expressions have changed. This is done for all components.

	e quindi

	>By default, Angular 2 does not do deep object comparison to detect changes, it only takes into account properties used by the template

	Tradotto, questo vuol dire che ad ogni check del template Angular2 ritesta anche `{{writeDate() | data}}` e lo aggiorna, poiche' questo valore cambia ogni volta.
	Questo fatto e' visualizzabile nel `forList.simple.component`.

Risposta tratta da [how-does-angular-2-change-detection-really-work/](http://blog.angular-university.io/how-does-angular-2-change-detection-really-work/)
Dynamic
=======

Per comprendere meglio questo package ricapitolo dove sta il problema:

>Voglio ottimizzare le view, evitando che vengano inutilmente distrutti e ricostruiti gli oggett!

In Angular2 i cambiamenti su Oggetti e Array vengono rivelati soltanto se si cambia la loro referenza, 
pertanto modificare l'oggetto a cui puntano (modificare proprieta' o item) non comporta alcun cambiamento). 
Di conseguenza eliminare o modificare un elemento delle liste `weapons` presenti in `forListComponent` non provocherebbe alcun change,
e di conseguenza alcun render delle view: pertanto se mi limito ad appendere/creare/rimuove manualmente specifici elementi, 
la cosa non cambia di molto.

Il vero punto e' che spesso potrei utilizzare una serie di dati che ottengo da una chiamata HTTP ad un server esterno, 
sovrascrivendo ogni volta la variabile `weaponsDynamic*` sulla quale `*ngFor` realizza il loop: 
in questo caso sovrascrivendo ottengo proprio l'effetto di perdere tutte le referenze degli oggetti nell'array, realizzando cosi' un change Angular2 vero e proprio!

Di fatto `*ngFor` quando vede che l'array e' cambiato, pialla completamente tutti gli elementi e li ricostruisce da capo!
Supponiamo che la chiamata HTTP generi qualche centinaio di oggetti: questo vorrebbe dire che Angular2 distrugge e ricrea qualche centinaio di view,
cosa che a livello di prestazioni puo' non essere bella...

Entriamo nel dettaglio: 

- se gli oggetti ritornati sono quasi tutti differenti da quelli iniziali, allora c'e' poco da ottimizzare,	
	in quanto non posso sfruttare le view che avevo gia' creato mediante i componenti:
	di conseguenza in questo caso posso infischiarmente di tutto, procedere a testa d'ariete e poi andare a dormire

- se quasi tutti gli oggetti ritornati sono simili a quelli di partenza (e spesso e' cosi', basti pensare a un wall relativo agli ultimi articoli), 
	allora distruggere un centinaio di view e ricrearle tutte sarebbe uno spreco: in fondo la maggioranza sarebbero uguali a quelle distrutte!

Per ovviare allo spreco di risorse del secondo punto, e' possibile usare il `trackBy` nel loop `ngFor`.


Step
====

in generale poiche' ho voluto mantenere le due liste differenti, ad alcuni eventi ho aggiunto il parametro `property`,
che e' una stringa che mi dice su quale array devo operare: `weaponsDynamic` o `weaponsDynamicTrackby`.

forList
----------

i metodi comuni, che agiscono su una di queste due liste sono:


#### addTrackedby()

in teoria fa una cosa semplice: specificando su quale array agire, mi aggiunge il primo elemento non ancora presente, in ordine di `id`
(quasi come se fosse un database).

Ora la parte complicata di questo metodo: 
per testare che Angular2 percepisca effettivamente un cambiamento, TUTTI gli elementi vengono sovrascritti mediante clonazione di loro stessi;
in questo modo gli elementi di fatto non cambiano, ma essendo clonati ottengo l'effetto di cambiare la referenza, e quindi Angular2 effettivamente realizza un change.
> Nel log infatti vedo sempre  apparire la scritta `chenged: <input del componente>` per un numero di volte pari agli elementi presenti nell'array.

Ecco spiegato il giro contorto fatto in questo metodo.


#### weapTrackbyDelete()
mi dice quale elemento eliminare e da quale dei due array sopra selezionati

#### shuffle()
si limita a rimescolare tutte le liste: 

- sempre dal log posso notare che pur aggiornando le view non viene triggerato alcun `changed: ...`: 
	con questo posso osservare che quindi il risparmio e' dovuto al fatto che evito di distruggere e ricreare componenti,
	ma tutto si limita all'update della view.

- quando la view si aggiorna, in questo caso modificando l'ordine, posso vedere che Angular2 svolge un controllo di tutti i parametri della view:
	infatti nella `ListaSemplice` posso notare che ad ogni shuffle la data e l'ora vengono aggiornati. 
	Questo e' coerente perche' angular controlla il valore di `{{writeDate()}}`, ed essendo dinamico questo cambia ad ogni check.
	Inoltre e' da notare che non viene triggerato il `change` perche' questo e' appunto il risultato di un metodo e non la modifica di una proprieta'!!!



Confronto tra i Loop
=================

SENZA TrackBy:
--------------------------

e' associato alla classe/componente `WeaponDynamic`:

- alla rimozione non succede nulla;
- allo shuffle aggiorna l'ordine  e i valori di damange (che cambio di proposito);

ma la parte interessente e' che quando `Aggiungo`:

- vedo un log di `changed: weapDynamic`  per un numero pari alla lunghezza della lista(vedi commento a `addTrackedby()`);
- vedo apparire `Created new WeaponDynamic` un numero di volte pari alla lunghezza della lista (questa stringa fa parte del costruttore del componente);

e' proprio questo punto la parte importante:

in questo loop non ho ottimizzato nulla, pertanto `*ngFor` si limita a piallare tutti gli oggetti, ricostruire tutti i componenti e realizzare le relative views.

Questo realizzerebbe proprio la condizione di buttare tutto nel cesso e ricostruire da capo... cosa da evitare se si vuole ottimizzare!!!



Con TrackBy:
--------------------------



e' associato alla classe/componente `WeaponDynamicTrackby`:

- alla rimozione non succede nulla;
- allo shuffle aggiorna l'ordine  e i valori di damange (che cambio di proposito);

ma la parte interessente e' che quando `Aggiungo`:

- vedo un log di `changed: weapTrackby`  per un numero pari alla lunghezza della lista(vedi commento a `addTrackedby()`);
- vedo apparire `Created new WeaponDynamicTrackby` solo una volta! (questa stringa fa parte del costruttore del componente);


in questo loop ho ottimizzato  `trackByWeaponsDynamic()`,  che per ogni elemento sostanzialmente dice: 
"se weap.id combacia, allora questo elemento ce l'ho gia', pertanto non stare a distruggerlo e a ricostruirlo, ma limitati ad aggiornare la view!".

Pertanto `*ngFor` risparmia energie, e si limita a creare l'unico elemento il cui `weap.id` non combacia con alguno degli oggetti/componenti gia' presenti.


Conclusione
--------------------

Grazie a `trackBy` evito di distruggere e ricostruire elementi, mantenendo i precedenti e limitandomi a istanziare quelli non ancora presenti.
Naturalmente rimane ancora uno spreco se riferito a questo esempio. 

Questa ottimizzazione inizierebbe pero' ad essere rilevante se ad sesempio nel costruttore di `WeaponDynamicTrackby` venissero realizzate chiamate ajax a terzi servizi: 
risparmiare chiamate inutili e' sempre un bene, anche per risparmiare la banda e la cpu!!!



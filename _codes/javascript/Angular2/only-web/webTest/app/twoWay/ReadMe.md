2-WAY
======

test per il two way data binding in notazione **Angular2**.

Tutti i siti mostrano il two-way data-binding solo con `[(ngModel)]`, rendendo a mio avviso ostico comprendere cosa significhi e come implementarlo,
ma fortunatamente [questo sito](http://angular-2-training-book.rangle.io/handout/components/app_structure/two_way_data_binding.html) spiega tutto in poche righe, 
pertanto lo ripropongo personalizzandolo un minimo.

La parte da capire e' che 

````
<counter [(count)]="number1">Number 1:</counter>
````

e' un sugar che si puo' realizzare con una logica separata traducibile in 

````
<counter [count]="number1" (countChange)="number1=$event">Number 1:</counter>
````

dove appunto abbiamo il binding-input via `[count]`, da mother a child, e il binding-output `(countChange)`, da child a mother.

Da `[(count)]` si capisce subito di dover inizializzare un `@Input() count`, mentre non risulta ovvio dover anche creare un relativo evento con suffido `Change`, in questo caso `@Output() countChange`; 
quest'ultimo risulta pero' di facile memorizzazione una volta capito il trick.


Che sia Parent o Child, in ogni caso modificando i valori delle loro proprieta', `number1` e `count` rispettivamente, si ha l'aggiornamento di entrambe le view.


### Importante

poiche' il two-way realizza sia input che ouput devo stare attento a come inizializzo!!! 

Osservando la forma estesa

````
<counter [count]="number1" (countChange)="number1=$event">Number 1:</counter>
````

si nota che appena viene creata la componente `counter`, il suo valore `count` viene immediatamente sovrascritto da `number1` del parent, in luogo a `[count]="number1"`.
Solo successivamente viene aggiornato `number1` tramite l'evento del figlio: `(countChange)="number1=$event"`.

In luogo a questo, risulta dunque **FONDAMENTALE** inizializzare almeno la variabile `number1` del parent, 
mentre nel child non sono obbligato a dargli un valore, in quanto viene immediatamente sovrascritto dal parent.


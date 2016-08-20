oneChild
=======

semplice gruppo creato per inserire singoli e semplici child nello **shadow/component** `oneChild`, 
che funge da componente principale, e che carica tutti gli altri child.


### child1

semplice e statico child.


### childIf , childIfSub

server solo a testare la direttiva `*ngIf`; `childIfSub` lo uso per testare anche su sotto-componenti.


### childInput

usato per fare in modo che il padre passi dei dati al figlio. Per il figlio i dati passati sono appunto un INPUT. 

Infatti e' all'interno del figlio che si dichiara la variabile di input, o come elemento della proprieta' `inputs` del `@Component`, 
o direttamente come **decoratore/annotazione** `@Input()` seguito da variabile.

Il padre deve importare la **direttiva/componente** e la utilizza direttamente nel suo template tramite il tag ad esso associata, 
ovvero al suo selettore (in questo caso `child-input`).


### childInput

usato per fare in modo che il padre riceva dei dati dal figlio. Per il figlio i dati passati sono appunto un OUTPUT. 

Infatti e' all'interno del figlio che si dichiara la variabile di output, o come elemento della proprieta' `outputs` del `@Component`, 
o direttamente come **decoratore/annotazione** `@Output()` seguito da variabile. Inoltre se voglio all'output posso anche associare un alias (vedi link sui template).

Il padre deve importare la **direttiva/componente** e la utilizza direttamente nel suo template tramite il tag ad esso associata, 
ovvero al suo selettore (in questo caso `child-output`).



/**
 * Sources and Thanks:
 *	- http://www.angulartypescript.com/angular-2-components/
 */

import {Component} from '@angular/core';

// semplice esempio di Child statico
import {Child1} from './child1.component';

// input e output sono pensati rispetto al punto di vista del child: 
// infatti nel parent (questo script) non sono presenti decoratori (direttive) di Input o Output
import {ChildInput} from './childInput.component';
import {ChildOutput} from './childOutput.component';

// testo semplicemente *ngIf
import {ChildIf} from './childIf.component.ts';

@Component({
	selector: 'my-one-child',
	template: `<div>
			<p>Esempio di Componente con child:</p>
			<p>Componente: {{name}}</p>
			<child1>Child1 ...</child1>
			<!--	
				Questo tag, child-input, serve per realizzare il template del componente ChildInput, 
				e per il data-binding di angular2:
				il data-flow sostanzialmente parte dal parent e si propaga nei children. 
				Pertanto di base ogni cambiamento nel parent va a modificare automaticamente i children,
				mentre non vale il contrario, in quanto di default angular e' un one-way data-binding (da parent a child, e non viceversa).
				
				[] e' la direttiva di childInput: 
				quindi ChildInput riceve parentText dal parent, e lo salva nella propria childDataText ;
				inoltre questa viene ad aggiornarsi ognivolta che il parent (la classe di questo script, ovvero oneChild)
				modifica il valore della variabile "parentText" (vedi il setTimeout)
			-->
			<child-input [childInputText]="parentText">ChildInput...</child-input>
			
			<!--
				Per fare in modo che il parent venga a modificarsi in base al child,
				e' necessario propagare l'evento dal child e fare in modo che il parent lo catturi.
				Come sopra, il tag fa capire che ChildOutput e' appunto un figlio, e che si lega al parent mediante l'evento ()
			-->
			<child-output (childOutputChanged)="parentOnChange($event)">Childoutput...</child-output>
			<!-- Qui sotto uso il pipe "|" che ha la stessa funzione del pipe che uso nella shell, ad esempio con "ls | grep .txt" -->
			<p>Output captured: {{parentOutputCaptured | json}}</p>
			
			<child-if>ChildIf...</child-if>
	</div>`,
	styles: [`
		div{
			border: 2px solid blue;
			padding: 2px;
		}`
	],
	directives: [Child1, ChildInput, ChildOutput, ChildIf]
})
export class OneChildComponent {
	
	name: string;
	
	parentText: string;
	// parentOutputCaptured: string; // facoltativo
	
	constructor() {
		this.name = 'componente padre!';
		this.parentText = "wow!";
		setTimeout( ()=>{
			console.log(this);
			this.parentText = 'wow timeout!';
		},5000);
	}
	
	parentOnChange(e){
		console.log('parent detect event and save into "parentOutputCaptured"');
		// appena modifico questa variabile, il template si aggiorna in automatico.
		this.parentOutputCaptured = e;
	}
	
}

/**
 * Tutto questo e' simile a backbone, solo che in backbone:
 * - le View e i Models sono separati
 * - per legare insieme i cambiamenti si usano le Collection (che sono solo sui model)
 * - si legano i cambiamenti mediante listenTo sui model 
 * - si aggiorna via chiamata di render(), cosa che viene fatta manualmente
 *
 * mentre in angular2:
 * - si hanno i componenti che racchiudono la view e il model
 * - (nel caso voglia solo i model, allora uso una semplice direttiva)
 * - non si hanno piu le collection, ma si hanno componenti che possono raccogliere altri componenti come direttive
 * - con la giusta sintassi un cambiamento nel componente padre puo' modificare e far aggiornare AUTOMATICAMENTE la sua view e quella dei figli
 *	(il metodo e' quello di usare [] property nel template padre, e usare un'opportuna variabile INPUT nel child)
 * - con la giusta sintassi un cambiamento nel componente figlio puo' essere catturato dal padre, che pensera' a come eventualmente aggiornare
 *	(il metodo e' quello di usare () event nel template padre, e usare un'opportuna variabile OUTPUT nel child; questo e' simile al listenTo di backbone, solo che si triggera manualmente l'evento, via EventEmitter.emit())
**/

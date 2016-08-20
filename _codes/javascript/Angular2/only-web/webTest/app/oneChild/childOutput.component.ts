// questo child mi serve per testare il binding dei dati

import {Component, EventEmitter} from '@angular/core';

@Component({
	selector: 'child-output',
	template: `<div (click)="childOutputClicked()">
			<p>Esempio di Componente child: {{name}}</p>
	</div>`,
	styles: [`
		div{
			border: 2px solid blue;
			padding: 2px;
			margin: 1px;
		}
		div:hover{
			cursor: pointer;
		}
		`
	],
	// outputs to parent
	outputs: ['childOutputChanged']

})
export class ChildOutput {

	name: string;
	childOutputChanged = new EventEmitter();

	constructor() {
		this.name = 'child-output!';
	}
	
	childOutputClicked(){
		data = {output: Math.random()}
		console.log('child emit events with data:',data);
		this.childOutputChanged.emit(data);
	}
	
}


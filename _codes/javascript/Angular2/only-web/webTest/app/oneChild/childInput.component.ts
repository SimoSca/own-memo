// questo child mi serve per testare il binding dei dati

import {Component} from '@angular/core';

@Component({
	selector: 'child-input',
	template: `<div>
			<p>Esempio di Componente child: {{name}}, and input is {{childInputText}}</p>
	</div>`,
	styles: [`
		div{
			border: 2px solid blue;
			padding: 2px;
			margin: 1px;
		}`
	],
	// inputs from parent
	inputs: ['childInputText']
})
export class ChildInput {

	name: string;
	// childDataText: string; // questo non e' necessario...

	constructor() {
		this.name = 'child-input!';
	}
}

import {Component} from '@angular/core';

@Component({
	selector: 'child1',
	template: `<div>
			<p>Esempio di Componente child: {{name}}</p>
	</div>`,
	styles: [`
		div{
			border: 2px solid blue;
			padding: 2px;
			margin: 1px;
		}`
	]
})
export class Child1 {

	name: string;

	constructor() {
		this.name = 'child1!';
	}
}

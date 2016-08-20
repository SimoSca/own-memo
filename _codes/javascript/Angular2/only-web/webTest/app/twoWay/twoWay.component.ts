/**
 * Sources and Thanks:
 *	- http://angular-2-training-book.rangle.io/handout/components/app_structure/two_way_data_binding.html
 */

import {Component} from '@angular/core';
import {ChildCount} from './childCount.component';

@Component({
	selector: 'my-two-way',
	template: `<div>
			<p>Esempio di Componente con two-way data-flow:</p>
			<p>Componente: {{name}}</p>
			<my-count [(childCount)]="parentCount">ChildCount...</my-count>
			<p>Valore bindato del parent: {{parentCount}} </p>
	</div>`,
	styles: [`
		div{
			border: 2px solid purple;
			padding: 2px;
			margin: 1px;
		}`
	],
	directives: [ChildCount]
})
export class TwoWayComponent {
	
	name: string;
	parentCount: number = 50;
		
	constructor() {
		this.name = 'componente two way parent!';
	}
	
	
}



import {Component} from '@angular/core';

@Component({
	selector: 'hello',
	template: '<div><p>Esempio di Angular2 con caricamento diretto nel browser, senza backend.<br/>Hello {{name}} {{timeout}}</p></div>',
	styles: [`
		div{
			border: 2px solid green;
			padding: 2px;
		}`
	]
})
export class Hello {
	name: string;

	constructor() {
		this.name = 'Angular2 without npm!';
	}
}
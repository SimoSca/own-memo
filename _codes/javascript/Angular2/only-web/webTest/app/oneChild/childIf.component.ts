// questo child mi serve per testare il binding dei dati

import {Component} from '@angular/core';
import {ChildIfSub} from './childIf.sub.component';

@Component({
	selector: 'child-if',
	template: `<div (click)="childIfClicked()">
			<p>
				Testo *ngIf, sia per semplice html che per subcomponenti. Cliccare per cambiare lo stato della variabile.
			</p>
			<p>Esempio di Componente child: {{name}}</p>
			<p *ngIf="childBool">Hello, I'm an element </p>
			<child-if-sub *ngIf="childBool" [childIfSubText]="childText">childIfSub ....</child-if-sub>
	</div>`,
	styles: [`
		div{
			border: 2px solid blue;
			padding: 2px;
			margin: 1px;
		}
		div:hover{
			cursor: pointer;
			border: 2px solid yellow;
		}
		`
	],
	directives: [ChildIfSub]
})
export class ChildIf {

	name: string;
	// to show the div
	childBool: bool = true;
	
	childText: string = "Hello, I'm a child of this!";
	
	constructor() {
		this.name = 'child-if!';
	}
	
	childIfClicked(){
		this.childBool = !this.childBool;
	}
	
	
}



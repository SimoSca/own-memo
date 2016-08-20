import {Component, Input} from '@angular/core';
import {NinjaWeapon} from './ninjaWeapon';

@Component({
	selector: 'weapon-simple',
	template: `
		<li>
			{{weapSimple | json}} {{writeDate() }}
		</li>
	`
})
export class WeaponSimple {

	// questo e' un bug, o almeno credo:
	//	in teoria il NinjaWeapon e' uno solo, pertanto dovrei utilizzarlo senza il simbolo di array [],
	//	ma se lo faccio mi appare l'errore: `Uncaught SyntaxError: Unexpected token export in ninjaWeapon.ts`
	// potrebbe essere semplicemente dovuto a un qualche problema con systemjs, che magari potrebbe sparire compilando esplicitamente i typescript in javascript.
	@Input() weapSimple: NinjaWeapon[]; 
	
	constructor(){
		// console.log('weapSimple');
	}
	
	writeDate(){ 
		return new Date();
	}
	
}

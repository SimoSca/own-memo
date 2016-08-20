// questo child mi serve per testare il binding dei dati

import {Component, Input} from '@angular/core';

@Component({
	selector: 'child-if-sub',
	template: `<div>
			<div id="mydiv">{{name}}, with text: {{childIfSubText}}</div>
	</div>`,
	styles: [`
		#mydiv{
			border: 1px dotted blue;
			padding: 2px;
			margin: 1px;
		}
		`
	]
})
export class ChildIfSub {

	name: string;
	
	// questa annotation vuol dire che la variabile puo' essere modificata dall'esterno
	@Input() childIfSubText;
		
	constructor() {
		this.name = 'child-if-sub!';
	}
	
}




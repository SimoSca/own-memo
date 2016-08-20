import {Component, Input, Output, EventEmitter} from '@angular/core';

@Component({
	selector: 'my-count',
	template: `
		<div>
			<p>
				Count: {{ childCount }}  <br/>
				<button (click)="increment()">Increment</button>
			</p>
    </div>
  `
})
export class ChildCount {
	@Input() childCount: number; // = 0; non sono obbligato ad assegnargli un valore, poiche' essendo un Input viene automaticamente sovrascritto dal parent.
	@Output() childCountChange: EventEmitter<number> = new EventEmitter<number>();
  
	increment() {
		this.childCount++;
		this.childCountChange.emit(this.childCount);
	}
}
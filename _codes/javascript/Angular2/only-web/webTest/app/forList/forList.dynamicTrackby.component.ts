import {Component, Input, Output, EventEmitter} from '@angular/core';
// questi import li uso per testare il detect dei cambiamenti. E' solo a scopo didattico.
import {OnChanges, DoCheck, KeyValueDiffers} from '@angular/core';
import {NinjaWeapon} from './ninjaWeapon';

@Component({
	selector: 'weapon-trackby',
	template: `
		<li>
			<button (click)="removeSingleTrack()">Rimuovi</button>
			{{weapTrackby | json}}
		</li>
	`
})
export class WeaponDynamicTrackby implements  OnChanges, DoCheck {

	@Input() weapTrackby: NinjaWeapon[]; 
	@Output() weapDelete: EventEmitter<number> = new EventEmitter<number>();
	
	constructor(private differs: KeyValueDiffers){
		console.log('Created new WeaponDynamicTrackby', new Date());
		this.differ = differs.find({}).create(null);
	}
	
	removeSingleTrack(){
		this.weapDelete.emit({id: this.weapTrackby.id});
	}
	
				
	// Angular only calls the hook when the value of the input property changes. 
	// The value of the weapTrackBy property is the reference to the weapTrackBy object. 
	// Angular doesn’t care that the weapTrackBy’s own property changed. 
	// The weapTrackBy object reference didn’t change so, from Angular’s perspective, there is no change to report!	
	ngOnChanges(changes) {
		// console.log('ngOnChanges = ', changes['lapsData']);;
		for (var change in changes) {
			console.log('changed: ' + change);
		}
	}
		
	// ngDoCheck() is invoked whenever change detection is run
	ngDoCheck() {
		
		return;
		var changes = this.differ.diff(this.weapTrackby);

		if(changes) {
			console.log('changes detected');
			changes.forEachChangedItem(r => console.log('changed ', r.currentValue));
			changes.forEachAddedItem(r => console.log('added ' + r.currentValue));
			changes.forEachRemovedItem(r => console.log('removed ' + r.currentValue));
		} else {
			console.log('nothing changed');
		}
	}
}

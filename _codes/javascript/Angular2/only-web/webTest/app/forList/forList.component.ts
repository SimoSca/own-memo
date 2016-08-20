import {Component} from '@angular/core';

// per poter tipizzare la variabile weapons di questa classe (e' un extra)
import {NinjaWeapon} from './ninjaWeapon';

// per recuperare i dati mediante il getWeapon();
import {NinjaWeaponService} from './ninjaWeapon.service';

// per scrivere la lista semplice
import {WeaponSimple} from './forList.simple.component';

// per scrivere la lista dinamica
import {WeaponDynamicTrackby} from './forList.dynamicTrackby.component';
import {WeaponDynamic} from './forList.dynamic.component';

// per mescolare rapidamente gli array
Array.prototype.shuffle = function() {
	var input = this;
     
	for (var i = input.length-1; i >=0; i--) {
     
		var randomIndex = Math.floor(Math.random()*(i+1)); 
		var itemAtIndex = input[randomIndex]; 
         
		input[randomIndex] = input[i]; 
		input[i] = itemAtIndex;
	}
	return input;
}

@Component({
	selector: 'my-for-list',
	template: `<div>
			<p>Esempio di Componente con lista e service:</p>
			<p>Componente: {{name}}</p>
			<p> Lista semplice con ngFor (mostro tutto):</p>
			<ul>
				<weapon-simple *ngFor="let weap of weapons" [weapSimple]="weap" >WeaponSimple...</weapon-simple>
			</ul>
			
			<p> Lista dinamica con ngFor SENZA TrackBy:</p>
			<ul>
				<weapon-dynamic *ngFor="let weap of weaponsDynamic" [weapDynamic]="weap" (weapDelete)="weapTrackbyDelete($event, 'weaponsDynamic')">WeaponDynamic...</weapon-dynamic>
			</ul> 
			<p><button (click)="addTrackedby( 'weaponsDynamic')">Aggiungi</button> (aggiungi nuovo item se manca)</p>
			
			<p> Lista dinamica con ngFor CON TrackBy:</p>
			<ul>
				<weapon-trackby *ngFor="let weap of weaponsDynamicTrackby; trackBy:trackByWeaponsDynamic" [weapTrackby]="weap" (weapDelete)="weapTrackbyDelete($event, 'weaponsDynamicTrackby')">WeaponDynamicTrackby...</weapon-trackby>
			</ul> 
			<p><button (click)="addTrackedby( 'weaponsDynamicTrackby')">Aggiungi</button> (aggiungi nuovo item se manca)</p>
			
			
			<p><button (click)="shuffle()">Mescola Tutti</button></p>
	</div>`,
	styles: [`
		div{
			border: 2px solid cyan;
			padding: 2px;
			margin: 1px;
		}`
	],
	directives: [WeaponSimple, WeaponDynamic, WeaponDynamicTrackby],
	providers: [NinjaWeaponService], // in questo modo posso Injectare il servizio 
})
export class ForListComponent {

	public weapons: NinjaWeapon[];
	public weaponsDynamic: NinjaWeapon[];
	public weaponsDynamicTrackby: NinjaWeapon[];
	
	constructor(private _weaponService: NinjaWeaponService){
		this.name = "for list parent!";
		this.getWeapons();			
	}
	
	getWeapons(){
		this._weaponService.getWeapons().then(
			(weap: NinjaWeapon[]) => {
				this.weapons = weap;
				// perform a copy
				this.weaponsDynamic = Object.assign([], weap);
				this.weaponsDynamicTrackby = Object.assign([], weap);
				console.log(weap)
			}
		)
	}
	
	weapTrackbyDelete($e, property: string){
		//console.log('toDelete:', $e);
		let index = -1;
		let dynamic = this[property]; // weaponsDynamic or weaponsDynamicTrackby
		for( let idx in this[property]){
			if(this[property][idx].id === $e.id) index = idx; 
		}
		if (index > -1) {
			this[property].splice(index, 1);
		}
	}
	
	trackByWeaponsDynamic(index: number, weap: NinjaWeapon){
		//console.log('track on:', weap);
		return weap.id;
	}
	
	addTrackedby(property: string){
		console.log('------', 'Trigger add ',property,' event', '--------');
		// track free index
		let idList: number[] = [];
		// store data to reset all weapons references
		// get all occuped id
		let tempWeapons: NinjaWeapon[] = this[property].map(function(el){
			idList.push(el.id); 
			return Object.assign({}, el);
		});
		// find id not used: if exists then update array
		//NB: note the "arrow function" to allow "this" usage
		this.weapons.every( (el, idx)=> {
			// se l'id non e' presente, allora posso aggiungere l'elemento
			if( !idList.some(elem => elem === el.id) ){
				tempWeapons.push(Object.assign({}, el) );			
				// e' un break, come in $.each()
				return false;
			}else{
				return true;
			}
		});
		
		// cambio qualcosa per vedere se anche con il trackby in ogni caso la view si aggiorna
		tempWeapons.map(function(el){
			// valore tra 6 e 10
			el.damage = Math.floor((Math.random() * 5) + 6);
		});
		
		this[property] = tempWeapons;
	};
	
	shuffle(){
		this.weapons.shuffle();
		this.weaponsDynamic.shuffle();
		this.weaponsDynamicTrackby.shuffle();
	}
	
}
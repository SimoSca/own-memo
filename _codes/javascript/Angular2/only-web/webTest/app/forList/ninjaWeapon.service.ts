import {Injectable} from '@angular/core';
import {WEAPONS} from './ninjaWeapon-data';
 
 // questa e' injectable, pertanto si puo' utilizzare direttamente nella componente che la utilizza
 // senza dover inizializzare un `new`
 // Con Injectable si capisce che questo e' un servizio
@Injectable()
export class NinjaWeaponService {
	getWeapons(){
		return Promise.resolve(WEAPONS);
	}
}
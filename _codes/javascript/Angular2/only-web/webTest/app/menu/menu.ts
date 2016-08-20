import {Component} from '@angular/core';

@Component({
    selector: 'ng-menu',
    // Set moduleId to use relative path for Urls
    // moduleId: module.id,		// commonjs
    moduleId: __moduleName,     // systemjs: fully resolved filename; defined at module load time
    templateUrl: './menu.html',
    styleUrls: ['./menu.css']
})
export class Menu {
    name: string;


    constructor() {
    	console.log('without');
        this.name = 'Angular2 without npm!';
    }
}
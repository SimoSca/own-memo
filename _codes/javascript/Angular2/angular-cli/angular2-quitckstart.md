
ANGULARJS 2
============


partendo da zero tendo a seguire il tutorial [https://angular.io/docs/ts/latest/quickstart.html](https://angular.io/docs/ts/latest/quickstart.html), cercando pero' di far girare bene la testa... infatti proprio per questo mi voglio anche dedicare al `FromScratch` in modo da simulare bene tutto cio che mi serve per partire da zero.


STEP BASE

1. mi serve avere typescript installato con npm: `tsc`

2. nella directory che uso come progetto ho bisogno di 4 files base:
  
    - package.json lists packages the QuickStart app depends on and defines some useful scripts. See Npm Package Configuration for details.
    - tsconfig.json is the TypeScript compiler configuration file. See TypeScript Configuration for details.
    - typings.json identifies TypeScript definition files. See TypeScript Configuration for details.
    - systemjs.config.js, the SystemJS configuration file. See discussion below.

3. `npm install`, per installare tutta quella fraccata di moduli che servono a angular2 (vedi [package.json](package.json))

3.bis se per qualche motivo non dovesse esserci il folder di typings, runnare il comando `npm run typings install`. 
> Tenere presente che potrebbe metterci un po per svolgere tutta l'installazione


opzionalmente posso correggere `package.json` aggiungendo

````
{
  "scripts": {
    "start": "tsc && concurrently \"npm run tsc:w\" \"npm run lite\" ", 
    "lite": "lite-server",
    "postinstall": "typings install",
    "tsc": "tsc",
    "tsc:w": "tsc -w",
    "typings": "typings"
  }
}
````

ad esempio chiamo `npm start`


#### Dopo

dopo tutte queste premesse posso creare la directory `app/` e aggiungergli `app.component.ts`.
Angularjs CLI
=============

Tratto da

[https://scotch.io/tutorials/use-the-angular-cli-for-faster-angular-2-projects](https://scotch.io/tutorials/use-the-angular-cli-for-faster-angular-2-projects)

Se non presente:

`npm install -g angular-cli`


Comandi
-------

Comandi eseguiti per creare il progetto:

`ng new enomis`

in questo modo ho gia' finito tutti i preliminari noiosi!



Comando NG
===========

### `ng serve` 

aiuta a

- Built with BrowserSync: Reload on saves
- Automatically routed for us
- Found in the browser at [http://localhost:4200](http://localhost:4200)
- Simplicity and ease-of-mind


### `ng generata`

- Create a new component
- Create a new directive
- Create a new route
- Create a new pipe
- Create a new service

and it's **options**
`--flat`: Don't create the code in it's own directory. Just add all files to the current directory.
`--route=<route>`: Specify the parent route. Only used for generating components and routes.
`--skip-router-generation`: Don't create the route config. Only used for generating routes.
`--default`: The generated route should be a default route.
`--lazy`: Specify if route is lazy. default true



START
=====

Component
---------

dopo aver generato i primissimi files, posso ampliare l'esempio, pertanto provo il classico hello:

`ng generate component hello`

che comporta la creazione di:

- Folder: hello
- Files: `hello.component.[css,html,spec.ts,ts]
- Class: HelloComponent

quindi la tree risulta molto modulare, stile package, proprio come piace a me!


Route
-----

`ng generate route about`

il folder sara' `+about`, dove il `+`  indica che sara' in `lazy loaded`.

> attualmente non disponibile....



More NG CMDS
============

`ng build`
`ng test`: Run unit tests with karma
`ng e2e`: Run end-to-end tests with protractor
`ng get`: Gets values for project
`ng set`: Sets values for project
`ng github-pages:deploy`: Build the app for production, setup GitHub repo, and `publish`
`ng lin`t: Run codelyzer to analyze code
`ng format`
`ng doc`
`ng version`: Get the version of the CLI

esempio

`ng test --watch=false`






IMPROVEMENTs
=============


Molto buono, preso da 

[https://www.sitepoint.com/angular-2-tutorial/](https://www.sitepoint.com/angular-2-tutorial/)


### Serie di comandi


````
# Generate a new component
$ ng generate component my-new-component

# Generate a new directive
$ ng generate directive my-new-directive

# Generate a new pipe
$ ng generate pipe my-new-pipe

# Generate a new service
$ ng generate service my-new-service

# Generate a new class
$ ng generate class my-new-class

# Generate a new interface
$ ng generate interface my-new-interface

# Generate a new enum
$ ng generate enum my-new-enum
````



### Parte TODO

`ng generate class Todo` , e modifico sia `todo.ts` che `todo.spec.ts`

`ng generate service Todo`, e `modifico todo.service.ts` assieme al relativo `spec.ts`

`ng generate component TodoApp`, e modifico `todo-app.component.html`, `...component.ts`

---
title:      Tutorial
permalink:  /note/tutorial/
---

letture
http://www.newmediacampaigns.com/blog/woah-i-switched-to-windows-and-its-awesome-for-php-development
https://blog.jetbrains.com/phpstorm/2016/06/debugging-vvv-vagrant-setup-with-phpstorm/


Conosco `wordpress`, `elgg` e `laravel` e di recente mi ero ripromesso di creare un `Telegram Messanger Bot`,
cosi' preso dalla mia inesorabile voglia di imparare mi sono detto: __perche' non provare a sviluppare qualcosa di diverso??__

Di tutta risposta e spinto dalla voglia di apprendere almeno le basi di `Joomla` ho deciso di

**creare un plugin Joomla che gestisca un Bot Telegram** , mica male come idea!

Per rendere il lavoro riutilizzabile, ho deciso di segnare passo per passo quello che faro'.

Anzitutto sono un patito di configurazioni e settaggi pertanto per cominciare e rendere il mio lavoro completo ho deciso che
l'architettura base doveva comprendere:

- `server apache`
- `php5 o superiore`
- `phpUnit` per i test
- `composer` per gestire librerie e autoload
- `XDebug` per consentirmi di sviluppare senza i soliti `print_r` e `var_dump`

Per implementare il tutto ho optato per l'installazione di una macchina virtuale (`VM`) usando un `Vagrant box` che ho trovato nella rete,
e che mi ha permesso di essere operativo nel giro di pochissimi minuti!!!

usato
https://github.com/mattandersen/vagrant-lamp

La directory `src` presente nel folder d'installazione di questa `VM` e' creata specificamente dal `Vagrant Box` utilizzato in questo esempio, e la sua peculiarita' e' che questo folder e' accessibile sia dalla `VM` che dal computer `Host` (ovvero quello che sto utilizzando).

**Altri Vagrant box**
e uno basato su `Chef`
https://github.com/MiniCodeMonkey/Vagrant-LAMP-Stack

completissimo
https://github.com/morafabio/vagrant-php-development-box

uno fortissimo per `Joomla` qualora decida di sviluppare piu siti
https://github.com/joomlatools/joomlatools-vagrant
e suo tutorial
https://www.joomlatools.com/developer/tools/vagrant/


Con questo ho una base, ma ora devo pensare ai tools per l'automazione; cosa intendo?
sostanzialmente vi sono due operazioni che voglio automatizzare:

### Testing

Tenendo aperto un terminale, voglio che ad ogni salvataggio di una classe `phpUnit` esegua il test su quella classe,
e naturalmente voglio visualizzare il risultato sul terminale per poter subito capire se mi sto muovendo bene.
Il progetto si basera' su un folder `libs` che conterra' tutte le classi di supporto, e i test verranno sviluppati
nel folder `test` (da creare).

Per rendere automatizzare l'esecuzione dei test mediante un'opzione di **watch** (vedi `gulpfile`) usero':

- `composer` , in particolare in `psr4` per poter sfruttare il suo autoload (ricordare comando `composer dump-autoload`)
- convenzione sui nomi: i test dovranno avere il postfisso `Test`

Grazie a questo un prototipo di comando `phpunit` sara'

````
phpunit --bootstrap vendor\autoload.php test\libs\<MyClass>Test.php
````


### Browser Refresh

Per mio gusto se devo avere un riscontro visuale preferisco usare un browser piuttosto che utilizzare l'interfaccia del mio IDE.
Fatta questa premessa, parlo di una delle azioni tipiche che vengono svolte dallo sviluppatore, ovvero

1. salvare codice
2. andare sul browser ed aggiornare la pagina
3. tornare nuovamente sull' IDE per continuare il proprio lavoro

Quante volte capita di farlo? Troppeeeeee!!!

Pertanto anche in questo caso voglio uno strumento che mi consenta di ricaricare il browser ogni volta che salvo determinati
file (`.php`, `.css`, `.html`, `.js`).

> In questa guida non mi occupo di transpiller come `sass`, `typescript` e templating, ma sarebbe bene usarli per un grosso progetto.


#### Soluzione: **watch**

dato che tutto si basa su azioni il cui `trigger` e' basato sul salvataggio dei file, sorge naturale adottare un `tasks system`
che  consenta agilmente l'utilizzo di programmi `watch` (ad esempio `guard` per `Ruby`).

Quale scegliere va a gusti e non fa parte dello scopo di questo tutorial, pertanto mi limito a tracciare le mie scelte:

- `npm browsersync` , per l'autoreload (lo uso come proxy-server )
- `npm gulp` , per utilizzare i suoi plugin e le sue funzioni di watch


> io li ho installati globalmente poiche' li utilizzo spesso,
> ma per un lavoro serio sarebbe meglio salvarli localmente con l'opzione
`--save-dev` per poter tenere traccia del lavoro con npm

> se non trova le installazioni globali, aggiungere il link mediante `npm link gulp`, o estensione non trovata

ed ecco qui un esempio del mio `gulpfile.js`
{% highlight javascript %}
var gulp = require('gulp'),
    path = require('path'),
    bs = require('browser-sync').create(),
    proxyTarget = "localhost:8888/test",
    serverPort =  30003;

// start proxy server to inject websocket to autoload and dispatch message
gulp.task('browser-sync', function() {
    console.log('Init Server and proxy to...!');
    bs.init({
        port: serverPort,
        proxy:{
            target: proxyTarget, // can be [virtual host, sub-directory, localhost with port]
            ws: true // enables websockets
        }
    });
});

// Monitor over php files
gulp.task('phpunit', function(){
    gulp.watch('**/*.php').on('change', function(file){
        if(file.path.match(/\.php$/)){
            var s = __dirname + '(.+)\.php';
            s = s.replace(/\\+/g, '\\\\');
            var r = new RegExp(s);
            var relSrc = file.path.match(r);
            console.log('Run test over ' + relSrc);
            var dest = path.join(__dirname, 'Test', relSrc[1], '.php');
            bs.reload()
        }
    });
});

gulp.task('watch', ['browser-sync', 'phpunit']);
{% endhighlight %}

**NOTA BENE**
In questo tutorial la `Vagrant VM` viene utilizzata solo come server, ed i files verranno salvati nella directory `src`: i files ivi contenuti sono accessibili sia dalla `VM` che dal mio pc stesso (vedi prossima sezine).
Dato che il mio `IDE` risiede sul mio pc normale (`Vagrant Host`), i files e le loro modifiche li trattero' come locali, e quindi in questo caso mi basta avere installato `nodejs` e i relativi plugin solamente sul pc, e non sulla macchina virtuale.


# Joomla

Dopo aver inizializzato la `VM`, dentro la directory `src` creo la directory di lavoro, in questo caso `joomlagram`, e qui inseriro' la mia installazione di joomla.

Non entro nel dettaglio ma mi limito a specificare che in fase di installazione ho scelto `MySql PDO` come connettore.


### Trick

#### aggiornare plugin
capita quando si modifica il relativo `file.xml`: per fare in modo che le sue modifiche vengano aggiornate andare in
`Extensions > Manage > Manage` , selezionare il plugin e poi `Refresh Cache`.



KickOff!!!
==========

Directory utili:

- `modules`
- `plugins`


Nella home del mio progetto inserisco un file `build.xml` da runnare con **ant**:
il suo scopo sara' solamente quello di svolgere operanzioni utili come copiare tutte le directory che creo in `joomla` in un altra directory,
consentendomi un poco piu di ordine mentale, specie ai fini del backup.

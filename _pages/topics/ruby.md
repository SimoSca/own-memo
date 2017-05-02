---
title:      Ruby
permalink:  /topics/ruby/
---

Ruby risulta piuttosto complesso, pertanto non e' mia intenzione scrivere un tutorial su di esso,
ma mi limito a inserire dei macro argomenti con link utili.

> hook javascript a menu automatico


### Literals

%strong come [], {}, ->

https://en.wikipedia.org/wiki/Literal_(computer_programming)


### Proc vs lambda

risposta 1 di http://stackoverflow.com/questions/1435743/why-does-explicit-return-make-a-difference-in-a-proc

e

http://stackoverflow.com/questions/1740046/whats-the-difference-between-a-proc-and-a-lambda-in-ruby


### Hashes

https://www.sitepoint.com/guide-ruby-collections-ii-hashes-sets-ranges/


### Thread

Ruby implementa i `Thread` in maniera falsa: in realta' non realizza veramente dei processi separati.  
Come alternative esistono diverse implementazioni, tra cui le piu gettonate:

- Rubinius
- JRuby

che naturalmente portano sia a pro e contro.


### Threads vs Processes

buonissima guida:

- [ruby-concurrency-and-parallelism-a-practical-primer](https://www.toptal.com/ruby/ruby-concurrency-and-parallelism-a-practical-primer)


# GEM

gemme interessanti per la gestione.


### Prawn e Combine_pdf

due gemme molto utili per la creazione e gestione di file PDF via ruby, nello specifico

* `prawn` per editing e creazione di documenti
* `combine_pdf` per la gestione: split, merge, rotazione, trasformazione, cambio numeri di pagina, etc.


### EventMachine

molto utile per il design pattern `reactor`.

Non vi sono molti tutorial, ma ho trovato questi per iniziare:

- [starting-with-eventmachine-i](http://javieracero.com/blog/starting-with-eventmachine-i) , serie di 4 mini tutorial, VERAMENTE UTILI
- [ruby-eventmachine-the-speed-demon](https://www.igvita.com/2008/05/27/ruby-eventmachine-the-speed-demon/)
- [eventmachine-introductions](http://everburning.com/news/eventmachine-introductions.html), in particolare il pdf della presentazione!


## Creare Gemma

Il modo piu veloce e probabilmente mantenibile per creare una gemma e' mediante bundler:

````
bundle gem <nome-gemma>
````

stando attendo al fatto che il `-` fa creare delle subdirectory forse indesiderate.

Per dettagli vedere:

- [how-to-build-a-ruby-gem-with-bundler-test-driven-development-travis-ci-and-coveralls-oh-my](https://www.smashingmagazine.com/2014/04/how-to-build-a-ruby-gem-with-bundler-test-driven-development-travis-ci-and-coveralls-oh-my/)
- [engineering-lunch-series-step-by-step-guide-to-building-your-first-ruby-gem](https://quickleft.com/blog/engineering-lunch-series-step-by-step-guide-to-building-your-first-ruby-gem/)

oppure guardare il puro tutorial senza bundler:

- [Ruby Gem Tutorial](http://guides.rubygems.org/make-your-own-gem/)




Linguaggio
-----------

peculiarita' di ruby che vale la pena sottolineare:

#### eingenclass

costrutti come `class << self`.

In sostanza ruby distingue i metodi in  singleton, class ed istanza. Il singleton non e' inteso come __singola istanza__ univocamente chiamabile (come in laravel), ma come singolo oggetto istanziato. Vediamo meglio , supponendo che utilizzi la classe `Foo` con metodo `hello`:

- **Class** , metodo statico di tutta la classe; in PHP sarebbe lo static, come `Foo::hello()`, quindi in comune tra tutte le istanze.

- **Instance**, metodo presente in tutte le istanze e non statico; in PHP `$f = new Foo(); f.hello();`

- **Singleton**, metodo della singola istanza; in javascript come `var f = new Foo(); f.hello = ...` (in questo caso hello suppongo non essere definito in `Foo` e pertanto sara' presente solo in `f`, e non in altre istanze create con `new Foo`)

Links:

- [class-self-idiom-in-ruby](http://stackoverflow.com/questions/2505067/class-self-idiom-in-ruby)
- [eigenclass](http://www.integralist.co.uk/posts/eigenclass.html)


#### lambda, proc e closure

articolo ben fatto:

[http://awaxman11.github.io/blog/2013/08/05/what-is-the-difference-between-a-block/](http://awaxman11.github.io/blog/2013/08/05/what-is-the-difference-between-a-block/)


Throubleshoots
---------------

### Compilazione

ci ho sbattuto la testa per un bel po... sostanzialmente, almeno su `Windows`, puo' capitare che ruby non riesca a installare alcune gemme (ad esempio `json`),
e ritorna un errore del tipo

````
ERROR: Failed to build gem native extension.
````

Per sopperire a questo problema ho provato a installare il `Ruby Tool Kit` ([Installazione](https://github.com/oneclick/rubyinstaller/wiki/Development-Kit)):

1. scarico il file
2. lo spacchetto salvandolo in una directory (potrebbe essere quella ri Ruby, oppure un `RubyDevKit` sibling of Ruby)
3. comando `$ ruby dk.rb init`
4. se sopra e' tutto liscio, allora comando `$ ruby dk.rb install`

ora... dopo ore di ricerche e tentativi **NULLA FUNZIONAVA!!!**

Ho risolto nella maniera piu ignorante possibile, ovvero aggiungendo gli eseguibili del RubyDevKit

`RubyDevKit/bin` e `RubyDevKit/mingw/bin`

al path di `Windows`, e **funzia!!!**


### Update into Macbook

Simply follow the list if you wanna update via `homebrew`:

````bash
$ brew update
$ brew install ruby-build
$ brew install rbenv
$ rbenv install 2.4.1
$ rbenv global 2.4.1
````

But with this, `$ ruby -v` shows still older version, that is put into `.bashrc` or `.profile` :

````bash
# Initialize rbenv
if which rbenv > /dev/null; then eval "$(rbenv init -)"; fi
````

and more important: after this, rerun

````bash
gem install bundler
````


**Only as NOTE**
,and eventually (but in this case doesn't works...):

````bash
gem install rspec
rspec --init

````
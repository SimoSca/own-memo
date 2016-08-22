---
title:      Testing
permalink:  /developer/testing/
---


Il testing e' importante pertanto qui mi limito a raccogliere alcuni link

**Assoultissimamente da leggere** per avere una nozione panoramica:

[https://github.com/CarmenPopoviciu/protractor-styleguide](https://github.com/CarmenPopoviciu/protractor-styleguide)


#### Sincronizzazione

Inoltre i test e' in generale renderli automatici con delle opportune configurazioni di `watch`, evitando cosi' il loro lancio manuale.
Quando saranno reperibili soluzioni, le inseriro'

Un trick carino e' quello di runnare lo script in background e mandare una notifica di sistema quando il test fallisce: vedi [questo simpatico esempio](http://erichogue.ca/2012/04/best-practices/continuous-testing/).

In ogni caso la scelta piu comoda con `Nodejs` e' quella di usare il proxy-server `broswersync` (vedi questo [esempio con Gulp](https://scotch.io/tutorials/how-to-use-browsersync-for-faster-development)).




PHP
----

- [jtreminio/unit-testing-tutorial-introduction-to-phpunit/](https://jtreminio.com/2013/03/unit-testing-tutorial-introduction-to-phpunit/)

### PHPUnit

**Ruby**

tramite estensione `guard-phpunit` (vedi [Tutorial](http://welcometothebundle.com/automate-test-and-code-inspection-in-php-with-guard-and-symfony2/) ).

plugins utili:

- `guard-phpunit`


**Nodejs**

Ecco un [esempio con Gruntjs](https://coderwall.com/p/chdf1w/php-continuous-testing-with-gruntjs-and-phpunit) e un [esempio con Gulp e notify](https://alfrednutile.info/posts/85).

plugins utili:

- `grunt-phpunit`
- `gulp-phpunit`




JAVASCRIPT
----------------------


### Angular2

- [http://www.mithunvp.com/build-angular-apps-using-angular-2-cli/](http://www.mithunvp.com/build-angular-apps-using-angular-2-cli/)



### Karma

- [https://github.com/rcosnita/polymer-karma-example](https://github.com/rcosnita/polymer-karma-example)



### Protractor

- [https://github.com/juliemr/protractor-demo](https://github.com/juliemr/protractor-demo)


### QUnit

- [https://github.com/dwyl/learn-qunit](https://github.com/dwyl/learn-qunit)
- [https://github.com/jquery/qunit](https://github.com/jquery/qunit)
- [https://github.com/jquery/qunitjs.com](https://github.com/jquery/qunitjs.com)

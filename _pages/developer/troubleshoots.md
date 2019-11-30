---
title:      Compiler
permalink:  /developer/troubleshoots/
---

BREW HELM OLD VERSIONS
----------------------

per installare una vecchia versione non più presente in homebrew vedi 
[https://medium.com/@schmijos/installing-old-homebrew-formulas-dc91de0c329c](https://medium.com/@schmijos/installing-old-homebrew-formulas-dc91de0c329c)

#### STEPS

Trovare la formula [https://github.com/Homebrew/homebrew-core/tree/master/Formula](https://github.com/Homebrew/homebrew-core/tree/master/Formula)

esempio

https://github.com/Homebrew/homebrew-core/blob/9db72db1b627b9c9944856125f2103a971c228f7/Formula/helm.rb

andare alla vecchia versione (vecchio commit), per trovarla facilmente ricercare nella ricerca di git il nome del pacchetto e premere "commits". IMPORTANTE PUNTARE AL RAW FILE

https://raw.githubusercontent.com/Homebrew/homebrew-core/2fbed24cb83d0ecc69b8004e69027e0d8eed5f9d/Formula/kubernetes-helm.rb

fare 

````bash
brew install https://raw.githubusercontent.com/Homebrew/homebrew-core/2fbed24cb83d0ecc69b8004e69027e0d8eed5f9d/Formula/kubernetes-helm.rb
brew switch kubernetes-helm 2.9.1
brew pin kubernetes-helm
````


PHP COMPOSER PROBLEM
---------------------

Problema: sul composer.lock vedevo che il package ha la versione 0.9.7, ma nella `vendor/<tenant>/<package>` invece aveo la 0.9.6,
    e con composer install rimaneva 0.9.6... quindi non aggiornava!

1. con `composer show -i`  ho visto che secondo composer il package e' alla versione 0.9.7... ed anche in `vendor/composer/installed.json` effettivamente vedo 0.9.7... quindi ho un qualcosa che non va...

Con `composer clear-cache` rimuovo la cache nella mia vendor

A questo punto ho provato `rm vendor/composer/installed.json` e poi con `composer install --no-dev` vedo che finalmente li ha reinstallati, ed inoltre ha ricreato anche il `vendor/composer/installed.json`



VSCODE PROBLEM
--------------

Se il `vscode-remote` s'inceppa, posso killare i processi del mio utente:

````bash
pkill -f <my username>/.vscode
````


ANDROID ADB
-----------

/Users/<myuser>/Library/Android/sdk/platform-tools

Connect to network device:

````
adb tcpip 5555
adb devices
adb -s 192.168.14.147:5555
adb connect 192.168.14.147:5555
````
---
layout:     default
title:      Fixes InternetExplorer
permalink:  /readings/fixes/
---



\#condividofix \#perchecapitanotutteame?!


Windows 8 + IE problems...
==========================


Settimana scorsa il reparto Dev di un cliente ci ha contattati perche' su una piattaforma che deve comunicare con una nostra community era presente un bug per una specifica casistica di SO e Browser, e non se ne veniva a capo.

E' uno di quei casi in cui la soluzione arriva tramite un workaround scovato su un forum "tanto tanto lontano", e trovo bello condividere questa info che forse tra qualche anno potra' aiutare qualcuno a risolvere la stessa problematica "in una gelida notte d'inverno"...


### Contesto

La piattaforma deve caricare degli iframe il cui sorgente punta alla nostra community (fin qui sembra semplice), ma la peculiarita' e' che l'utente deve prima autenticarsi su tale piattaforma, e dopo tale azione e' necessario che risulti autenticato anche in Community.
Tralasciando l'implementazione di come deve avvenire l'autenticazione, la parte importante e' che per svolgere correttamente il flusso, una volta caricata la pagina, dalla piattaforma partono delle chiamate ajax verso un nostro endpoint, e l'output di una risposta viene utilizzato per triggherare la richiesta successiva (quindi avviene una chiamata e alla risoluzione della sua "promise" parte la chiamata successiva).


### Problema

Un uggioso giorno di Ottobre si scopre che "da rete interna, per i pc Windows 8 e SOLO SU INTERNET EXPLORER" il giro sapientemente studiato non funziona:
parte la prima chiamata e ottiene una risposta corretta, ma dalla seconda chiamata in poi si ottengono degli errori...

Il primo naturale impatto e' stato quello di attribuire il problema a eventuali proxy della rete interna, o a delle policy sui pc, ma dopo aver contattato i responsabili, questa problematica e' stata scartata (o quantomeno accantonata in quanto non direttamente verificabile).
In aggiunta a questo, anche i log del nostro applicativo non davano evidenze particolari.

Come scritto sopra, venne pero' in aiuto un thread trovato nel web (non ho il link, mi spiace) e cosi' trovammo il workround che spiego sotto.


### Fix

Come spesso avviene in questi casi border line la fatica e' trovare il fix, non l'implementazione concreta: 
in questo e' bastato aggiungere un delay di circa 1 secondo (ma quello puo' dipendere dalla specifica implementazione) tra una chiamata ajax e l'altra.


Sembrerebbe che il problema sia un bug di IE che su quella particolare versione W8 non realizza completamente le chiamate Ajax se non viene terminato correttamente l' ACKNOWLEDGE della precedente... doh!

Spinti dalla curiosita' abbiamo cercato di capire perche' un bug di questo tipo si sia presentato solo per i casi di connessione da "rete interna" e non da quella esterna, e una spiegazione e' la seguente:

da rete interna l'apertura/chiusura della connessione (il processo di ACK) probabilmente avviene piu' lentamente in quanto all'interno della rete e' presente un proxy che potrebbe aggiungere un certo delay, e questo dealy causa appunto un ritardo della chiusura della connessione.

Quindi probabilmente (e dico probabilmente perche' nessuno dei presenti era uno sviluppatore di Internet Explorer :) ) quando il browser riceve la risposta risolve la promise prima ancora di terminare l' ACK, e di conseguenza la chiamata successiva parte quando ancora l' ACK  della precedente deve essere chiuso, portanto cosi' all'errore di cui sopra.



Che sia chiaro, la parte relativa alla spiegazione non prendetela come oro colato: dopo aver trovato il fix e cercato senza troppa energia una spiegazione plausibile, era presente in tutti la voglia di godersi il weekend (dato che il fix e' stato testato di venerdi' pomeriggio), quindi di tacito accordo abbiamo accettato quella soluzione senza indagare oltre.



Per quanto mi riguarda questa e' una pietra miliare dei fix, che sicuramente arricchira' il mio bagalio personale e verra' tatuata sul mio "omino del cervello" per essere pronto a riutilizzarla se dovesse ricapitare in futuro... ma mi auguro che per quando arrivera' "il futuro" i popoli avranno finalmente debellato questo antico male che tutt'ora affligge i Dev e che prende il nome di "Internet Explorer".




Compilazione Form
-----------------


Su alcuni pc gli utenti riuscivano a loggarsi, su altri no... dopo mille insidie si scopri' che il problema era dovuto a delle estensioni del browser 
(tipo lastpass), che svolgevano l'autocomplete di alcuni hiddend fields, e quindi il login non andava mai a buon fine perche' "compilato" in maniera errata.
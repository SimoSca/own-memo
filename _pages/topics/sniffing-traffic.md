---
layout:     default
title:      Sniffing (local traffic)
permalink:  /topics/sniffing-traffic/
---



#### proxy/network

- mitmproxy per traccare le chiamate che svolge una app

- ngrok

- mitmproxy, mitmdump con uno script per parsare il websocket e websocket client per forgiare le richieste



### sniffing
- Collegare celloulare in wi-fi al wi-fi del mac x il man in the middle
- per sniffare la connessione mitmproxy
- specificare per il mac: specificare il sistema per dirgli di sniffare le chiamate (packet filter): tutte le porte tcp in un certo range vengono inoltrate alla 8080. Ad esempio setto nelle impostazioni network del device l'opzione Proxies, e poi SOCKS PROXY, interendo 127.0.0.1 porta 8080.
- problema: connessioni client-server criptate tramite certificato: ma noi possiamo iniettare i certificati del server in ogni chiamata
(mitm.it e gli installi il suo certificato, e poi lo installi)

Per i due punti precedenti vedi: https://blogs.msdn.microsoft.com/aaddevsup/2018/04/11/tracing-all-network-machine-traffic-using-mitmproxy-for-mac-osx/

- wss (websocket), ma mitmproxy non permette inspection della web proxy (wrapper sopra mitmdump)

- dunque ora si passa a mitmdump

- con mitmdump creo script: quando si verifica un messaggio wss allora stampo il payload


mitmproxy --mode socks5 --showhost

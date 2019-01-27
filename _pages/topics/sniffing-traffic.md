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

- Collegare cellulare in wi-fi al wi-fi del mac x il man in the middle per sniffare la connessione mitmproxy (https://www.imore.com/how-turn-your-macs-internet-connection-wifi-hotspot-internet-sharing)

specificare per il mac:
 
- specificare il sistema per dirgli di sniffare le chiamate (packet filter): tutte le porte tcp in un certo range vengono inoltrate alla 8080. Ad esempio setto nelle impostazioni network del device l'opzione Proxies (tab advanced nel network preference dell'interfaccia di rete che voglio utilizzare), e poi SOCKS PROXY, interendo 127.0.0.1 porta 8080.

- problema: connessioni client-server criptate tramite certificato: ma noi possiamo iniettare i certificati del server in ogni chiamata
(mitm.it e gli installi il suo certificato, e poi lo installi)


> Per i due punti precedenti vedi [qui](https://blogs.msdn.microsoft.com/aaddevsup/2018/04/11/tracing-all-network-machine-traffic-using-mitmproxy-for-mac-osx/).

- wss (websocket), ma mitmproxy non permette inspection della web proxy (wrapper sopra mitmdump)


esempi di comandi comandi:

````
mitmproxy --mode socks5 --showhost

# o in caso si debbano utilizzare degli script su event listeners
mitmdump --mode socks5 --showhost -s mtest.py -s mheader.py -s ws.py --set websock=true
````



Nel mio caso
------------

- per internet uso la mia ethernet thunderbolt, quindi per test disabilito wi-fi, imposto quella

- la thunderbolt gestisce il mio internet, quindi nelle sue configurazioni avanzate imposto il proxy SOCK sulla 8080

- dopo aver impostato e salvato la tunderbolt, a quel punto non dovrei piu' navigare in interntet, ma ci dovrei riuscire dopo aver digitato

    ````bash
    mitmproxy --mode socks5 --showhost
    ```` 
    
se ok, allora posso procedere allo sharing della connessione:

- in `system preferences` > `sharing` imposto la condivisione internet. E' intuitivo, quindi non scrivo altro.


WEBSOCKET
---------

di default `mitmproxy/mitmdump` sniffano soltanto la connessione, non lo scambio dei messaggi `ws`.

Fortunatamente il sistema ad eventi consente di ovviare a questo problema!

Lo script che utilizzo per visualizzare il contenuto e' il seguente:

````python
import mitmproxy.websocket
from mitmproxy import ctx



class AddWs:
    # Websocket lifecycle
    def websocket_handshake(self, flow: mitmproxy.http.HTTPFlow):
        """
            Called when a client wants to establish a WebSocket connection. The
            WebSocket-specific headers can be manipulated to alter the
            handshake. The flow object is guaranteed to have a non-None request
            attribute.
        """

    def websocket_start(self, flow: mitmproxy.websocket.WebSocketFlow):
        """
            A websocket connection has commenced.
        """

    def websocket_message(self, flow: mitmproxy.websocket.WebSocketFlow):
        """
            Called when a WebSocket message is received from the client or
            server. The most recent message will be flow.messages[-1]. The
            message is user-modifiable. Currently there are two types of
            messages, corresponding to the BINARY and TEXT frame types.
        """
        #self.dump(flow.messages[0])
        # ottengo l'ultimo messaggio
        ctx.log.info("    ws message: " + flow.messages[-1].content)

    def websocket_error(self, flow: mitmproxy.websocket.WebSocketFlow):
        """
            A websocket connection has had an error.
        """

    def websocket_end(self, flow: mitmproxy.websocket.WebSocketFlow):
        """
            A websocket connection has ended.
        """

    # for debugging
    def dump(self, obj):
        for attr in dir(obj):
            ctx.log.error("obj.%s = %r" % (attr, getattr(obj, attr)))

addons = [
    AddWs()
]
````


In questo modo posso utilizzare il proxy col comando:

````bash
mitmdump --mode socks5 --showhost -s ws.py  --set websocket=true
````

dove `ws.py` e' il nome che ho dato allo script di cui sopra.


#### Test

Per testare usare una connessione al sito [https://www.websocket.org/echo.html](https://www.websocket.org/echo.html).

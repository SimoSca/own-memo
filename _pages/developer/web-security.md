---
title:      Security
permalink:  /developer/web-security
---

La sicurezza web, ma non solo, consta di tanti piccoli accorgimenti e prevenzioni, per le quali bisogna mostrare accortezza.

I principali blocchi da affrontare riguardano autenticazione, persistenza e crittografia. Ciascuno ha degli standard che possono aiutare a capire come comportarsi


### Secure saltz

Lecture to [https://crackstation.net/hashing-security.htm](https://crackstation.net/hashing-security.htm)


### Crittografia

vi sono sostanzialmente due tipi:

* **a chiave antisimmetrica**, in cui si fa uso di una chiave pubblica e una privata (ad esempio `SSH` e `HTTPS` mediante `rsa`)

* **a chiave simmetrica**, in cui i due utenti condividono la stessa `secret` e la usano per criptare testo ed eventualmente firmarlo (signed): ovvero mi passano due messaggi, uno originale e lo stesso ma crittografato; anch'io con la mia secret crittografo l'originale e di conseguenza sia io che l'utente abbiamo la stessa secret, pertanto dovrei sperare che sia chi io penso. In questo caso il messaggio e' visibile, pertanto non ha senso per mandare messaggi con informazioni sensibili.

Ad esempio `HTTPS` fa uso di un misto: inizialmente server e browser si collegano con chiave simmetrica; grazie a questo il browser puo' generare una `secret` da mandare al server, certo che i messaggi siano cifrati, e che solo il server puo' decifrarlo con la sua chiave privata. Da quel momento in poi server e browser si scambiano messaggi crittografati simmetricamente mediante la `secret` creata dal browser.
Si usa questo stratagemma poiche' la crittografia antisimmetrica risulta pesante a livello computazionale, pertanto potrebbe ledere le prestazioni del server.


Per crittografare vi sono vari algoritmi, taluni invertibili(quali base_64url) altri invece non invertibili(HMAC ad esempio). In questo contesto ho trovato una parte interessante in PHP, ovvero gli **Initialization Vector**. Per specifiche meglio vedere uno dei miei `public/Test/Encryption`.


### JWT, JWS e JWE

See [https://medium.facilelogin.com/jwt-jws-and-jwe-for-not-so-dummies-b63310d201a3](https://medium.facilelogin.com/jwt-jws-and-jwe-for-not-so-dummies-b63310d201a3)


### Tokens e JWT (OAuth2)

sono un metodo alternativo, ma non una complete sostituzione, all'uso delle `sessioni`. Per anni i browser per mantenere traccia usavano le sessioni: il server php crea un file con tutte le variabili di sessioni e lo salva, settando tra i Cookies un sessionID. Grazie a questo, se l'utente si ricollega al sito inviandogli i Cookies, qualora il server trovi tra questi il sessionID, recuperera' il file salvato e potra' quindi riabilitare la sessione. Questo permette di eludere il fatto che HTTP sia un protocollo `stateless`, ma al contempo rende il sito attaccabile.
Tipici attacchi sono `CRSF`(cross site forgery), `Man in the Middle` e `XSS`. Con una buona politica sui cookies questi sono parzialmente eliminabili.

Tuttavia ormai viviamo in un contesto in cui non e' presente solo il browser, ma anche device quali smartphone, tablet, web app. Pertanto per rendere universale e sicuro l'accesso, tra le opzioni `statless` che piu mi hanno colpito, trovo `JWT`, e due sistemi che la sfruttano: `JWS` e `JWE`. La sua utilita' e' che e' uno standard, semplice da implementare e i cui dati sono trasmessi in maniera molto leggera, risparmiando cosi' banda e tempi, in piu risulta compatibile con OAuth2(che in principio non e' molto sicuro).

Per utilizzare JWT vi sono vari metodi, ad esempio alcuni utilizzano `access token` e `refresh token`, altri un solo token. Il token puo' anche essere `self-contained`.

La mia strategia, in linea di principio, sara' quella di registrate un utente in modo sicuro mediante connessione HTTPS: in questo modo posso anche passare con tranquillita' la **secret** e salvarla nel device/broser: a tal proposito ho letto che il `localstorage` e' molto insicuro, pertanto conviene utilizzare cookies con opzioni https-only. Successivamente ad ogni richiesta il server passa un nuovo token che servira' al client per una successiva connessione: se il token passato nell'header `Authentication: Bearer ...` e' uguale a quello che il server ha memorizzato, allora il server reputa tutto attendibile, altrimenti elimina il token e attende che il clienti rieffettui l'autenticazione.

In genere si consiglia di separare la logica di autenticazione (form di login) e la firma (verifica di autenticita') mediante token: in questo modo la piattaforma risulta **scalabile** tra piu server (in quanto stateless) e anche predisposta per scambio di token per **OAuth2**.


Per questo argomento risultano interessanti:

http://stackoverflow.com/questions/3487991/why-does-oauth-v2-have-both-access-and-refresh-tokens/12885823#12885823

https://stormpath.com/blog/token-auth-spa

#### JWT

da https://securedb.co/community/jwt-vs-jws-vs-jwe/

`JWT` claims possono essere inviati via JWS(ignature) payload o via JWE(ncrypt) payload, pertanto quello che tipicamente trovo come JWT in realta' e' l'implementazione JWS.

#### JWS

`JWS` serve come schema per la firma digitale dei contenuti: The server signs the JWT and sends it to the client, say after successful user authentication. The server expects the client to send this JWS back to the server as part of the next request.

What if the client we're dealing with is rogue? That's where the signature comes in. Signature brings Integrity (remember the Confidentiality, Integrity, Availability triad?) and Authentication  into the equation. In other words, server can be sure that the JWT claims inside the JWS it just received were not tampered with either by the rogue client or by a man-in-the-middle.

Server achieves this by validating the signature of the message to ensure that the claims were not tampered with by the client. If the server detects any kind of tampering, it can take appropriate action (deny the request or block the client etc.).

The client can also validate the signature. To do so, the client either needs server's secret (if the JWT signature is HMAC) or needs server's public key (if the JWT was digitally signed).

Pertanto posso utilizzare il token sia come auto-segnatura del server, che come metodo per il trasferimento tra client e server, qualora questi condividano una secret.

#### JWE

`JWE` scheme encrypts the content instead of signing it. The content being encrypted here are JWT claims. JWE, thus brings Confidentiality. The JWE can be signed and enclosed in a JWS. Now, you get both encryption and signature (thus getting Confidentiality, Integrity, Authentication).


## Riassumo il TUTTO

sul web vi sono molti tutorial, e ciascuno ha delle piccole differenze di implementazione, pertanto per trovare una traduzione univoca dovrei leggere l' RFC ufficiale del JWT, cosa che non ho fatto.

In ogni caso per capire la logica dietro al concetto di `stateless` e token (che cosi' potrebbero sembrare come i `sessionID`) e' meglio che concepisca un flusso a 3:

* client
* server-token
* server-authentication

in cui cioe' ho due server: uno che svolge l'autenticazione dell'utente (ovvero che controlla il suo log up/log in) e l'altro server che e' quello con cui l'utente vuole dialogare. Con questa logica sostanzialmente sto pensando a OAuth2.

L'idea quindi e' che il client la primva volta contatta il server-token per il login/logup e questi reindirizza al server-authentication, che controlla l'attendibilita' dell'utente e manda riscontro al server-token. In questa fase i due server potrebbero a loro volta scambiare dei token per essere sicuri di dialogare tra fonti attendibili; inoltre spesso al token viene aggiunta una secret per poter cifrare il tutto (google fornishe app-key e app-secret ad esempio).
Il server-token una volta ricevuta conferma dal server-authentication predispone i suoi JWT-head e JWT-claims e se vuole usare JWS allora li codifica con una propria secret-key facendoli diventare la JWT-signature per creare il JWT-token complessivo e lo restituisce al client.
Il client nella chiamata successiva passa anche il JWT-token che il server-token gli aveva passato, o come Header o come Cookies ed e' qui che si verifica il trick:

a questo punto il server-token verifica la firma digitale del token con la sua secret, senza bisogno di accedere ad alcun DB o di restaurare alcuna Session!!! (prevenzione dei parameters tampering)

Questa sarebbe la sua utilita' in linea di principio: quindi il JWT con questa impostazione **serve al server-token per se stesso**: lui crea il token, lui lo verifica, lui lo ricrea se vuole! in aggiunta al JWT-claims potrei aggiungere un tokenId (jit) se salvassi nel DB del server-token o del server-authentication dati importanti: il `jit` mi potrebbe servire per verificare il token e recuperare l'utente dal DB.

Sempre con questo giro, il JWT non serve assolutamente a trasmettere i messaggi tra client e server! quest'ultima potrebbe essere verificata mediante algoritmo simmetrico se client e server si sono scambiati in modo sicuro una secret.

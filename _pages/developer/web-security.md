---
title:      Security
permalink:  /developer/web-security
---

La sicurezza web, ma non solo, consta di tanti piccoli accorgimenti e prevenzioni, per le quali bisogna mostrare accortezza.

I principali blocchi da affrontare riguardano autenticazione, persistenza e crittografia. Ciascuno ha degli standard che possono aiutare a capire come comportarsi.


GENERAL
-------

Some general concept:

- [Certificate Pinning](https://medium.com/@appmattus/android-security-ssl-pinning-1db8acb6621e)
- **XSS protection** like this php tool: [https://github.com/voku/anti-xss](https://github.com/voku/anti-xss)


### Secure saltz

Lecture to [https://crackstation.net/hashing-security.htm](https://crackstation.net/hashing-security.htm)


### Crittografia

vi sono sostanzialmente due tipi:

* **a chiave antisimmetrica**, in cui si fa uso di una chiave pubblica e una privata (ad esempio `SSH` e `HTTPS` mediante `rsa`)

* **a chiave simmetrica**, in cui i due utenti condividono la stessa `secret` e la usano per criptare testo ed eventualmente firmarlo (signed): ovvero mi passano due messaggi, uno originale e lo stesso ma crittografato; anch'io con la mia secret crittografo l'originale e di conseguenza sia io che l'utente abbiamo la stessa secret, pertanto dovrei sperare che sia chi io penso. In questo caso il messaggio e' visibile, pertanto non ha senso per mandare messaggi con informazioni sensibili.

Ad esempio `HTTPS` fa uso di un misto: inizialmente server e browser si collegano con chiave simmetrica; grazie a questo il browser puo' generare una `secret` da mandare al server, certo che i messaggi siano cifrati, e che solo il server puo' decifrarlo con la sua chiave privata. Da quel momento in poi server e browser si scambiano messaggi crittografati simmetricamente mediante la `secret` creata dal browser.
Si usa questo stratagemma poiche' la crittografia antisimmetrica risulta pesante a livello computazionale, pertanto potrebbe ledere le prestazioni del server.


Per crittografare vi sono vari algoritmi, taluni invertibili(quali base_64url) altri invece non invertibili(HMAC ad esempio). In questo contesto ho trovato una parte interessante in PHP, ovvero gli **Initialization Vector**. Per specifiche meglio vedere uno dei miei `public/Test/Encryption`.


Encrypt/Decrypt vs Sign/Verify
------------------------------

To have a good overview about single key and key-pair generic usage, for example:

- grant that only the receivers can decrypt the message
- grant that the sender is trusted
- ca chain

you can view https://dzone.com/articles/encryption-and-signing (or the statisc assets in [../../assets/html/developer/web-security/Encryption and Signing - DZone Security.html](../../assets/html/developer/web-security/Encryption and Signing - DZone Security.html)).

Relatively the above article, I've performed some simple test using the follows:

````shell
# NOTA:
# usare la normale coppia di chiavi create con "ssh-keygen -t rsa" non va bene in quanto per l'encrypt si deve usare il formato openssl e non openssh (con cui vengono create le chiavi). 
openssl genrsa -out rsa_key.pri 2048; openssl rsa -in rsa_key.pri -out rsa_key.pub -outform PEM -pubout


### Esempio di codice per svolgere encrypt con pubblica e decript privata (comunicazione)

# Encrypt usando la chiave pubblica
echo 'Hi MacGuffin!' | openssl rsautl -encrypt -inkey rsa_key.pub -pubin -out secret.dat 
# Decrypt usando la chiave privata
openssl rsautl -decrypt -inkey rsa_key.pri -in secret.dat



# Esempio di codice per creare signature: in questo caso si cripta con la privata e si decripta con la pubblica (firma digitale o simile)
echo 'Hi MacGuffin!' | openssl rsautl -sign -inkey rsa_key.pri -out encrypted.txt
openssl rsautl -verify -pubin -inkey rsa_key.pub -in encrypted.txt 


#NOTA: 
# sono sicuro che tutto funzioni correttamente... ad es. se provo 
openssl rsautl -verify -pubin -inkey rsa_key.pub -in secret.dat
# non funziona perche' per creare secret.dat avevo gia' usato la pubblica!
# similmente per il seguente, ma con la privata:
openssl rsautl -decrypt -inkey rsa_key.pri -in encrypted.txt
````

> NOTE:
> 
> using standard `ssh-keygen -t rsa [...]` not works because rsa public and private key are in openssh format, 
> while the commands above expects keys in openssl format. 


### Root CA / CA chain / Certificate chain / Certificate CA signature

A CA (Certificate Authority) is an entity that issues digital certificates.

A CA Certificate is a certificate signed by issuer authority (with it's private key) and that can be verified with the issuer's authority public key.
The digital signature used has same logic of JWKs, and you can have a simple idea viewing [https://dzone.com/articles/encryption-and-signing#EncryptionAndSigning-CertificateAndCA](https://dzone.com/articles/encryption-and-signing#EncryptionAndSigning-CertificateAndCA).

In concrete, I create a CSR into server I'm starting the process to obtain a `Server Certificate: The End-Entity`
(note that while I create the CSR, I create also a PRIVATE KEY).
After all procedure, the Certificate Authority give me back: 

- a CA Certificate (Server Certificate)
    - containing subject (me), issuer and a public key (related to my private key)
    - this certificate is signed by the issuer with private key (in pair with the public key of issuer)
- intermediate certificates
    - always signed...
    - see chain of trusting
- Root Certificate
    - this is the only self-signed certificate
    - the browser TRUST this certificate (for example is in certificate store)
    - is very important that this certificate is well trusted, to ensure security's chain 

A curious thing is that the `Server Certificate` contains the public key related to my private key:
I've no created explicitly a public key wit CSR flow, so I think that CSR has all the informations to ensure the 
authority to create the public key providing the CSR itself 
(remember that when I create a CSR I gain a private key, that's why I think that CSR could be used to produce a related public key).

To have a better explanation you can read:

- [https://sectigostore.com/blog/what-is-an-ssl-certificate-chain-how-does-it-work/](https://sectigostore.com/blog/what-is-an-ssl-certificate-chain-how-does-it-work/), 
  well done and clear, but pay attention: the image "SSL CHAIN OF TRUST", the last two blocks related to "The signature can be verified [...]" are incorrects 
- [https://www.venafi.com/blog/how-do-certificate-chains-work](https://www.venafi.com/blog/how-do-certificate-chains-work)
- [https://knowledge.digicert.com/solution/SO16297.html](https://knowledge.digicert.com/solution/SO16297.html), just to see the chain's picture
- [https://blog.keyfactor.com/certificate-chain-of-trust](https://blog.keyfactor.com/certificate-chain-of-trust), another good explanation

At the end... some video:

- [https://www.youtube.com/watch?v=heacxYUnFHA&ab_channel=DaveCrabbe](https://www.youtube.com/watch?v=heacxYUnFHA&ab_channel=DaveCrabbe), fast: only 17min
- [https://www.youtube.com/watch?v=qXLD2UHq2vk&ab_channel=DaveCrabbe](https://www.youtube.com/watch?v=qXLD2UHq2vk&ab_channel=DaveCrabbe), 21min
- [https://www.youtube.com/watch?v=q1OF_0ICt9A&ab_channel=MITOpenCourseWare](https://www.youtube.com/watch?v=q1OF_0ICt9A&ab_channel=MITOpenCourseWare), didactic, not seen but seem a math lesson, so could be interesting :)

### Authorization and Authentication

Is it importanto to note that jwt tokens could be used both for **Authentication** and **Authorization**.

For **Authentication** you colud use some standard protocol like **OpenID**, 
while for **Authorization** you can use for example **OAuth 2.0** or similar.



TOKENS
------

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

Esplicazione di validazione del token:

- https://auth0.com/docs/tokens/json-web-tokens/validate-json-web-tokens
 

#### JWS

`JWS` serve come schema per la firma digitale dei contenuti: The server signs the JWT and sends it to the client, say after successful user authentication. The server expects the client to send this JWS back to the server as part of the next request.

What if the client we're dealing with is rogue? That's where the signature comes in. Signature brings Integrity (remember the Confidentiality, Integrity, Availability triad?) and Authentication  into the equation. In other words, server can be sure that the JWT claims inside the JWS it just received were not tampered with either by the rogue client or by a man-in-the-middle.

Server achieves this by validating the signature of the message to ensure that the claims were not tampered with by the client. If the server detects any kind of tampering, it can take appropriate action (deny the request or block the client etc.).

The client can also validate the signature. To do so, the client either needs server's secret (if the JWT signature is HMAC) or needs server's public key (if the JWT was digitally signed).

Pertanto posso utilizzare il token sia come auto-segnatura del server, che come metodo per il trasferimento tra client e server, qualora questi condividano una secret.

Explanation about of JWKs:

- https://www.baeldung.com/spring-security-oauth2-jws-jwk

Fully example of jws with jwks in node:

- https://auth0.com/blog/navigating-rs256-and-jwks/

#### JWE

`JWE` scheme encrypts the content instead of signing it. The content being encrypted here are JWT claims. JWE, thus brings Confidentiality. The JWE can be signed and enclosed in a JWS. Now, you get both encryption and signature (thus getting Confidentiality, Integrity, Authentication).

Example not fully working, but to have an idea of the encryption's workflow:

- https://stackoverflow.com/questions/47720701/how-to-generate-and-validate-jwe-in-node-js

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


#### JWT, JWS, JWE, JWKs: aggiornamento 20230223

In parole povere, JWS e JWE sono due standard che permettono di firmare e cifrare rispettivamente i dati. JWT e' un formato di dati che usa JWS e JWE per firmare e cifrare i dati.

Inoltre sono presenti le rappresentazioni:

- **Compact**, ovvero il normale token/stringa che siamo abituati a vedere negli urls (ovvero i base-64 separati dai periods "."); di fatto e' questa quella a cui in genere ci si riferisce
- **JSON serialization**, che e' un plain JSON strutturato con payload e "signatures"; puo' essere `flatten` e non avere l'array "signatures", e avere signature ed header come proprieta' base del json

In `JSON Serialization` e' possibile avere piu' di una signature, per cui si puo' avere un token firmato e cifrato, oppure firmato e firmato, oppure cifrato e cifrato, ecc.
La cosa importante e' che la signature e' come se fosse pensata per "receipt", quindi la validazione non avviene verificato la firma di ogni elemento "signatures": 
mi basta che solo una funzioni (quella relativa alla mia chiave). 
Per meglio capirci, non e' come una "trusted chain", in cui il certificato e' valido solo se tutta la chain di trusting e' valida,
questo perche' ogni signature e' indipendente dalle altre "sorelle", mentre in una chain di certificati ogni elemento contiene riferimenti (e quindi dipedenze) a quello precedente.

La `JSON Serialization` puo' essere utile se si vuole avere un token che sia firmato/cifrato e verificabile/consumabile da piu' destinatari (recipients), 
come scritto [qui](https://grimoire.carcano.ch/blog/json-web-token-jwt/#more-448).

> Al di la' di altri dettagli, in `jws` l'array e' `signatures`, mentre in `jwe` l'array e' `recipients`.


Per gli scopi (ipotizziamo chiavi asimmetriche):

#### JWS

- il token viene generato con la chiave privata e quindi verificato con la chiave pubblica da chi lo riceve; 
- serve per avere trusting della sorgente (se la signature e' valida vuol dire che effettivamente e' stato emesso da quella sorgente specifica che io identifico con la pubblica); 
- non protegge i dati

#### JWE

- il payload del token viene criptato con la chiave pubblica e quindi chi la riceve lo decripta con la chiave privata (quindi in teoria dovrebbe essere chi riceve a generare la coppia di chiavi e mandarmi la pubblica)
- il payload e' protetto e puo' essere letto solo da chi ha la chiave privata
- in questo caso pero' non si puo' verificare la sorgente perche' la chiave pubblica potrebbe essere consegnata a piu' persone (quindi per protezione, in teoria la pubblica non dovrebbe essere)

#### JWKS (JSON Web Key Set)

E' un formato per esporre le chiavi pubbliche, in modo che possano essere usate da altri consumer. E' sostanzialmente un array di JWK (JSON Web Key), ovvero un JSON che contiene le informazioni di una chiave pubblica.
Di base le chiavi pubbliche possono essere esposte per chi dovra' verificare il JWS, mentre le private per chi dovra' decriptare il JWE.
Dal mio punto di vista NON dovrebbero mai essere esposte le chiavi private, perche' altrimenti chiunque potrebbe decriptare il JWE recuperando la chiave privata esposta...

Se si ha bisogno di garantire protezione dei dati e trusting della sorgente, allora si dovrebbe usare JWS e JWE, 
in particolare potrebbe essere il payload del JWE a includere il JWS (nota che decifrare un JWE e' piu' dispendioso/lento di verificare un JWS).

Per ulteriori dettagli leggere [questo pdf](/assets/developer/web-security/jwt-handbook-v0_14_1.pdf).



AUTHORIZATION HEADER | TOKEN | HASHING | ENCRYPTING
---------------------------------------------------

Some good lecture:

- [VERY GOOG EXPLANATION OAUTH - TOKEN ACCESS](https://medium.com/@darutk/oauth-access-token-implementation-30c2e8b90ff0)

- [Authorization Types](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Authorization), like `Basic`, `Bearer` and so on

- [Tokens on OAuth 0](https://auth0.com/docs/tokens)

- [HTTP AUTHENTICATION FRAMEWORK](https://developer.mozilla.org/en-US/docs/Web/HTTP/Authentication)



2FA/MFA
-------

MFA like google authenticator... there's various types of Algorithms for MFA, so you can read some information here:

- [HOTP/TOTP](https://security.stackexchange.com/questions/35157/how-does-google-authenticator-work)

- [Here an example code](https://medium.com/@tilaklodha/google-authenticator-and-how-it-works-2933a4ece8c2)

You should always store in papero or in other manner the `CODE` provided when you associate the account to your authenticator
(inteending the recover code).

Generally you can use the qr code.

Very good article related to backup and mfa transfer [https://www.protectimus.com/blog/google-authenticator-backup/](https://www.protectimus.com/blog/google-authenticator-backup/):

- Backup codes
- Saving screenshots of the secret keys -> could be the QR code!
- Programmable hardware token


Note about `seed`:

> the QR code is the permanent secret key (seed), used to generate one-time passwords according to the TOTP algorithm. The app scans the QR code and saves this secret key. 
> Then the app will use the secret key and the current time interval to generate one-time passwords.
> If you save the secret key, you’ll create exactly the same token next time. 
> That’s why it is so important to store the saved QR codes in a reliable place.

I've tested:

1. saved qr code picture

2. scan that picture with another MFA authenticator app

3. logged in to the service with the code getted from the authenticator app whose seed is obtained via qr code picture scanned

4. and... **all goes right!!!**


https://grimoire.carcano.ch/blog/json-web-token-jwt/#more-448


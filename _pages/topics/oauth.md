---
title:      OAuth
permalink:  /topics/oauth/
---


### BITBUCKET


Test di `OAuth` su `Bitbucket`, utile per la gestione delle `API`, ad esempio per la notifica dello stato di una build.


#### Basic Auth

Per le api e' possibile svoglere un auth classico, utilizzando come credenziali username e password:


````
curl -u <user>:<pass> https://bitbucket.org/2.0/repositories/<repo owner>/<repo slug>
````

Ma naturalmente e una cosa __brutta brutta brutta!__.

E' preferibile l'`OAuth2`.


#### OAuth2

Dai settings del proprio account e' possibile creare un `consumer` `OAuth`, 
che di fatto e' come se fosse l'autorizzazione verso un servizio; infatti e' obbligatorio specificare un `Redirect Url`,
di fatto l'url del servizio a cui consentiremo l'accesso.

Una volta generato ci ritroviamo una `key` e una `secret`: la `key` di fatto e' come se fosse il `client id`,
ed e' lei che viene ad essere sostanzialmente considerato come l'account del servizio di cui sopra. 

Per poterle utilizzare vi sono vari modi che sono spiegati a 
[https://developer.atlassian.com/bitbucket/api/2/reference/meta/authentication#basic-auth](https://developer.atlassian.com/bitbucket/api/2/reference/meta/authentication#basic-auth)

Non li tratto tutti, ma ho fatto delle prove e riporto qua alcune considerazioni interessanti:

##### Authorization Code Grant (4.1)

Devo da prima fare una richiesta (ad es da browser)

````
https://bitbucket.org/site/oauth2/authorize?client_id=<oauth_client_id(key)>&response_type=code
````

https://bitbucket.org/site/oauth2/authorize?client_id=Dgv2uhy2g9jnFdF4dH&response_type=code

Se l'url risulta valido, allora `bitbucket` mi chiede l'autorizzazione (appunto come se fosse svolta da un servizio esterno),
e una volta che confermo l'accesso vengo rediretto al `redirect url` specificato prima. 
Per testarlo senza "sbattimenti" ho usato un `redirect_url` ottentuto via `ngirok`: dopo la conferma di cui sopra, 
bitbucket ritorna a quell'indirizzo aggiungendo la query string `code`:

````
https://f2b5ad6d.ngrok.io/?code=xyztuv
````

A questo punto tramite il `code` posso garantire manualmente l'accesso al `servizio (client_id come key)`: 

 ````
curl -X POST -u "<oauth_key>:<oauth_secret>" \
  https://bitbucket.org/site/oauth2/access_token \
  -d grant_type=authorization_code -d code=<code via redirect>
````
(vedro' che il mio consumer ora ha un `grant permission` nella sezione `OAuth integrated applications`).

Dalla chiamata di cui sopra, ottengo:

````
{"access_token": "<token...>", "scopes": "repository:write", "expires_in": 7200, "refresh_token": "<refresh_token>", "token_type": "bearer"}
````

E da questo momento mi basta usare l'access token. Quindi non e' necessario avere un vero servizio, ma solo usare questo trick con `ngrok`,
per ottenere l'access token, e da questo momento posso sempre utilizzare la coppia `oauthkey:oauthsecret` per chiamare le **API**!

Esempi:


Settare lo stato di una build:

````bash
curl -u oauthkey:oauthsecret -H "Content-Type: application/json" -X POST https://api.bitbucket.org/2.0/repositories/<user>/<repo>/commit/e7727901c82798268ba9418af99465194d8c91ac/statuses/build -d @file.json
````

Dove `file.json` e'

````json
{
    "state": "FAILED",
    "key": "REPO-MASTER-key",
    "name": "REPO-MASTER-42-name",
    "url": "https://bamboo.example.com/browse/REPO-MASTER-42",
    "description": "Changes by John Doe"
}
````

avere la lista:

````bash
curl -u oauthkey:oauthsecret https://api.bitbucket.org/2.0/repositories/<user>/<repo>
````

Con questo ritorno i token (altro tipo di OAuth):

````
curl -u oauthkey:oauthsecret https://bitbucket.org/site/oauth2/access_token
````

> La cosa bella del comando qua sopra, e' che posso rifarla tante volte perche' l'autenticazione con `oauthkey:oauthsecret`
> non scade mai (a meno che non venga revocata dai `settings`), quindi mi basta svolgere la procedura di conferma del servizio
> una volta sola e basta!  (vedi sopra)


REFS
----

- [authentication](https://developer.atlassian.com/bitbucket/api/2/reference/meta/authentication#basic-auth), tipi di autenticazione `bitbucket`
- [oauth](https://developer.atlassian.com/cloud/bitbucket/oauth-2/?utm_source=%2Fstatic%2Fbitbucket%2Fconcepts%2Foauth2.html&utm_medium=302), simile a quello sopra
- [oauth-on-bitbucket-cloud](https://confluence.atlassian.com/bitbucket/oauth-on-bitbucket-cloud-238027431.html), per bitbucket cloud




---
layout:     default
title:      React Apps
permalink:  /topics/react-app/
---

### REACT NATIVE

Per creare semplici app si puo' utilizzare `React Native`, tenendo pero' presente che allo stato attuale (Maggio 2018)
questa teconologia risulta ancora in fase di sviluppo, con cambi anche drastici tra una release e l'altra.


### Tools

E' consigliabile utilizzare `react-native` (**Nodejs**) in congiunta a:

 
- [Expo](https://expo.io/), per avere un feedback immediato anche su Device

- `react-redux`, per la gestione di uno stato globale che verra' aggiornato

- [http://graphql.org/learn/queries/](http://graphql.org/learn/queries/) come interfaccia API

- [https://www.apollographql.com/](https://www.apollographql.com/)

- [https://sentry.io/signup/](https://sentry.io/signup/) , sistema di logging



#### REDUX

Per sfruttare al meglio `redux` l'app verra' strutturata suddividendo le `Componenti` in due grandi categorie:

- `dumb` , ovvero componenti presentazionali pure che utilizzano solo proprieta'.

- `smart` , estendono le `dumb` e contengono parte della logica di buisness: esse sono componenti che vengono connesse allo `store` via `Redux`.


Le `dumb` **NON DEVONO** contenere alcuna logica di Business: servono solo da interfaccia, 
in questo modo potranno essere riutilizzate anche in altre applicazioni.   

Le `smart` invece si connettono tramite `redux` allo `store`, di conseguenza loro provvederanno ad aggiornare le `proprieta` della componente `dumb`, e tramite il **dispatcher** dello `store` 
propagheranno i cambiamenti di stato dell'applicazione mediante `actions`.

> Se non di default, ricordarsi che per utilizzare il `dispatcher` e' necessario settare la middleware `redux-thunk`.
> 
> Il `dispacher` risulta necessario in caso di operazioni asyncrone come chiamate `ajax` o `fetch`. 



### Refs


**Costruzione app**

- [React Native: chat tutorial + EXPO](https://github.com/jevakallio/react-native-chat-tutorial)
- [React With JWT tokens](https://auth0.com/blog/secure-your-react-and-redux-app-with-jwt-authentication/)
- [Listener Middleware](https://medium.com/@alexandereardon/the-middleware-listener-pattern-better-asynchronous-actions-in-redux-16164fb6186f) , 
proposta su come utilizzare Midlleware e separazione di responsabilita' sulle api


**Nozioni generali**

- [Ecma 6 - Symbols](https://www.keithcirkel.co.uk/metaprogramming-in-es6-symbols/)
- [Funzionamento Middleware thunk](https://github.com/reactjs/redux/blob/master/docs/advanced/Middleware.md)
- [Redux e Thunk](https://medium.com/@stowball/a-dummys-guide-to-redux-and-thunk-in-react-d8904a7005d3)
- [Redux Doc + Middleware return](https://redux.js.org/advanced/async-actions), esempi della documentazione ufficiale di redux, 
che spiega l'utilizzo dei valori ritornati dalle middleware.
- [Webpack + Express (1)](https://blog.hellojs.org/setting-up-your-react-es6-development-environment-with-webpack-express-and-babel-e2a53994ade) , spiega bene il concetto
- [Webpack + Express (2)](https://medium.com/@johnstew/webpack-hmr-with-express-app-76ef42dbac17) , spiega bene alcuni dettagli

Da questa [issue](https://github.com/GuillaumeSalles/redux.NET/issues/48) si evince il sunto delle middleware:

#### con `next(action)` 
si fa in modo che l'azione venga successivamente passata alla middleware successiva e cosi' via fino a raggiungere i `reducers` che aggiorneranno lo stato.

#### con `return`
The `return value` from the middlewares **bubbles up** through the middleware stack, and is ultimately accessible as the `return value` of `Store.Dispatch()`. 
For example, if one of the middlewares returns `null`, the return value of `Store.Dispatch()` will also be null, whereas if all middlewares return `next(action)`, then the return value will be the returned value of `next` midlleware.

Quindi se una middleware ritorna una promessa, e tutte le precedenti middleware ritornano `next(action)`, allora viene ritornata la Promessa!
Per esempi vedere la documentazione sopra citata (_middleware return_). 



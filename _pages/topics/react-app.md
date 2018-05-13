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



**Nozioni generali**

- [Ecma 6 - Symbols](https://www.keithcirkel.co.uk/metaprogramming-in-es6-symbols/)
- [Funzionamento Middleware thunk](https://github.com/reactjs/redux/blob/master/docs/advanced/Middleware.md)
- [Redux e Thunk](https://medium.com/@stowball/a-dummys-guide-to-redux-and-thunk-in-react-d8904a7005d3)
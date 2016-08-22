---
title:      Java
permalink:  /topics/java/
---

Raccolta info utili su Java


Exception Signature
--------------------

Consta nella differenza tra il mettere l'exeption nella "signature" della funzione, oppure all'interno della funzione stessa. Nello specifico se l' **Exception** viene inserito **nella signature del metodo** allora forzo il compilatore a controllare che nello stack chiamante il metodo vi sia almeno una volta un blocco `try/catch` per sollevare/catturare l'errore esposto dalla signature stessa.

Diviene uno strumento comodo per imporre controlli espliciti sul metodo, qualora venga sollevato un errore.

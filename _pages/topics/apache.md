---
layout:     default
title:      Apache
permalink:  /topics/apache/
---


### ORDER DENY,ALLOW

In apache2 the directive `order` is very important to establish what requests can access to sorces and what not.
 
To better understand this, see the article:
 
- [https://docstore.mik.ua/orelly/linux/apache/ch05_06.htm](https://docstore.mik.ua/orelly/linux/apache/ch05_06.htm)

And remember that from version `2.4` of apache, you can have troubleshoots in maintan deprecated `allow` and `denied` instructions, 
despite newest `Require all granded` instruction.


### Multi-Thread vs. Multi-Processing

In apache you can use `mpm`, which is a tool to use PHP via separate processes.

In this context is important to know the advantages/disadvantages of using multi-thread instead multi-processes.

See:

- [http://erpbasic.blogspot.it/2012/03/difference-between-multithreading-and.html](http://erpbasic.blogspot.it/2012/03/difference-between-multithreading-and.html)
- [http://techdifferences.com/difference-between-multiprocessing-and-multithreading.html](http://techdifferences.com/difference-between-multiprocessing-and-multithreading.html)


### Optimize Apapche Configurations

- [http://www.hostingtalk.it/ottimizzazione-di-apache-dall-analisi-ai-parametri_-c000000ut/](http://www.hostingtalk.it/ottimizzazione-di-apache-dall-analisi-ai-parametri_-c000000ut/)
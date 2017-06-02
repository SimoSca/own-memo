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
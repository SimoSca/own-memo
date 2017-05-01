---
title:      JMETER
permalink:  /tools/jmeter/
---

tool per check di network, in particolare utilizzato per chiamate http.

#### Sites:

- [https://ppettitau.wordpress.com/2012/11/25/posting-a-json-request-with-jmeter/](https://ppettitau.wordpress.com/2012/11/25/posting-a-json-request-with-jmeter/)


#### Test Recorder

to simulate navigation via proxy without set all by hand, you can use the `Http test script Recorder`. 
Just see:

	[https://www.digitalocean.com/community/tutorials/how-to-use-jmeter-to-record-test-scenarios](https://www.digitalocean.com/community/tutorials/how-to-use-jmeter-to-record-test-scenarios)

> WorkBench Item must be saved apart (if desidered). 
> After it is saved, you may add it to any test plan that you have open by using the "Merge" menu item, and selecting your saved WorkBench.




#### See Test

interesting method to run test via CLI or ANT and see the results:

- [https://www.blazemeter.com/blog/5-ways-launch-jmeter-test-without-using-jmeter-gui](https://www.blazemeter.com/blog/5-ways-launch-jmeter-test-without-using-jmeter-gui)




Settings
========

### Cookies

since out of box the `CookieManager` remember JUST ONE cookie (that is one set-cookie in header response), in sites with multiple cookies (like CICERONE), I need to perform this:

1 - find `jmeter.properties` file (in macbook at `/usr/local/Cellar/jmeter/3.0/libexec/bin/jmeter.properties`) 
2 - to this file add the option `CookieManager.check.cookies=false`

and restart JMeter.


### SSL

since JMeter has trouble with not public ssl cert, you need to start jmeter as follow:

````
jmeter -Djsse.enableSNIExtension=false
````

and all works.

I suggest to enable this by default, i.e. redefingn jmeter command in `.bash_profile` aliases.




RECORD PROXY
============

JMeter has two parts of configuration:

- mean panel
- workbench section


the first contains the heart of the application, while the second is used to work in example with `Http(s) script record`, that works as proxy.

> Both configuration part can be saved as separated config files, so remember to save both, not only what in `main panel`!!!!

#### My work is 

1 - add a `Thread Group` in `mean panel` and inside Group add a `Recording Controller` listener: this will be the target of the test script I'll create in Workbench.

2 - in `workbench` add a `Http(s) Test Script Record` and inside this configure the domains, prot, url patterns and `Recording Controller`: this is substantially a proxy server that saves all matching pattern as Http Requesto into the selected `Recording Controller` created into `main panel`. Remember to save `Save selection as...` in order to not lost your configuration.

3 - configure your browser to use localhost proxy at the setted port. **IMPORTANT** : with `Google Chrome` browser you can use `Postman` to make all wanted request (POST,GET,PUT and so on), and all this request will be recorded into your listener!

4 - navigate in the web to make all request you want record

5 - stop the proxy recorder (in Workbench)

6 - **FINALLY** you can view all your web requesto into the Recording Controller in main panel

> HINT: I navigate in many sites and record too much links... so after stop the recorder I clean useless `Http Request` stored into `Recording Controller`



#### Header Manager

You can use a `Header Manager` foreach `Http Request` or set one for all suceeded Request. for example in a tree like

````
_ Http Request 1
 \_ Header Manager 1
|
_ Header Manager 2
|
_ Http Request 2
|
_ Http Request 3

````

the `Header Manager 1` is inside `Http Request 1` and therefore it affect only the Header of `Http Request 1`, instead `Header Manager 2` is BEFORE and in the SAME LEVEL of `Http Request 2` and `Http Request 3`, therefore `Header Manager 2` 
will affect the header of `Http Request 2` and `Http Request 3`.

That is: `Header Manager` influence the request as the flow of a river, starting from top to bottom (the chronological steps).


JWT and Logint Auth
===================

Good tutorials:

- [https://guide.blazemeter.com/hc/en-us/articles/207421705-How-to-use-JMeter-for-Login-Authentication](https://guide.blazemeter.com/hc/en-us/articles/207421705-How-to-use-JMeter-for-Login-Authentication)

- [http://www.testingexcellence.com/jmeter-tutorial-testing-rest-web-services/](http://www.testingexcellence.com/jmeter-tutorial-testing-rest-web-services/)


PROXY
=====

- [https://guide.blazemeter.com/hc/en-us/articles/207420545-BlazeMeter-Recorder-Mobile-](https://guide.blazemeter.com/hc/en-us/articles/207420545-BlazeMeter-Recorder-Mobile-)
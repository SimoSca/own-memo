Enomis Libr
===========

example of library that use joomla `autoloader` in `psr-0` style.

Usage example

````
// register loader (i.e. via plugin system onAfterInitialise )
JLoader::registerNamespace('En', JPATH_LIBRARIES . '/enomis');
// call test method
\En\Mytest::say();
````
# PathType - Unix path manipulation

[![Build Status](https://api.travis-ci.org/rubendg/pathtype.png?branch=master)](http://travis-ci.org/rubendg/pathtype)

An OO library for Unix path manipulation. The properties of a path absolute/relative file or directory
are reflected in the type and invalid operations (like appending an absolute directory path to an
relative file path) are simply not possible by construction.

The library is heavily inspired by the Haskell [pathtype](http://hackage.haskell.org/package/pathtype) library, but
is not meant to be a port in anyway. Here is the type hierachy:


![Type hierarchy](https://github.com/rubendg/pathtype/raw/master/doc/types.png)


A quick example. 

Using the factory function `Path::from` we can produce any of the types in the bottom
of the diagram. It takes a well-formed path string and a flag indicating whether the path
is a directory or a file (since this cannot actually be determined from the path in all cases). 

```php
$path = '/etc/apache2/site-enabled';
$sitesEnabled = Path::from($path, is_dir($path));

function newApacheConfig(AbsDir $sitesEnabled, $name) {
   mkdir($sitesEnabled
   ->append('sites-enabled')
   ->append($name)
   ->get());
}

newApacheConfig($sitesEnabled);
```

One of the added benefits besides the path manipulation functions is that
we can now use type hinting in order to express that *newApacheConfig* expects
an absolute directory path. 

Notes:

- The filesystem is never touched by the library.
- No tilde (~) support. Let the OS resolve the tilde to the actual home directory before using the library.

Warning: this library is not battle tested

Similar:

- http://hackage.haskell.org/package/pathtype
- https://github.com/eloquent/pathogen

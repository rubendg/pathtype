# PathType - Unix path manipulation

[![Build Status](https://api.travis-ci.org/rubendg/pathtype.png?branch=master)](http://travis-ci.org/rubendg/pathtype)

An OO library for Unix path manipulation. The properties of a path absolute/relative file or directory
are reflected in the type and invalid operations (like appending an absolute directory path to an
relative file path) are simply not possible by construction.

The library is heavily inspired by the Haskell [pathtype](http://hackage.haskell.org/package/pathtype) library, but
is not meant to be a port in anyway.

A quick example. 

Using a type hint we can express that the *newApacheConfig* function only
works if it gets and *AbsDir* (short for absolute directory).

```php
$path = '/etc/apache2/site-enabled';
$sitesEnabled = Path::fromPath($path, is_dir($path));

function newApacheConfig(AbsDir $sitesEnabled, $name) {
   mkdir($sitesEnabled
   ->append('sites-enabled')
   ->append($name)
   ->get());
}

newApacheConfig($sitesEnabled);
```

Notes:

- The filesystem is never touched by the library.
- No tilde (~) support. Let the OS resolve the tilde to the actual home directory before using the library.

Warning: this library is not battle tested

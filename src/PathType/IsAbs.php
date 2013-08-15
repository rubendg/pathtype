<?php
/*
 * This file is part of the php-partial-constructor package.
 * (c) Ruben Alexander de Gooijer <rubendegooijer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PathType;

use \InvalidArgumentException;
use \PathType\AbsDir;
use \PathType\Path;

trait IsAbs {

   
   /**
    * /tmp/a - /tmp = ./a
    * 
    * /tmp/a - /tmp/a = .
    * 
    * /tmp/a - /tmp/b = undefined
    * 
    * /tmp/a/../a - /tmp/a/ = ./tmp ?
    * @param type $parent
    */
   public function rel($parent) {
      if(  is_string($parent) ) {
         $parent = AbsDir::create($parent);
      }
      
      $diff = self::diff($this->normalizedPath, $parent->normalizedPath)
         ->getOrThrow(
            new InvalidArgumentException(sprintf('Cannot convert an absolute to a relative directory path using: %s', $parent->get()))
         );
  
      return Path::arrayToPathString($diff, false, $this->hasTrailingSlash);
   }
   
   public function abs() {
      return $this;
   }

   
}
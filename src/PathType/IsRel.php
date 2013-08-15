<?php
/*
 * This file is part of the php-partial-constructor package.
 * (c) Ruben Alexander de Gooijer <rubendegooijer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PathType;

use \PathType\AbsDir;
use \PathType\Path;
use \PathType\TypeError;
use \ReflectionClass;

trait IsRel {
   
   public function abs($parent, $toFile) {
      if(  is_string($parent) ) {
         $parent = AbsDir::create($parent);
      }
      
      if(!$parent instanceof AbsDir) {
         throw new TypeError(
            sprintf(
               'Cannot convert relative directory to absolute path given a path of type: %s', 
               (new ReflectionClass($parent))->getName()
            )
         );
      }
      
      $path = Path::combine($parent->get(), $this->get());
      
      if($toFile) {
         return AbsFile::create($path);
      }
      
      return AbsDir::create($path);
   }
   
   public function rel() {
      return $this;
   }
   
}

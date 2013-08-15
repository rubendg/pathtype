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

trait IsRel {
   
   public function abs($parent, $toFile) {
      if(  is_string($parent) ) {
         $parent = AbsDir::create($parent);
      }
      
      if(!$parent instanceof AbsDir) {
         throw new InvalidArgumentException('Could not convert a relative into an absolute file path based on a parent that isn\'t an AbsDir');
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

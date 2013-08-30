<?php
/*
 * This file is part of the php-partial-constructor package.
 * (c) Ruben Alexander de Gooijer <rubendegooijer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PathType;

trait IsUpable {

   public function parent() {
      return $this->parentN(1);
   }
   
   function parentN($n) {
      if(empty($this->normalizedPath)) {
         return $this;
      }
      
      $actualN = count($this->normalizedPath);
      if($n >= $actualN) {
         if($this->isAbsolute) {
            return AbsDir::create(Path::arrayToPathString([], $this->isAbsolute, $this->hasTrailingSlash));
         }
         
         return RelDir::create(Path::arrayToPathString([], $this->isAbsolute, $this->hasTrailingSlash));
      }
      
      $upN = array_slice($this->normalizedPath, 0, $actualN - $n);
      
      if($this->isAbsolute) {
         return AbsDir::create(Path::arrayToPathString($upN, $this->isAbsolute, $this->hasTrailingSlash));
      }
      return RelDir::create(Path::arrayToPathString($upN, $this->isAbsolute, $this->hasTrailingSlash));
   }
   
}

?>

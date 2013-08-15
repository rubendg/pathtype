<?php
/*
 * This file is part of the php-partial-constructor package.
 * (c) Ruben Alexander de Gooijer <rubendegooijer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PathType;

use \RuntimeException;

trait IsAppendable {
   
   public function append($path, $isFile = false) {
      if(is_object($path) && $path instanceof IAbsPath) {
         throw new RuntimeException('Invalid operation: appending an absolute path to another absolute path');
      }
      
      $path = RelDir::create((string) $path);
      
      if($isFile) {
         return AbsFile::create(Path::combine($this->get(), $path->get()));
      }
      
      return static::create(Path::combine($this->get(), $path->get()));
   }
   
   
}
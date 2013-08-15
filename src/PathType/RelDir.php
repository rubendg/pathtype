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

class RelDir extends Path implements IRelPath, IDirPath {
   
   use IsRel { abs as private _abs; }
   use IsAppendable;
   
   public function __construct($path, array $normalizedPath) {
      if(!self::isRelative($path)) {
         throw new InvalidArgumentException('Expected a valid relative directory path.');
      }
      
      parent::__construct($path, $normalizedPath, false);
   }
      
   public function abs($parent) {
      return $this->_abs($parent, false);
   }

}
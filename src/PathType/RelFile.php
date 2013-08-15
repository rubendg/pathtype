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
use \PathType\Path;

class RelFile extends Path implements IRelPath, IFilePath {
   
   use IsRel { abs as private _abs; }
   
   public function __construct($path, array $normalizedPath) {
      if(!self::isRelative($path) || self::endsWithDirectorySeparator($path)) {
         throw new InvalidArgumentException('Expected a valid relative file path.');
      }
      
      parent::__construct($path, $normalizedPath, false);
   }
   
   public function abs($parent) {
      return $this->_abs($parent, true);
   }
   
}
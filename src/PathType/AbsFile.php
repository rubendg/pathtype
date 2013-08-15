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
use \PathType\RelFile;

class AbsFile extends Path implements IAbsPath, IFilePath {
   
   use IsAbs { rel as private _rel; }
   
   public function __construct($path, array $normalizedPath) {
      if(!self::isAbsolute($path) || self::endsWithDirectorySeparator($path)) {
         throw new InvalidArgumentException('Expected a valid absolute file path.');
      }
      
      parent::__construct($path, $normalizedPath, true);
   }

   public function rel($parent) {
      return RelFile::create($this->_rel($parent));
   }
   
}
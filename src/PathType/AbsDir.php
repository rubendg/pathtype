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
use \PathType\AbsFile;
use \PathType\IAbsPath;
use \PathType\RelDir;

class AbsDir extends Path implements IAbsPath, IDirPath {

   use IsAbs {
      rel as private _rel;
   }
   
   use IsAppendable;

   public function __construct($path, array $normalizedPath) {
      if(!self::isAbsolute($path)) {
         throw new InvalidArgumentException('Not an absolute directory path');
      }

      parent::__construct($path, $normalizedPath, true);
   }

   public function rel($parent) {
      return RelDir::create($this->_rel($parent));
   }
   
   public function toFile() {
      return AbsFile::create($this->get());
   }

}

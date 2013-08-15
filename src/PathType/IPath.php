<?php
/*
 * This file is part of the php-partial-constructor package.
 * (c) Ruben Alexander de Gooijer <rubendegooijer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PathType;

interface IPath {
   
   public function get();
   
   public function parent();
   
   public function parentN($n);
   
   public function map(callable $f);
   
   public function length();
   
   public function last();
   
   public function head();
   
}


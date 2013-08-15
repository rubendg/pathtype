<?php
/*
 * This file is part of the php-partial-constructor package.
 * (c) Ruben Alexander de Gooijer <rubendegooijer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PathType;

use \PHPUnit_Framework_TestCase;

abstract class AbstractPathTest extends PHPUnit_Framework_TestCase {

   function assertIsNone($actual) {
      return $this->assertInstanceOf('PHPOption\None', $actual);
   }
   
   function assertIsSome($actual) {
      return $this->assertInstanceOf('PHPOption\Some', $actual);
   }
   
   function assertSome($actual, callable $callable) {
      $this->assertIsSome($actual);
      return $callable($actual->get());
   }
   
}

?>

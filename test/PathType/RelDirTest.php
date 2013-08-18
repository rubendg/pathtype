<?php
/*
 * This file is part of the php-partial-constructor package.
 * (c) Ruben Alexander de Gooijer <rubendegooijer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PathType;

use \PathType\AbstractPathTest;
use \PathType\Path;
use \PathType\RelDir;

class RelDirTest extends AbstractPathTest {

   /**
    * @expectedException InvalidArgumentException
    */
   public function testInvalidConstruct1() {
      RelDir::create('/a');
   }
   
   public function testAppend() {
      $dir = RelDir::create('a');

      $this->assertInstanceOf('\PathType\RelDir', $dir->append('b'));
      $this->assertInstanceOf('\PathType\RelDir', $dir->append('b/c'));
   }
   
   public function testToAbs() {
      $dir = RelDir::create(Path::arrayToPathString(['b'], false, true));
      
      $v = $dir->abs(DIRECTORY_SEPARATOR . 'a');
      
      $this->assertInstanceOf('\PathType\AbsDir', $v);
      $this->assertEquals(Path::arrayToPathString(['a', 'b'], true, true), $v->get());
   }
   
   public function testDotFile() {
      $this->assertInstanceOf('\PathType\RelDir', Path::from('.foo', false));
   }
}

?>

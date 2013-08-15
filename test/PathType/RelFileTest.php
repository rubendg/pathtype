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
use \PathType\RelFile;

class RelFileTest extends AbstractPathTest {
   
   /**
    * @expectedException InvalidArgumentException
    */
   public function testInvalidConstruct1() {
      RelFile::create(DIRECTORY_SEPARATOR);
   }
   
   /**
    * @expectedException InvalidArgumentException
    */
   public function testInvalidConstruct2() {
      RelFile::create(Path::arrayToPathString(['a'], true));
   }
   
   /**
    * @expectedException InvalidArgumentException
    */
   public function testInvalidConstruct3() {
      RelFile::create(Path::CURRENT_DIRECTORY . DIRECTORY_SEPARATOR);
   }
   
   public function testGet() {
      $f = RelFile::create(Path::arrayToPathString(['a'], false));
      $this->assertEquals('a', $f->get());
   }
   
   public function testToAbs() {
      $f = RelFile::create(Path::arrayToPathString(['b'], false));
      
      $abs = $f->abs(Path::arrayToPathString(['a'], true));
      $this->assertInstanceOf('\PathType\AbsFile', $abs);
      $this->assertEquals(Path::arrayToPathString(['a', 'b'], true), $abs->get());
   }
   
}
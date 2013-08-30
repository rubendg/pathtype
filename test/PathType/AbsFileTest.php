<?php
/*
 * This file is part of the php-partial-constructor package.
 * (c) Ruben Alexander de Gooijer <rubendegooijer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PathType;

use \PathType\AbsFile;
use \PathType\AbstractPathTest;
use \PathType\Path;

class AbsFileTest extends AbstractPathTest {
   
   /**
    * @expectedException InvalidArgumentException
    */
   public function testInvalidConstruct1() {
      AbsFile::create('');
   }
   
   /**
    * @expectedException InvalidArgumentException
    */
   public function testInvalidConstruct2() {
      AbsFile::create(Path::arrayToPathString(['a'], false));
   }
   
   /**
    * @expectedException InvalidArgumentException
    */
   public function testInvalidConstruct3() {
      AbsFile::create(DIRECTORY_SEPARATOR);
   }
   
   public function testGet() {
      $f = AbsFile::create(Path::arrayToPathString(['a'], true));
      $this->assertEquals(Path::arrayToPathString(['a'], true), $f->get());
   }
   
   public function testToRel() {
      $f = AbsFile::create(Path::arrayToPathString(['a','b'], true));
      
      $rel = $f->rel(Path::arrayToPathString(['a'], true));
      $this->assertInstanceOf('\PathType\RelFile', $rel);
      $this->assertEquals(Path::arrayToPathString(['b'], false), $rel->get());
   }
   
   public function testParent() {
      $f = AbsFile::create(Path::arrayToPathString(['a','b'], true));
      $this->assertInstanceOf('\PathType\AbsDir', $f->parent());
      $this->assertInstanceOf('\PathType\AbsDir', $f->parent()->parent());
      $this->assertInstanceOf('\PathType\AbsDir', $f->parent()->parent()->parent());
      $this->assertEquals('/', $f->parent()->parent()->parent()->get());
   }
   
   
}
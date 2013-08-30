<?php
/*
 * This file is part of the php-partial-constructor package.
 * (c) Ruben Alexander de Gooijer <rubendegooijer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PathType;

use \PathType\AbsDir;
use \PathType\AbstractPathTest;
use \PathType\Path;

class AbsDirTest extends AbstractPathTest {

   /**
    * @expectedException InvalidArgumentException
    */
   public function testInvalidConstruct1() {
      AbsDir::create('a');
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testInvalidConstruct2() {
      AbsDir::create(Path::arrayToPathString(['.', 'a'], false));
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testInvalidConstruct3() {
      AbsDir::create(Path::arrayToPathString(['..', 'a'], false));
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testHomeDir() {
      AbsDir::create(Path::arrayToPathString(['~', 'a'], false, true));
   }
   
   public function testConstruct() {
      $a = Path::arrayToPathString(['foo', 'a'], true);
      $ab = Path::arrayToPathString([$a, 'b'], true);
      $abc = Path::arrayToPathString([$ab, 'c'], true);

      AbsDir::create($a);
      AbsDir::create($ab);
      AbsDir::create($abc);
      AbsDir::create(Path::arrayToPathString([$abc, '..', 'c'], true));
   }

   public function testAppend() {
      $dir = AbsDir::create(Path::arrayToPathString(['a'], true));

      $this->assertInstanceOf('\PathType\AbsDir', $dir->append('b'));

      $absf = $dir->append('foo.txt', true);

      $this->assertInstanceOf('\PathType\AbsFile', $absf);
      
      $absd = $dir->append('.foo');
      
      $this->assertInstanceOf('\PathType\AbsDir', $absd);
      
   }
   
   public function testAppendWithObject() {
      $dir = AbsDir::create(Path::arrayToPathString(['a'], true));
      $this->assertInstanceOf('\PathType\AbsDir', $dir->append(RelDir::create('foo')));
   }
   
   /**
    * @expectedException \RuntimeException
    */
   public function testAppendFailure() {
      $dir = AbsDir::create(Path::arrayToPathString(['a'], true));
      $this->assertInstanceOf('\PathType\AbsDir', $dir->append(AbsDir::create('/foo')));
   }

   public function testToRel() {
      $dir = AbsDir::create(Path::arrayToPathString(['a', 'b'], true, true));

      $v = $dir->rel(DIRECTORY_SEPARATOR . 'a');

      $this->assertInstanceOf('\PathType\RelDir', $v);
      $this->assertEquals(Path::arrayToPathString(['b'], false, true), $v->get());
   }
   
   public function testParent() {
      $f = AbsDir::create(Path::arrayToPathString(['a','b'], true));
      $this->assertInstanceOf('\PathType\AbsDir', $f->parent());
      $this->assertInstanceOf('\PathType\AbsDir', $f->parent()->parent());
      $this->assertInstanceOf('\PathType\AbsDir', $f->parent()->parent()->parent());
      $this->assertEquals('/', $f->parent()->parent()->parent()->get());
   }

}

?>

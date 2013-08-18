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

class PathTest extends AbstractPathTest {
   
   public function testPathDiff() {
      $a = ['a'];
      $b = ['b'];
      $this->assertIsNone(Path::diff($a, $b));
      
      $a = ['a'];
      $b = ['tmp', 'a'];
      $this->assertIsNone(Path::diff($a, $b));
      
      $a = ['tmp', 'b'];
      $b = ['tmp', 'a'];
      $this->assertIsNone(Path::diff($a, $b));
      
      $a = ['tmp', 'a'];
      $b = ['tmp'];
      $this->assertSome(Path::diff($a, $b), function($v) {
         $this->assertEquals(['a'], $v);
      });
      
      $a = ['a'];
      $b = ['a'];
      $this->assertSome(Path::diff($a, $b), function($v) {
         $this->assertEquals([], $v);
      });
      
      $a = ['tmp', 'a'];
      $b = ['tmp', 'a'];
      $this->assertSome(Path::diff($a, $b), function($v) {
         $this->assertEquals([], $v);
      });
      
      $a = ['tmp', 'a', 'b'];
      $b = ['tmp', 'a'];
      $this->assertSome(Path::diff($a, $b), function($v) {
         $this->assertEquals(['b'], $v);
      });
   }
         
   public function testNormalize() {
      $p = '';
      $this->assertEquals('.', Path::normalize($p));
      
      $p = './';
      $this->assertEquals('./', Path::normalize($p));
      
      $p = './.';
      $this->assertEquals('./', Path::normalize($p));
      
      $p = '/';
      $this->assertEquals('/', Path::normalize($p));
      
      $p = 'a';
      $this->assertEquals('a', Path::normalize($p));
      
      $p = '/a/b';
      $this->assertEquals('/a/b', Path::normalize($p));
      
      $p = './a';
      $this->assertEquals('a', Path::normalize($p));
      
      $p = '../a';
      $this->assertEquals('../a', Path::normalize($p));
      
      $p = 'a/.';
      $this->assertEquals('a/', Path::normalize($p));
      
      $p = './a/.';
      $this->assertEquals('a/', Path::normalize($p));
      
      $p = '../../';
      $this->assertEquals('../../', Path::normalize($p));
      
      $p = '../../a';
      $this->assertEquals('../../a', Path::normalize($p));
      
      $p = '../../a/../';
      $this->assertEquals('../../', Path::normalize($p));
      
      $p = '../../a/../../';
      $this->assertEquals('../../../', Path::normalize($p));
      
      $p = '/a/b/../b';
      $this->assertEquals('/a/b', Path::normalize($p));
   }

   public function testToString() {
      $p = './foo';
      $this->assertEquals('foo', (string) Path::from($p, true));
   }
   
   public function testParentAbsPath() {
      $dir = AbsDir::create(Path::arrayToPathString(['a', 'b', 'c'], true, true));

      $b = $dir->parent();
      $this->assertInstanceOf('\PathType\AbsDir', $b);
      $this->assertEquals(Path::arrayToPathString(['a', 'b'], true, true),
                                                  $b->get());

      $a = $b->parent();
      $this->assertInstanceOf('\PathType\AbsDir', $a);
      $this->assertEquals(Path::arrayToPathString(['a'], true, true), $a->get());

      $root = $a->parent();
      $this->assertInstanceOf('\PathType\AbsDir', $root);
      $this->assertEquals(DIRECTORY_SEPARATOR, $root->get());

      $root = $root->parent();
      $this->assertInstanceOf('\PathType\AbsDir', $root);
      $this->assertEquals(DIRECTORY_SEPARATOR, $root->get());
   }

   public function testParentRelPath() {
      $dir = RelDir::create(Path::arrayToPathString(['a', 'b', 'c'], false, true));
      
      $b = $dir->parent();
      $this->assertInstanceOf('\PathType\RelDir', $b);
      $this->assertEquals(Path::arrayToPathString(['a','b'], false, true), $b->get());
      
      $a = $b->parent();
      $this->assertInstanceOf('\PathType\RelDir', $a);
      $this->assertEquals(Path::arrayToPathString(['a'], false, true), $a->get());
      
      $root = $a->parent();
      $this->assertInstanceOf('\PathType\RelDir', $root);
      $this->assertEquals(Path::CURRENT_DIRECTORY, $root->get());
      
      $root = $root->parent();
      $this->assertInstanceOf('\PathType\RelDir', $root);
      $this->assertEquals(Path::CURRENT_DIRECTORY, $root->get());
   }
   
   public function testMapAbs() {
      $dir = AbsDir::create(Path::arrayToPathString(['a', 'b'], true, true));

      $i = 0;
      $dir = $dir->map(function($v) use(&$i) {
               return++$i;
            });

      $this->assertInstanceOf('\PathType\AbsDir', $dir);
      $this->assertEquals(Path::arrayToPathString(['1', '2'], true, true),
                                                  $dir->get());
   }

   public function testParentNAbs() {
      $dir = AbsDir::create(Path::arrayToPathString(['a', 'b', 'c'], true, true));
      
      $a = $dir->parentN(2);
      $this->assertInstanceOf('\PathType\AbsDir', $a);
      $this->assertEquals(Path::arrayToPathString(['a'], true, true), $a->get());
   }
   
   public function testMapRel() {
      $dir = RelDir::create(Path::arrayToPathString(['a', 'b'], false, true));
      
      $i = 0;
      $dir = $dir->map(function($v) use(&$i) { return ++$i; });
      
      $this->assertInstanceOf('\PathType\RelDir', $dir);
      $this->assertEquals(Path::arrayToPathString(['1','2'], false, true), $dir->get());
   }
   
}

?>

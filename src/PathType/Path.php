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
use \PathType\AbsFile;
use \PathType\RelDir;
use \PathType\RelFile;
use \PhpOption\None;
use \PhpOption\Some;

abstract class Path implements IPath {
   
   use IsUpable;
   
   const CURRENT_DIRECTORY = '.';
   const PARENT_DIRECTORY = '..';
   
   protected $path;
   
   protected $normalizedPath;
   
   protected $hasTrailingSlash;
   
   protected $isAbsolute;
   
   public function __construct($path, array $normalizedPath, $isAbsolute) {
      $this->path = $path;
      $this->normalizedPath = $normalizedPath;
      $this->isAbsolute = $isAbsolute;
      $this->hasTrailingSlash = self::hasTrailingSlash($path);
   }
   
   public static function create($path) {
      return new static($path, self::normalizeToArray($path));
   }
   
   public static function from($path, $isFile) {
      $isRelative = self::isRelative($path);
      
      if($isRelative && $isFile) {
         return RelFile::create($path);
      }
      
      if($isRelative && !$isFile) {
         return RelDir::create($path);
      }
      
      $isAbsolute = self::isAbsolute($path);
      
      if($isAbsolute && $isFile) {
         return AbsFile::create($path);
      }
      
      if($isAbsolute && !$isFile) {
         return AbsDir::create($path);
      }
   }
   
   public function get() {
      if($this->isAbsolute) {
         return self::arrayToPathString($this->normalizedPath, true, $this->hasTrailingSlash);
      }
      
      return self::arrayToPathString($this->normalizedPath, false, $this->hasTrailingSlash);
   }
   
   protected static function isRelative($path) {
      return !self::isAbsolute($path);
   }
   
   protected static function isAbsolute($path) {
      return self::startsWithDirectorySeparator($path);
   }
   
   private static function startsWithDirectorySeparator($path) {
      return stripos($path, DIRECTORY_SEPARATOR) === 0;
   }
   
   protected static function endsWithDirectorySeparator($path) {
      return substr($path, -1) === DIRECTORY_SEPARATOR;
   }

   
//   Resolves . and .. elements in a path array with directory names there
//   must be no slashes, empty elements, or device names (c:\) in the array
//   (so also no leading and trailing slashes - it does not distinguish
//   relative and absolute paths)
   private static function normalizeArray(array $parts, $allowAboveRoot) {
      // if the path tries to go above the root, `up` ends up > 0
      $up = 0;
      for($i = count($parts) - 1; $i >= 0; $i--) {
         $last = $parts[$i];
         if($last === self::CURRENT_DIRECTORY) {
            array_splice($parts, $i, 1);
         } else if($last === self::PARENT_DIRECTORY) {
            array_splice($parts, $i, 1);
            $up++;
         } else if($up) {
            array_splice($parts, $i, 1);
            $up--;
         }
      }
      
      // if the path is allowed to go above the root, restore leading ..s
      if($allowAboveRoot) {
         for(; $up--; $up) {
            array_unshift($parts, self::PARENT_DIRECTORY);
         }
      }
      
      return $parts;
   }
   
   private static function normalizeToArray($path) {
      if(empty($path)) {
         $path = self::CURRENT_DIRECTORY;
      }
      
      $isAbsolute = self::isAbsolute($path);
      return self::normalizeArray(self::pathStringToArray($path), !$isAbsolute);
   }
   
   public static function normalize($path) {
      $normalized = self::normalizeToArray($path);
      $hasTrailingSlash = self::hasTrailingSlash($path);
      $isEmpty = empty($normalized);
      
      if(self::isRelative($path)) {
         if($isEmpty && $hasTrailingSlash) {
            return self::CURRENT_DIRECTORY . DIRECTORY_SEPARATOR;
         } elseif($isEmpty) {
            return self::CURRENT_DIRECTORY;
         }
         return self::arrayToPathString($normalized, false, $hasTrailingSlash);
      }
      
      if(self::isAbsolute($path)) {
         if($isEmpty) {
            return DIRECTORY_SEPARATOR;
         } 
         return self::arrayToPathString($normalized, true, $hasTrailingSlash);
      }
   }

   private static function hasTrailingSlash($path) {
      return self::endsWithDirectorySeparator($path) || (substr($path, -2) === DIRECTORY_SEPARATOR . self::CURRENT_DIRECTORY);
   }

   public static function combine($a, $b) {
      $hasTrailingSlash = self::hasTrailingSlash($a);
      return $a . ($hasTrailingSlash ? '' : DIRECTORY_SEPARATOR) . $b;
   }
   
   public static function arrayToPathString(array $parts, $isAbsolute, $hasTrailingSlash = false) {
      $pre = $isAbsolute ? DIRECTORY_SEPARATOR : (empty($parts) ? self::CURRENT_DIRECTORY : '');
      $post = !empty($parts) && $hasTrailingSlash ? DIRECTORY_SEPARATOR : '';
      return $pre . implode(DIRECTORY_SEPARATOR, $parts) . $post;
   }
   
   private static function pathStringToArray($path) {
      return array_values(array_filter(explode(DIRECTORY_SEPARATOR, $path), function($p) {
         return trim($p) !== '';
      }));
   }
   
   public static function diff(array $a, array $b) {
      if(empty($a)) {
         return None::create();
      }

      if(count($a) < count($b)) {
         return None::create();
      }

      if(empty($b)) {
         return Some::create($a);
      }

      //remove items pairwise, until they become inequal
      while(($v1 = array_shift($b)) !== NULL) {
         $v2 = array_shift($a);
         if($v1 !== $v2) {
            //shift the non-matching item back on
            array_unshift($b, $v1);
            break;
         }
      }

      //items remaining in the right-hand-side
      if(!empty($b)) {
         return None::create();
      }

      return Some::create($a);
   }
   
   public function map(callable $callable) {
      return static::create(
         self::arrayToPathString(
            array_map($callable, $this->normalizedPath), 
            $this->isAbsolute, 
            $this->hasTrailingSlash
         )
      );
   }
   
   public function length() {
      return count($this->normalizedPath);
   }
   
   public function __toString() {
      return $this->get();
   }
   
   public function last() {
      $length = $this->length();
      
      if($length <= 0) 
         return null;
      
      return $this->normalizedPath[$length - 1];
   }
   
   public function head() {
      $length = $this->length();
      
      if($length <= 0) 
         return null;
      
      return $this->normalizedPath[0];
   }
   
}

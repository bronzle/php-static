<?php
require_once('include/PHPS_TestCase.php');
class NormalizeTest extends PHPS_TestCase {
  public function testNormalizeURI() {
    $this->assertSame('/a/nice/clean/path', normalize_uri('//a//nice///clean\\path//'));
  }
  public function testNormalizePath() {
    $path_parts = array('a', 'nice', 'clean', 'path');
    $clean = DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $path_parts);
    $dirty = '/';
    $separators = array('/', '\\');
    foreach ($path_parts as $part) {
      $count = mt_rand(2, 4);
      $dirty .= $part;
      for ($i = 0; $i < $count; $i++) {
        $dirty .= $separators[mt_rand(0, 1)];
      }
    }
    $this->assertSame($clean, normalize_path($dirty));
  }
  public function testNormalizePathCannonical() {
    $this->assertSame(implode(DIRECTORY_SEPARATOR, array(__DIR__, 'data', 'dir-real')), normalize_path(implode(DIRECTORY_SEPARATOR, array(__DIR__, 'data', 'dir-fake')), true));
  }
}

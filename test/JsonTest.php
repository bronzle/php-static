<?php
require_once('include/PHPS_TestCase.php');
class JsonTest extends PHPS_TestCase {
  public function testJsonReturn() {
    $this->assertSame('{"a":["b","c"],"d":{"e":6},"g":true,"h":false,"i":null}', json(array('a' => array('b', 'c'), 'd' => array('e' => 6), 'g' => true, 'h' => false, 'i' => null)));
    $this->assertSame(json_encode(array('a' => array('b', 'c'), 'd' => array('e' => 6), 'g' => true, 'h' => false, 'i' => null)), json(array('a' => array('b', 'c'), 'd' => array('e' => 6), 'g' => true, 'h' => false, 'i' => null)));
  }
}
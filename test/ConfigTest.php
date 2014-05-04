<?php
require_once('include/PHPS_TestCase.php');
class ConfigTest extends PHPS_TestCase {
  public function testConfigRead() {
    $this->assertNotEmpty(config());
  }
  public function testConfigDefault() {
    $this->assertNotEmpty(config('pages_root'));
  }
  public function testConfigJson() {
    $this->assertSame('value', config('test_param'));
  }
  public function testConfigDotAccess() {
    $this->assertSame('third', config('first.second'));
  }
  public function testDefaultReturnValue() {
    $this->assertNull(config('non-existant'));
    $this->assertTrue(config('non-existant', true));
  }
}

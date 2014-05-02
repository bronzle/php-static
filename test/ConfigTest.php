<?php
class ConfigTest extends PHPUnit_Framework_TestCase {
  public function setUp() {
    $_SERVER['REQUEST_URI'] = '/';
    $_SERVER['DOCUMENT_ROOT'] = __DIR__ . '/data';
    set_env('phps_app_doc_root', $_SERVER['DOCUMENT_ROOT'] = __DIR__ . '/data');
    run();
  }
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

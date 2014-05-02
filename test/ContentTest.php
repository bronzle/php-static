<?php
class ContentTest extends PHPUnit_Framework_TestCase {
  public function test_content_returns_content() {
    $GLOBALS['__content'] = 'hello world';
    $this->assertEquals('hello world', content());
  }
}
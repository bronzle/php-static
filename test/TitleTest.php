<?php
class TitleTest extends PHPUnit_Framework_TestCase {
  public function setUp() {
    $t = &title();
    $t = null;
  }
  public function testDefaultTitle() {
    $this->assertEquals(null, title());
  }
  public function testSetTitle() {
    $this->assertEquals('title text', title('title text'));
  }
  public function testTitleCalledTwice() {
    title('actual title');
    $this->assertEquals('actual title', title('not actual title'));
  }
}

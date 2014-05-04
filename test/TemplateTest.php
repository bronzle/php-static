<?php
require_once('include/PHPS_TestCase.php');
class TemplateTest extends PHPS_TestCase {
  public function setUp() {
    $this->template_dir = __DIR__ . '/data/pages';
    $l = &layout();
    $l = null;
  }
  public function testResetLayoutValue() {
    layout('asdf');
    $l = &layout();
    $l = null;
    $this->assertSame($this->template_dir . '/layout.php', locate_template($this->template_dir, layout(), false, false));
    $l = &layout();
    $l = null;
    $this->assertNull(locate_template($this->template_dir, layout(false), false, false));
  }
  public function testDefaultLayout() {
    $this->assertSame($this->template_dir . '/layout.php', locate_template($this->template_dir, layout(), false, false));
  }
  public function testAlternateLayout() {
    $this->assertSame($this->template_dir . '/other_layout.php', locate_template($this->template_dir, layout('other_layout'), false, false));
  }
  public function testResetLayoutLayout() {
    layout('other_layout');
    $this->assertSame($this->template_dir . '/layout.php', locate_template($this->template_dir, layout(null), false, false));
  }
  public function testNoLayout() {
    $this->assertNull(locate_template($this->template_dir, layout(false), false, false));
  }
  public function testFindTemplateFromUri() {
    $this->setUpRequest(__DIR__ . '/data', __DIR__ . '/data', '/index?asd=123');
    $this->assertSame('index', render(ltrim(request('uri_name'), '/'), array('str' => 'index'), false, false, false));
  }
  public function testContentReturnsContent() {
    $GLOBALS['__content'] = 'hello world';
    $this->assertSame('hello world', content());
  }
  public function testRender() {
    $this->assertSame('index', render('index', array('str' => 'index'), false, false, false));
  }
}

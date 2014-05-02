<?php
class LocateTemplateTest extends PHPUnit_Framework_TestCase {
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
    $this->assertSame(null, locate_template($this->template_dir, layout(false), false, false));
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
    $this->markTestIncomplete('Not implmented yet');
  }
}

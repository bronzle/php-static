<?php
require_once('include/PHPS_TestCase.php');
class BodyClassesTest extends PHPS_TestCase {
  public function testSetsABodyClass() {
    body_classes('class-name');
    $this->assertSame('class-name', body_classes());
    $this->assertSame('class-name another-class', body_classes('another-class'));
  }
  public function testAssignsMultiple() {
    $this->assertSame('one two three', body_classes('one', 'two', 'three'));
  }
  public function testRemovesDuplicates() {
    body_classes('a', 'b', 'c', 'b');
    $this->assertSame('a b c', body_classes());
    $this->assertSame('a b c', body_classes('a'));
  }
  public function testBodyClassReturnsReference() {
    body_classes('a', 'b', 'c', 'd');
    $classes = &body_classes(true);
    $this->assertSame(array('a', 'b', 'c', 'd'), body_classes(true));
    $classes[0] = 'z';
    $this->assertSame(array('z', 'b', 'c', 'd'), body_classes(true));
  }
  public function testBodyClassReset() {
    body_classes('a', 'b', 'c', 'd');
    $this->assertSame('', body_classes(false));
  }
  public function testReplaceBodyClass() {
    body_classes('a', 'b', 'c', 'd');
    $this->assertSame('w x y z', body_classes(false, 'w', 'x', 'y', 'z'));
  }
}
<?php
class HelpersTest extends PHPUnit_Framework_TestCase {
  public function setUp() {
    $this->array = array(
      'names' => array(
        'Byron',
        'Joe',
        'Roger'
      ),
      'positions' => array(
        'developer' => 'Byron',
        'owners' => array('Byron', 'Joe')
      ),
      'full_name' => 'PHP-Static'
    );
  }
  public function test_array_flatten() {
    $this->assertEquals(array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10), array_flatten(array(1, 2, 3, array(4, 5, 6), 7, array(8, array(9, 10)))));
    $this->assertEquals(array('a' => 1, 'b' => 2, 'c' => 3, 4, 5, 'x' => 24, 'w' => 23, 22), array_flatten(array('a' => 1, array('b' => 2), 'c' => 3, array('d' => array(4, 5)), 'z' => array('y' => array('x' => 24, 'w' => 23), 22))));
  }
  public function test_array_whitelist_keys() {
    $this->assertEquals(array('names' => array('Byron','Joe','Roger'), 'full_name' => 'PHP-Static'), array_whitelist_keys($this->array, 'names', 'full_name'));
    $this->assertEquals(array('names' => array('Byron','Joe','Roger'), 'full_name' => 'PHP-Static'), array_whitelist_keys($this->array, array('names', 'full_name')));
  }
  public function test_array_dot_access() {
    $this->assertEquals(array('Byron', 'Joe', 'Roger'), array_dot_access($this->array, 'names'));
    $this->assertEquals('Byron', array_dot_access($this->array, 'positions.developer'));
    $this->assertEquals(array('Byron', 'Joe'), array_dot_access($this->array, 'positions.owners'));
    $this->assertEquals('PHP-Static', array_dot_access($this->array, 'full_name'));

    $this->assertEquals(null, array_dot_access($this->array, 'invalid'));
    $this->assertEquals(null, array_dot_access($this->array, 'invalid.key'));
    $this->assertEquals('default', array_dot_access($this->array, 'invalid', 'default'));
  }
  public function test_string_until() {
    $this->assertEquals('asdf', string_until('asdf#1234', '#'));
    $this->assertEquals('asdf', string_until('asdf', '#'));
  }
}
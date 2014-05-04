<?php
require_once('include/PHPS_TestCase.php');
class RunTest extends PHPS_TestCase {
  public function testRunSetsDefaultEnv() {
    set_env('env', false);
    run();
    $this->assertSame('development', env('env'));
  }
  public function testRunAcceptsEnvParam() {
    run('test');
    $this->assertSame('test', env('env'));
    run(array('env' => 'blah'));
    $this->assertSame('blah', env('env'));
  }
}
<?php
require_once('include/PHPS_TestCase.php');
class ResposneTest extends PHPS_TestCase {
  public function testGetHttpCodeValue() {
    $this->assertSame('Not Found', get_http_code_value(404));
    $this->assertSame('Continue', get_http_code_value(100));
  }
  public function testSetHeader() {
    $this->markTestSkipped('This functionality requires redirects which aren\'t supported in PHP CLI');
  }
  public function testRedirect() {
    $this->markTestSkipped('This functionality requires redirects which aren\'t supported in PHP CLI');
  }
  public function testError() {
    $this->setExpectedException('Exception', 'Error: 404');
    error(404);

    // need to check how to test all the functionality of error -- including rendering correct page

    // $this->setExpectedException('Exception', '404');
    // error(404);
    // new MissingTemplate($type, $template, $locations)
    // new InvalidConfiguration($type, $item, $extra = null)
  }
  public function testSessionId() {
    $this->assertSame('', session_id());
    $session_id = session();
    $this->assertSame($session_id, session_id());
    $this->assertSame(config('session_name'), session_name());
  }
  public function testSessionValues() {
    $id = session();
    $this->assertSame(null, session('non-existant'));
    session('key', 'value');
    $this->assertSame('value', session('key'));
  }
}
<?php
require_once('include/PHPS_TestCase.php');
class RequestTest extends PHPS_TestCase {
  public function testUri() {
    $this->setUpRequest(__DIR__ . '/data', __DIR__ . '/data', '/?asd=123');
    $this->assertSame('/', request('root_uri'));
    $this->assertSame(__DIR__ . '/data', request('root_path'));
    $this->assertSame('/', request('uri'));
    $this->assertSame('/index', request('uri_name'));
    $this->assertSame(array('index'), request('uri_parts'));

    $this->setupRequest(__DIR__, __DIR__ . '/data', '/data?asd=123');
    $this->assertSame('/data', request('root_uri'));
    $this->assertSame(__DIR__ . '/data', request('root_path'));
    $this->assertSame('/', request('uri'));
    $this->assertSame('/index', request('uri_name'));
    $this->assertSame(array('index'), request('uri_parts'));
  }
  public function testPhpsRoot() {
    $this->assertSame(normalize_path(__DIR__ . '/../lib', true), request('phps.root'));
  }
  public function testUriParts() {
    $this->assertSame(array('index', 'foo', 'bar'), request('uri_parts'));
  }
  public function testQueryString() {
    $this->assertSame('asd=123', request('query_string'));
  }
  public function testUriExtension() {
    $config = &config();
    unset($config['redirect']);
    $this->setUpRequest(__DIR__ . '/data', __DIR__ . '/data', '/page.php?asd=123');
    $this->assertSame('php', request('extension'));
  }
  public function testUriExtensionRedirection() {
    $this->setUpRequest(__DIR__ . '/data', __DIR__ . '/data', '/page.php?asd=123');
    // these fail because we are using headers and cli doesn't really support that
    $this->markTestSkipped('This functionality requires redirects which aren\'t supported in PHP CLI');
    $this->assertSame('/page?asd=123', request('root_uri'));
    $this->assertSame('/page', request('uri'));
  }
  public function testEnv() {
    $this->assertSame('phpunit-bootstrap-value-env', env('phpunit-bootstrap-value'));
    $this->assertSame('phpunit-bootstrap-value-env', env('PHPUNIT-BOOTSTRAP-VALUE'));
    set_env('foo', 'bar');
    $this->assertSame('bar', env('foo'));
  }
  public function testParams() {
    $this->assertSame('phpunit-bootstrap-value-post', post('phpunit-bootstrap-value'));
    $this->assertSame('phpunit-bootstrap-value-get', get('phpunit-bootstrap-value'));
    $this->assertSame('phpunit-bootstrap-value-get', param('phpunit-bootstrap-value'));
  }
}

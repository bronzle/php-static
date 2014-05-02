<?php
class RequestTest extends PHPUnit_Framework_TestCase {
  public function setUp() {
    $this->origConfig = &config();
    $this->setupDefaultRequest();
  }
  public function tearDown() {
    $cfg = &config();
    $cfg = &$this->origConfig;
  }
  public function testUri() {
    $this->setUpRequest(__DIR__ . '/data', __DIR__ . '/data', '/?asd=123');
    $this->assertEquals('/', request('root_uri'));
    $this->assertEquals(__DIR__ . '/data', request('root_path'));
    $this->assertEquals('/', request('uri'));
    $this->assertEquals('/index', request('uri_name'));
    $this->assertEquals(array('index'), request('uri_parts'));

    $this->setupRequest(__DIR__, __DIR__ . '/data', '/data?asd=123');
    $this->assertEquals('/data', request('root_uri'));
    $this->assertEquals(__DIR__ . '/data', request('root_path'));
    $this->assertEquals('/', request('uri'));
    $this->assertEquals('/index', request('uri_name'));
    $this->assertEquals(array('index'), request('uri_parts'));
  }
  public function testPhpsRoot() {
    $this->assertEquals(normalize_path(__DIR__ . '/../lib', true), request('phps.root'));
  }
  public function testUriParts() {
    $this->assertEquals(array('index', 'foo', 'bar'), request('uri_parts'));
  }
  public function testQueryString() {
    $this->assertEquals('asd=123', request('query_string'));
  }
  public function testUriExtension() {
    $config = &config();
    unset($config['redirect']);
    $this->setUpRequest(__DIR__ . '/data', __DIR__ . '/data', '/page.php?asd=123');
    $this->assertEquals('php', request('extension'));
  }
  public function testUriExtensionRedirection() {
    $this->setUpRequest(__DIR__ . '/data', __DIR__ . '/data', '/page.php?asd=123');
    // these fail because we are using headers and cli doesn't really support that
    $this->markTestSkipped('This functionality requires redirects which aren\'t supported in PHP CLI');
    $this->assertEquals('/page?asd=123', request('root_uri'));
    $this->assertEquals('/page', request('uri'));
  }
  public function testEnv() {
    $this->assertEquals('phpunit-bootstrap-value-env', env('phpunit-bootstrap-value'));
    $this->assertEquals('phpunit-bootstrap-value-env', env('PHPUNIT-BOOTSTRAP-VALUE'));
    set_env('foo', 'bar');
    $this->assertEquals('bar', env('foo'));
  }
  public function testParams() {
    $this->assertEquals('phpunit-bootstrap-value-post', post('phpunit-bootstrap-value'));
    $this->assertEquals('phpunit-bootstrap-value-get', get('phpunit-bootstrap-value'));
    $this->assertEquals('phpunit-bootstrap-value-get', param('phpunit-bootstrap-value'));
  }
  private function setupDefaultRequest() {
    $this->setUpRequest(__DIR__ . '/data', __DIR__ . '/data', '/index/foo/bar?asd=123');
  }
  private function setUpRequest($document_root, $app_doc_root, $request_uri) {
    $_SERVER['DOCUMENT_ROOT'] = $document_root;
    set_env('phps_app_doc_root', $app_doc_root);
    $_SERVER['REQUEST_URI'] = $request_uri;
    $r = &request();
    $r = null;
    run();
  }
}

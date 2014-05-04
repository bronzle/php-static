<?php
class PHPS_TestCase extends PHPUnit_Framework_TestCase {
  public function setUp() {
    ob_start();
    $this->setupDefaultRequest();
    $this->origConfig = &config();
    $classes = &body_classes(true);
    $classes = array();
  }
  public function tearDown() {
    $cfg = &config();
    $cfg = &$this->origConfig;
    if (ob_get_length()) {
      ob_end_clean();
    }
  }
  protected function &getOuput() {
    $output = ob_get_contents();
    return $output;
  }
  protected function setupDefaultRequest() {
    $this->setUpRequest(__DIR__ . '/../data', __DIR__ . '/../data', '/index/foo/bar?asd=123');
  }
  protected function setUpRequest($document_root, $app_doc_root, $request_uri) {
    $_SERVER['DOCUMENT_ROOT'] = $document_root;
    set_env('phps_app_doc_root', $app_doc_root);
    $_SERVER['REQUEST_URI'] = $request_uri;
    $r = &request();
    $r = null;
    run();
  }
}
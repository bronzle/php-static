<?php
require_once('include/PHPS_TestCase.php');
class UrlTest extends PHPS_TestCase {
  public function testUrl() {
    $this->assertSame('/about/me', url('/about/me'));
    $this->assertSame('/about/me', url('about/me'));

    $this->setupRequest(__DIR__, __DIR__ . '/data', '/');
    $this->assertSame('/data/about/me', url('about/me'));
  }
  public function testAssetUrl() {
    $this->assertSame('/assets/images/test.jpg', asset_url('images', 'test.jpg'));
    $this->assertSame('/assets/images/ui/test.jpg', asset_url('images', 'ui/test.jpg'));

    $config = &config();
    $config['assets_images'] = 'i';
    $this->assertSame('/assets/i/test.jpg', asset_url('images', 'test.jpg'));
  }
  public function testUnConfiguredAssetUrl() {
    $this->assertSame('/assets/pdfs/test.pdf', asset_url('pdfs', 'test.pdf'));
  }
  public function testNoAssetType() {
    $this->assertSame('/assets/asset.ext', asset_url('asset.ext'));
    $this->assertSame('/assets/multiple/dirs/asset.ext', asset_url('multiple/dirs', 'asset.ext'));
  }
}
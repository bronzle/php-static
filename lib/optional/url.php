<?php
function url($path) {
  return normalize_uri(request('root_uri') . $path);
}
function asset_url($type, $path = null) {
  if (!$path) {
    $path = $type;
    $type = null;
  }
  $asset_url = config('assets_base');
  if ($type) {
    $asset_url .= '/' . config('assets_' . $type, $type);
  }
  return url($asset_url . "/$path");
}

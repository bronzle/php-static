<?php
function url() {
  $args = func_get_args();
  array_unshift($args, request('root_uri'));
  return normalize_uri($args);
}
function asset_url($type, $path = null) {
  $type = null;
  $path = func_get_args();
  if (count($path) > 1) {
    $type = $path[0];
    $path = array_slice($path, 1);
  }
  $asset_url = config('assets_base');
  if ($type) {
    $asset_url .= '/' . config('assets_' . $type, $type);
  }
  array_unshift($path, $asset_url);
  return url($path);
}

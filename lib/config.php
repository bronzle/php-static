<?php
function &config($key = null, $default = null) {
  static $config = null;
  if (!$config) {
    $config_file = __DIR__ . '/../config.json';
    $config = array(
      'default_layout'      => 'layout',
      'pages_root'          => 'pages',
      'error_root'          => 'errors',
      'assets_base'         => 'assets',
      'assets_css'          => 'css',
      'assets_js'           => 'js',
      'assets_images'       => 'images',
      'session_name'        => 'session_token',

/* optional */
      'controller_path'     => 'controllers',
      'default_controller'  => 'default',
      'error_controller'    => 'error',

      'flash_msg'           => 'flash_msg',

      'emails_root'         => 'emails'
    );
    if (file_exists($config_file)) {
      $json_data = json_decode(file_get_contents($config_file), true);
      if ($json_data === null) {
        throw InvalidConfiguration(MissingConfiguration::READ_ERROR, $config_file);
      }
      $config = array_merge($config, (array)$json_data);
    }
  }
  if ($key) {
    $keys = explode('.', $key);
    $config_at_level = &$config;
    $last = end($keys);
    foreach ($keys as $key) {
      if (isset($config_at_level[$key])) {
        if ($key === $last) {
          return $config_at_level[$key];
        }
        $config_at_level = &$config_at_level[$key];
      } else {
        break;
      }
    }
    return $default;
  }
  return $config;
}
function has_config($key, $children = array(), $throw = false) {
  $all = true;
  $missing = null;
  if (is_bool($children)) {
    $throw = $children;
    $children = array();
  }
  if (config($key) !== null) {
    if (is_array($children)) {
      foreach ($children as $child) {
        if (!$config("{$key}.{$child}") !== null) {
          $all = false;
          $missing = "{$key}.{$child}";
          break;
        }
      }
    }
  } else {
    $all = false;
    $missing = $key;
  }
  if (!$all) {
    if ($throw) {
      throw new MissingConfiguration(MissingConfiguration::MISSING_VALUE, $missing);
    } else {
      return false;
    }
  }
}

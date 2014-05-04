<?php
function &config($key = null, $default = null) {
  static $config = null;
  if (!$config) {
    $config = array(
      'default_layout'      => 'layout',
      'pages_root'          => 'pages',
      'error_root'          => 'errors',
      'assets_base'         => 'assets',
      'assets_css'          => 'css',
      'assets_js'           => 'js',
      'assets_images'       => 'images',
      'session_name'        => 'session_token',

      'run_request'         => true,

      'default_response_headers' => array(
        'Content-Type' => 'text/html; charset=utf-8'
      ),

/* optional */
      'controller_path'     => 'controllers',
      'default_controller'  => 'default',
      'error_controller'    => 'error',

      'flash_msg'           => 'flash_msg',

      'emails_root'         => 'emails',

      'redirect'            => array(
        'extensions'         => array('php', 'html', 'htm')
      )
    );
    $config_file = request('root_path') . '/config.json';
    if (file_exists($config_file)) {
      $json_data = json_decode(file_get_contents($config_file), true);
      if ($json_data === null) {
        throw new InvalidConfiguration(InvalidConfiguration::READ_ERROR, $config_file);
      }
      $config = array_merge($config, (array)$json_data);
    }
    $config_file = request('root_path') . '/config-' . env('env') . '.json';
    if (file_exists($config_file)) {
      $json_data = json_decode(file_get_contents($config_file), true);
      if ($json_data === null) {
        throw new InvalidConfiguration(InvalidConfiguration::READ_ERROR, $config_file);
      }
      $config = array_merge($config, (array)$json_data);
    }
  }
  if ($key) {
    $value = array_dot_access($config, $key, $default);
    return $value;
  }
  return $config;
}
function has_config($key) {
  $all = true;
  $missing = null;
  $children = array();
  $args = func_get_args();
  if (count($args) > 1) {
    if (is_bool(end($args))) {
      $throw = end($args);
      $children = array_flatten(array_slice($args, 1, -1));
    } else {
      $children = array_flatten(array_slice($args, 1));
    }
  }
  if (config($key) !== null) {
    if (is_array($children)) {
      foreach ($children as $child) {
        if (config("{$key}.{$child}") === null) {
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
      throw new InvalidConfiguration(InvalidConfiguration::MISSING_VALUE, $missing);
    } else {
      return false;
    }
  }
  return true;
}

<?php
function normalize_uri($path) {
  return '/' . trim(preg_replace(array('#/+#', '#\\\\+#'), array('/', '/'), $path), '/');
}
function normalize_path($path, $cannonical = false) {
  $path = DIRECTORY_SEPARATOR . trim(preg_replace(array('#[/\\\\]+#', '#' . DIRECTORY_SEPARATOR . '+#'), array(DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR), $path), DIRECTORY_SEPARATOR);
  if ($cannonical) {
    $path = realpath($path);
  }
  return $path;
}
function &request($key = null) {
  static $request = null;
  if (!$request) {
    $request = $_SERVER;
    $root_uri = normalize_uri(str_replace(realpath($_SERVER['DOCUMENT_ROOT']), '', realpath(env('phps_app_doc_root'))));
    $request = array_merge($request, array(
      'root_uri' => $root_uri,
      'root_path' => normalize_path($_SERVER['DOCUMENT_ROOT'] . $root_uri)
    ));
    $parts = explode('?', trim(preg_replace('#^' . $request['root_uri'] . '#i', '', $_SERVER['REQUEST_URI']), " \t\n\r\v\0/\\"), 2);
    if (count($parts) == 1) {
      $parts[] = null;
    }
    list($uri, $query_string) = $parts;
    $request['uri'] = $request['uri_name'] = '/' . $uri;
    $request['query_string'] = $query_string;
    if (isset($_GET['/' . $request['uri']])) {
      unset($_GET['/' . $request['uri']]);
    }
    if ($request['uri_name'] === '/') {
      $request['uri_name'] = '/index';
    }
    $request['uri_parts'] = array_values(array_filter(explode('/', $request['uri_name'])));
    $last_uri_part = end($request['uri_parts']);
    if (strpos($last_uri_part, '.') !== false) {
      list($file, $ext) = explode('.', $last_uri_part);
      $request['extension'] = $ext;
    } else {
      $request['extension'] = null;
    }
    $request['phps.root'] = __DIR__;
    $request['xhr'] = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest');
  }
  if ($key) {
    return $request[$key];
  }
  return $request;
}
function &env($key, $default = false) {
  $key = strtoupper($key);
  $var = getenv($key);
  if ($var !== false) {
    return $var;
  } elseif (function_exists('apache_getenv')) {
    $var = apache_getenv($key);
    if ($var !== false) {
      return $var;
    }
  }
  return $default;
}
function set_env($key, $value) {
  if ($value === false) {
    putenv(strtoupper($key));
  } else {
    putenv(strtoupper($key). '=' . $value);
  }
}
function &post($key = null, $default = '') {
  if (!$key) {
    return $_POST;
  } else {
    if (isset($_POST[$key])) {
      return $_POST[$key];
    }
    return $default;
  }
}
function &get($key = null, $default = '') {
  if (!$key) {
    return $_GET;
  } else {
    if (isset($_GET[$key])) {
      return $_GET[$key];
    }
    return $default;
  }
}
function &param($key = null, $default = '') {
  if (!$key) {
    $ret = array_merge(array(), $_POST, $_GET);
    return $ret;
  } else {
    if (isset($_GET[$key])) {
      return $_GET[$key];
    } elseif (isset($_POST[$key])) {
      return $_POST[$key];
    }
    return $default;
  }
}

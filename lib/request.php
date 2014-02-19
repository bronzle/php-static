<?php
function normalize_uri($path) {
  return preg_replace(array('#/+#', '#\\\\#'), array('/', '/'), $path);
}
function normalize_path($path, $cannonical = false) {
  $path = preg_replace(array('#[/\\\\]#', '#' . DIRECTORY_SEPARATOR . '+#'), array(DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR), $path);
  if ($cannonical) {
    $path = realpath($path);
  }
  return $path;
}
function &request($key = null) {
  static $request = null;
  if (!$request) {
    $request = $_SERVER;
    $root_uri = normalize_uri('/' . str_replace(realpath($_SERVER['DOCUMENT_ROOT']), '', realpath(PHPS_APP_DOC_ROOT)) . '/');
    $request = array_merge($request, array(
      'root_uri' => $root_uri,
      'root_path' => rtrim($_SERVER['DOCUMENT_ROOT'] . $root_uri, '/')
    ));
    $request['uri'] = $request['uri_name'] = trim(str_replace($request['root_uri'], '', $_SERVER['REQUEST_URI']), " \t\n\r\v\0/\\");
    if ($request['uri_name'] === '') {
      $request['uri_name'] = 'index';
    }
    $request['uri_parts'] = array_filter(explode('/', $request['uri_name']));
    $request['phps.root'] = __DIR__;
  }
  if ($key) {
    return $request[$key];
  }
  return $request;
}
function &env($key, $default = false) {
  $var = getenv(strtoupper($key));
  if ($var !== false) {
    return $var;
  }
  return $default;
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
    return array_merge(array(), $_POST, $_GET);
  } else {
    if (isset($_GET[$key])) {
      return $_GET[$key];
    } elseif (isset($_POST[$key])) {
      return $_POST[$key];
    }
    return $default;
  }
}

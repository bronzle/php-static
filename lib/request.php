  <?php
function normalize_uri($path) {
  return preg_replace('#/+#', '/', $path);
}
function &request($key = null) {
  static $request = null;
  if (!$request) {
    $request = $_SERVER;
    $root_uri = normalize_uri('/' . str_replace($_SERVER['DOCUMENT_ROOT'], '', dirname(dirname($_SERVER['SCRIPT_FILENAME']))) . '/');
    $request = array_merge($request, array(
      'root_uri' => $root_uri,
      'root_path' => rtrim($_SERVER['DOCUMENT_ROOT'] . $root_uri, '/')
    ));
    $request['uri'] = $request['uri_name'] = trim(str_replace($request['root_uri'], '', $_SERVER['REQUEST_URI']), " \t\n\r\v\0/\\");
    if ($request['uri_name'] === '') {
      $request['uri_name'] = 'index';
    }
    $request['uri_parts'] = array_filter(explode('/', $request['uri_name']));
  }
  if ($key) {
    return $request[$key];
  }
  return $request;
}
function &env($key, $default = false) {
  if (strtoupper($key) === 'ENV') {
    $var = getenv($key);
    if ($var !== false) {
      return $var;
    }
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

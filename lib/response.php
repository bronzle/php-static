<?php
function get_http_code_value($code) {
  $code = (string)$code;  
  static $error_code_values = array(
    '301' => 'Moved Permanently',
    '302' => 'Found',
    '404' => 'Not Found',
    '500' => 'Internal Server Error'
  );
  if (!isset($error_code_values[strval($code)])) {
    $error_code_values = json_decode(file_get_contents(__DIR__ . '/data/http_codes.json'), true);
  }
  return $error_code_values[$code];
}
// save headers to check them later?
function set_header($key, $value = null, $code = null) {
  if (is_numeric($key)) {
    $code = $key;
    $key = null;
  }
  if ($code !== null) {
    $error_code_value = get_http_code_value($code);
    header("HTTP/1.0 {$code} {$error_code_value}", true, $code);
  }
  if ($key) {
    if ($value) {
      header("{$key}: {$value}", true);
    } elseif ($key) {
      header($key, true);
    }
  }
  return isset($error_code_value) ? $error_code_value : null;
}
function error($code, $exception = null) {
  if (!config('run_request')) {
    if ($exception) {
      throw $exception;
    } else {
      throw new Exception('Error: ' . $code);
    }
  }
  $error_code_value = set_header($code);
  if (env('env') === 'development') {
    echo get_template_contents(__DIR__ . '/templates/developer_error.php', array('exception' => $exception, 'code_reason' => $error_code_value, 'code' => $code));
  } else {
    $show_default_page = true;
    $error_controller = config('error_controller');
    if ($error_controller) {
      if (function_exists('run_controller')) {
        if (run_controller($error_controller, array($code, $exception), array('error_' . $code, 'error'))) {
          $show_default_page = !Controller::$__rendered;
        }
      }
    }
    if ($show_default_page) {
      if (($template_path = locate_template(config('pages_root') . '/' . config('error_root'), 'error' . $code, false, false))) {
        $GLOBALS['__content'] = &get_template_contents($template_path, array('message' => $exception->getMessage()));
        echo render(layout(), array(), false, false, true);
      } else {
        if (file_exists(__DIR__ . '/templates/missing/' . $code . '.php')) {
          echo get_template_contents(__DIR__ . '/templates/missing/' . $code . '.php', array('exception' => $exception, 'code_reason' => $error_code_value));
        } else {
          echo get_template_contents(__DIR__ . '/templates/missing/default.php', array('exception' => $exception, 'code_reason' => $error_code_value, 'code' => $code));
        }
      }
    }
  }
}
function redirect($page, $query_string = false, $code = false) {
  if ($code === false && is_int($query_string)) {
    $code = $query_string;
    $query_string = false;
  }
  if ($code === false) {
    $code = 302;
  }
  if ($query_string === false || $query_string === null) {
    $query_string = '';
  } else {
    $query_string = '?' . $query_string;
  }
  if (!preg_match('#^\w+://#', $page)) {
    if (substr($page, 0, 1) !== '/') {
      $page = '/' . $page;
    }
    $proto = 'http' . (request('HTTPS') !== null ? 's' : '') .'://';
    $host = request('HTTP_HOST');
    $root = request('root_dir');
    $page = $proto . $host . $root . $page . $query_string;
  }
  set_header('Location', $page, $code);
  if (config('run_request')) {
    exit;
  }
}
function &session($key = null, $value = null) {
  $session_name = config('session_name');
  if ($session_name) {
    if (!session_id()) {
      session_name($session_name);
      $lifetime = max(config('session_lifetime', 0), ini_get('session.gc_maxlifetime'));
      ini_set('session.gc_maxlifetime', $lifetime);
      session_set_cookie_params($lifetime);
      session_start();
    }
    if ($key) {
      if ($value !== null) {
        $_SESSION[$key] = $value; // implment js style hash lookup (like config; maybe extract to look up hash funciton (taking dot separators))
        return $value;
      } else {
        return $_SESSION[$key];
      }
    } else {
      $session_id = session_id();
      return $session_id;
    }
  }
  $null = null;
  return $null;
}

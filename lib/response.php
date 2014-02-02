<?php
function error($code, $exception = null) {
  static $error_code_values = array(
    '404' => 'Not Found',
    '500' => 'Internal Server Error'
  );
  if (!isset($error_code_values[$code])) {
    $error_code_values = json_decode(file_get_contents(__DIR__ . '/data/http_codes.json'), true);
  }
  header("HTTP/1.0 {$code} {$error_code_values[$code]}", true, $code);

  if (env('ENV') === 'development') {
    echo get_template_contents(__DIR__ . '/templates/developer_error.php', array('exception' => $exception, 'code_reason' => $error_code_values[$code], 'code' => $code));
  } else {
    if (locate_template('error' . $code, false)) {
      echo get_template_contents('error' . $code, array('message' => $exception->getMessage()));
    } else {
      $error_controller = config('error_controller');
      if ($error_controller) {
        if (function_exists('run_controller')) {
          if (run_controller($error_controller, array($code, $exception), array('error_' . $code, 'error'))) {
            return;
          }
        }
      }
      if (file_exists(__DIR__ . '/templates/missing/' . $code . '.php')) {
        echo get_template_contents(__DIR__ . '/templates/missing/' . $code . '.php', array('exception' => $exception, 'code_reason' => $error_code_values[$code]));
      } else {
        echo get_template_contents(__DIR__ . '/templates/missing/default.php', array('exception' => $exception, 'code_reason' => $error_code_values[$code], 'code' => $code));
      }
    }
  }
}
function redirect($page) {
  if (!preg_match('#^\w+://#', $page)) {
    if (substr($page, 0, 1) !== '/') {
      $page = '/' . $page;
    }
    $proto = 'http' . (isset(request('HTTPS')) ? 's' : '') .'://';
    $host = request('HTTP_HOST');
    $root = request('root_dir');
    $page = $proto . $host . $root . $page;
  }
  header('Location: '. $page);
  exit;
}
function session($key = null) {
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
      if (func_num_args() === 2) {
        $_SESSION[$key] = func_get_arg(1);
      } else {
        return $_SESSION[$key];
      }
    } else {
      return session_id();
    }
  }
  return null;
}

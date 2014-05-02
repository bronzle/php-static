<?php
function run_controller($controller = null, $args = array(), $actions = array()) {
  if (!$controller) {
    $parts = request('uri_parts');
    $controller = array_shift($parts);
    if ($controller !== 'index') {
      $controller_path = controller_path($controller);
      if (!file_exists($controller_path)) {
        array_unshift($parts, $controller);
        $controller = config('default_controller');
      }
    } else {
      $controller = config('default_controller');
    }
    if (count($parts)) {
      $actions[] = implode('_', $parts);
    } else {
      $actions[] = 'index';
    }
  }
  $ret = true;
  $controller_path = controller_path($controller);
  if (file_exists($controller_path)) {
    include($controller_path);
    $controller_class = controller_class($controller);
    if (class_exists($controller_class) && is_a($controller_class, 'Controller', true)) {
      $controller_object = new $controller_class;
      if (method_exists($controller_object, '_before')) {
        $ret = call_user_func_array(array($controller_object, '_before'), $args);
        $ret = $ret === null ? true : $ret;
      }
      if ($ret) {
        foreach ($actions as $action) {
          if (method_exists($controller_object, $action)) {
            $ret = call_user_func_array(array($controller_object, $action), $args);
            $ret = $ret === null ? true : $ret;
          }
        }
      }
      if (method_exists($controller_object, '_after')) {
        call_user_func_array(array($controller_object, '_after'), $args);
      }
    }
  }
  return $ret;
}
function controller_path($controller) {
  return request('root_path') . '/' . config('controller_path') . '/' . $controller . '.php';
}
function controller_class($controller) {
  return str_replace(' ', '', ucwords(str_replace('_', ' ', $controller))) . 'Controller';
}
abstract class Controller {
  public static $__rendered = false;
  protected function render($template, $vars = array()) {
    $GLOBALS['__content'] = render($template, $vars, false, false); // this could return null, which will break things
    self::$__rendered = $template;
  }
}

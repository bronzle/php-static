<?php

function locate_template($template_location, $template, $allow_expanding_to_folder = true, $partial = true, &$files = array()) {
  list($template, $ext) = array_merge(explode('.', $template, 2), array('php'));
  $ext = ".{$ext}";
  $files = array();
  if ($template_location[0] !== '/') {
    $template_location = request('root_path') . '/' . $template_location;
  }
  if ($partial) {
    $parts = explode('/', $template);
    $last = array_pop($parts);
    $last = '_' . $last;
    $parts[] = $last;
    $template = implode('/', $parts);
  }
  if ($partial && $template[0] !== '/') {
    $parts = array($template_location, request('uri'), $template);
  } else {
    $parts = array($template_location, $template);
  }
  $parts = array_filter($parts);
  $file = normalize_path(implode('/', $parts)) . $ext;
  $files[] = $file;
  if (file_exists($file)) {
    return $file;
  } elseif ($allow_expanding_to_folder && !$partial && $template !== 'index') {
    $parts[] = 'index';
    $file = normalize_path(implode('/', $parts)) . $ext;
    $files[] = $file;
    if (file_exists($file)) {
      return $file;
    }
  }
  return null;
}
function &render($template, $__variables = array(), $__template_root = null, $__partial = true, $__is_layout = null, $__save_content = true) {
  $__internal = is_bool($__is_layout);
  $__in_controller = isset($GLOBALS['__in_controller']) && $GLOBALS['__in_controller'] == true;
  $__template = locate_template($__template_root ? $__template_root : config('pages_root'), $template, !$__is_layout, $__in_controller ? false : $__partial, $__should_be_at);
  if (!$__template) {
    throw new MissingTemplate(($__internal ? ($__is_layout ? MissingTemplate::LAYOUT : MissingTemplate::CONTENT) :  MissingTemplate::OTHER), $template, $__should_be_at);
  }
  unset($__should_be_at, $template, $__is_layout, $__internal);
  $contents = &get_template_contents($__template, $__variables);
  if ($__in_controller && $__save_content) {
    $GLOBALS['__content'] = &$contents;
  }
  return $contents;
}
function &get_template_contents($__template, $__variables = array()) {
  extract(array_merge(vars(), $__variables));
  ob_start();
  include($__template);
  $__content = ob_get_clean();
  return $__content;
}
function &title($title = null) {
  static $__title = null;
  if (!$__title && $title) {
    $__title = $title;
  } elseif ($title === false) {
    $__title = '';
  }
  return $__title;
}
function &layout($layout = true) {
  static $__layout = null;
  if ($__layout === null && $layout === true || $layout === null) {
    $__layout = config('default_layout');
  } elseif ($layout !== true) {
    $__layout = $layout;
  }
  return $__layout;
}
$__content = '';
function &content() {
  return $GLOBALS['__content'];
}
function &vars($key = null, $value = null) {
  static $vars = array();
  if ($key) {
    if (is_array($key)) {
      $vars = array_merge($vars, $key);
      return true;
    } else {
      if ($value !== null) {
        $vars[$key] = $value;
      }
      return $vars[$key];
    }
  }
  return $vars;
}

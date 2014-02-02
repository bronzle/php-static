<?php
function locate_template($template, $partial = true, &$files = array()) {
  list($template, $ext) = array_merge(explode('.', $template, 2), array('php'));
  $ext = ".{$ext}";
  if ($partial && $template[0] !== '/') {
    $parts = array(request('root_path'), config('pages_root'), request('uri'), $template);
  } else {
    $parts = array(request('root_path'), config('pages_root'), $template);
  }
  $parts = array_filter($parts);
  $file = implode('/', $parts) . $ext;
  $files[] = $file;
  if (file_exists($file)) {
    return $file;
  } elseif (!$partial && $template !== 'index') {
    $parts[] = 'index';
    $file = implode('/', $parts) . $ext;
    $files[] = $file;
    if (file_exists($file)) {
      include($file);exit;
      return $file;
    }
  }
  return null;
}
function &render($template, $__variables = array(), $__partial = true, $__is_layout = null) {
  $__internal = is_bool($__is_layout);
  $__template = locate_template($template, $__partial, $__should_be_at);
  if (!$__template) {
    throw new MissingTemplate(($__internal ? ($__is_layout ? MissingTemplate::LAYOUT : MissingTemplate::CONTENT) :  MissingTemplate::OTHER), $template, $__should_be_at);
  }
  unset($__should_be_at);
  unset($template);
  unset($__is_layout);
  unset($__internal);
  return get_template_contents($__template, $__variables);
}
function &get_template_contents($__template, $__variables = array()) {
  extract($__variables);
  ob_start();
  include($__template);
  $__content = ob_get_clean();
  return $__content;
}
function title($title = null) {
  static $__title = null;
  if (!$__title && !$title) {
    $__title = $title;
  }
  return $__title;
}
function layout($layout = null) {
  static $__layout = null;
  if ($layout) {
    $__layout = $layout;
  } else {
    $__layout = config('default_layout');
  }
  return $__layout;
}
$__content = '';
function &content() {
  return $GLOBALS['__content'];
}

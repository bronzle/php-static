<?php

function locate_template($template_location, $template, $allow_expanding_to_folder = true, $partial = true, &$files = array()) {
  list($template, $ext) = array_merge(explode('.', $template, 2), array('php'));
  $ext = ".{$ext}";
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
function &render($template, $__variables = array(), $__partial = true, $__is_layout = null) {
  $__internal = is_bool($__is_layout);
  $__template = locate_template(config('pages_root'), $template, !$__is_layout, $__partial, $__should_be_at);
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
function layout($layout = true) {
  static $__layout = null;
  if ($__layout === null) {
    $__layout = config('default_layout');
  }
  if ($layout === null) {
    $__layout = config('default_layout');
  } elseif ($layout === false) {
    $__layout = false;
  } elseif ($layout !== true) {
    $__layout = $layout;
  }
  return $__layout;
}
$__content = '';
function &content() {
  return $GLOBALS['__content'];
}

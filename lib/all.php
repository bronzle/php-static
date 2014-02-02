<?php
$__FILE_LIST = array(
  'exceptions',
  'config',
  'request',
  'template',
  'response',

  'url' => 'optional',
  'controller' => 'optional',
  'flash' => 'optional',
  'body_classes' => 'optional',
  'email' => 'optional',

  'run'
);
foreach ($__FILE_LIST as $__key => $__current_file) {
  if (!is_numeric($__key)) {
    $__optional = '/' . $__current_file;
    $__current_file = $__key;
  } else {
    $__optional = '';
  }
  require(__DIR__ . "{$__optional}/{$__current_file}.php");
}

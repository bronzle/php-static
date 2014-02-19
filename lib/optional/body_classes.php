<?php
function &body_classes() {
  static $_body_classes = array();
  if (func_num_args() > 0) {
    $args = func_get_args();
    if ($args[0] === true && count($args) === 1) {
      return $_body_classes;
    } elseif ($args[0] === false) {
      $_body_classes = array_filter(array_unique(array_slice($args, 1)));
    } else {
      $_body_classes = array_filter(array_unique(array_merge($_body_classes, $args)));
    }
  }
  $ret = implode($_body_classes, ' ');
  return $ret;
}

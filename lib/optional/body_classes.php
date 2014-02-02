<?php
function body_classes() {
  static $_body_classes = array();
  if (func_num_args() > 0) {
    $_body_classes = func_get_args();
  }
  return implode(array_filter(array_unique($_body_classes)), ' ');
}

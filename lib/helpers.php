<?php
function &array_flatten($array, $preserve_keys = true, &$newArray = array()) {
  foreach ($array as $key => $child) {
    if (is_array($child)) {
      $newArray = &array_flatten($child, $preserve_keys, $newArray);
    } elseif ($preserve_keys && is_string($key)) {
      $newArray[$key] = $child;
    } else {
      $newArray[] = $child;
    }
  }
  return $newArray;
}
function array_whitelist_keys($data) {
  $keys = array();
  if (func_num_args() > 1) {
    $keys = array_flatten(array_slice(func_get_args(), 1));
  }
  return array_intersect_key($data, array_flip($keys));
}
function array_dot_access(&$array, $key, $default = null) {
  $keys = explode('.', $key);
  $currentArray = &$array;
  $last = end($keys);
  foreach ($keys as $key) {
    if (isset($currentArray[$key])) {
      if ($key === $last) {
        return $currentArray[$key];
      }
      $currentArray = &$currentArray[$key];
    } else {
      break;
    }
  }
  return $default;
}
if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
  function string_until($str, $until) {
    $ret = strstr($str, $until, true);
    return $ret !== false ? $ret : $str;
  }
} else {
  function string_until($str, $until) {
    $ret = current(explode($until, $str, 1));
    return $ret !== false ? $ret : $str;
  }
}
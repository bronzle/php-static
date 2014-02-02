<?php
function flash($type = null, $message = null) {
  $flash_msg = config('flash_msg');
  if ($message === null) {
    if (isset($_SESSION[$flash_msg])) {
      if ($type === null) {
        $ret = array();
        foreach($_SESSION[$flash_msg] as $_type) {
          foreach($_SESSION[$flash_msg][$_type] as $msg) {
            $ret[] = $msg;
          }
        }
      } elseif (isset($_SESSION[$flash_msg][$type])) {
        $ret = $_SESSION[$flash_msg][$type];
      }
      clear_flash($type);
      return $ret;
    }
    return array();
  } else {
    if (!isset($_SESSION[$flash_msg])) {
      $_SESSION[$flash_msg] = array();
    }
    if (!isset($_SESSION[$flash_msg][$type])) {
      $_SESSION[$flash_msg][$type] = array();
    }
    $_SESSION[$flash_msg][$type][] = $message;
  }
}
function has_flash($type = null) {
  $flash_msg = config('flash_msg');
  if (!isset($_SESSION[$flash_msg])) {
    return false;
  }
  if ($type === null) {
    foreach($_SESSION[$flash_msg] as $_type) {
      if (count($_SESSION[$flash_msg][$_type]) > 0) {
        return true;
      }
    }
  } else {
    if (isset($_SESSION[$flash_msg][$type]) && count($_SESSION[$flash_msg][$type]) > 0) {
      return true;
    }
  }
  return false;
}
function clear_flash($type = null) {
  $flash_msg = config('flash_msg');
  if ($type === null) {
    unset($_SESSION[$flash_msg]);
  } else {
    if (isset($_SESSION[$flash_msg])) {
      unset($_SESSION[$flash_msg][$type]);
    }
  }
}

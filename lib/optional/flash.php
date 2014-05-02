<?php
function flash($type = null, $message = null) {
  static $flash_msg = null;
  if (!$flash_msg) {
    $flash_msg = config('flash_msg');
    session();
  }
  if ($message === null) {
    if (isset($_SESSION[$flash_msg])) {
      if ($type === null) {
        $ret = array();
        foreach($_SESSION[$flash_msg] as $_type => $_messages) {
          $ret[$_type] = array();
          foreach($_messages as $msg) {
            $ret[$_type][] = $msg;
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
  static $flash_msg = null;
  if (!$flash_msg) {
    $flash_msg = config('flash_msg');
    session();
  }
  if (!isset($_SESSION[$flash_msg])) {
    return false;
  }
  if ($type === null) {
    foreach($_SESSION[$flash_msg] as $_type => $_messages) {
      if (count($_messages) > 0) {
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
  static $flash_msg = null;
  if (!$flash_msg) {
    $flash_msg = config('flash_msg');
    session();
  }
  if ($type === null) {
    unset($_SESSION[$flash_msg]);
  } else {
    if (isset($_SESSION[$flash_msg])) {
      unset($_SESSION[$flash_msg][$type]);
    }
  }
}

<?php
function json($data) {
  set_header('Content-Type', 'application/json');
  return json_encode($data);
}
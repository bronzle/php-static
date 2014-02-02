<?php
$benchmark = false;
function benchmark($name = null) {
  static $start_time = array();
  if ($name === true) {
    return $start_time;
  } elseif (!$name) {
    $name = '__DEFAULT__';
  }
  if (!isset($start_time[$name]) || (isset($start_time[$name]) && isset($start_time[$name]['end']))) {
    $start_time[$name] = array(
      'start' => microtime(true)
    );
  } elseif (!isset($start_time[$name]['end'])) {
    $start_time[$name]['end'] = microtime(true);
    $start_time[$name]['diff'] = $start_time[$name]['end'] - $start_time[$name]['start'];
    return $diff_time;
  }
}

/*

Example for application.php

*/


if($benchmark) {
  ob_start();
  benchmark();
}

/* application.php */
require __DIR__ . '/all.php';
run();
/* end application.php */


if($benchmark) {
  benchmark();
  ob_clean();

  echo "<table cellpadding=10><tr><td>Name</td><td>Time</td></tr>";
  foreach (benchmark(true) as $bench_name => $info) {
    if ($bench_name === '__DEFAULT__') {
      $bench_name = 'Overall';
    }
    echo "<tr><td>{$bench_name}</td><td>{$info['diff']}</td></tr>";
  }
  echo "</table>";
}

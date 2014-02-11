<?php
function run($env = null) {
  if ($env === null) {
    $env = config('env', env('ENV', 'production'));
  }
  try {
    $run_default_page = true;
    if (function_exists('run_controller')) {
      run_controller();
      $run_default_page = !Controller::$__rendered;
    }
    if ($run_default_page) {
      $GLOBALS['__content'] = render(request('uri_name'), array(), false, false);
    }

    $layout = render(layout(), array(), false, true);
    if ($layout) {
      echo $layout;
    } else {
      echo content();
    }
  } catch (MissingTemplate $e) {
    error('404', $e);
  } catch (InvalidConfiguration $e) {
    error('500', $e);
  } catch (Exception $e) {
    error('500', $e);
  }
}

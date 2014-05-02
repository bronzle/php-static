<?php
function run($env = null) {
  try {
    if (!defined('PHPS_START_TIME')) {
      define('PHPS_START_TIME', microtime(true));
    }
    if ($env === null) {
      $env = array('env' => env('env', 'development'));
    } elseif (is_string($env)) {
      $env = array('env' => $env);
    } elseif (is_array($env)) {
      $env = array_merge(array('env' => env('env', 'development')), $env);
    }
    foreach ($env as $env_key => $env_val) {
      set_env($env_key, $env_val);
    }
    if (!env('phps_app_doc_root')) {
      $bt = @debug_backtrace();
      $called_from = dirname($bt[0]['file']);
      while (true) {
        $possible_config_files = glob($called_from . '/config{-*,}.json', GLOB_BRACE);
        if ($possible_config_files !== false && count($possible_config_files)) {
          set_env('phps_app_doc_root', $called_from);
          break;
        }
        if (realpath($called_from) === realpath($_SERVER['DOCUMENT_ROOT'])) {
          throw new Exception('Application root not found inside document root');
        }
        $called_from = dirname($called_from);
      }
    }
    $default_headers = config('default_response_headers', array());
    if (count($default_headers) > 0) {
      foreach ($default_headers as $header => $value) {
        if (is_int($header)) {
          $header = $value;
          $value = null;
        }
        set_header($header, $value);
      }
    }
    if (config('run_request')) {
      $run_default_page = true;
      if (function_exists('run_controller')) {  // run controller to determine if we render or just send content and exit
        $GLOBALS['__in_controller'] = true;
        $ret = run_controller();
        // if we did call render, skip default page
        unset($GLOBALS['__in_controller']);
        if ($ret === true) {
          $run_default_page = empty($GLOBALS['__content']);
        } else {
          if (is_string($ret)) {
            echo $ret;
          }
          exit;
        }
      }
      if ($run_default_page) {
        $GLOBALS['__content'] = &render(ltrim(request('uri_name'), '/'), array(), false, false, false);
      }

      $layout = layout();
      if ($layout !== false) {
        echo render($layout, array(), false, false, true);
      } else {
        echo content();
      }
    }
  } catch (MissingTemplate $e) {
    error('404', $e);
  } catch (InvalidConfiguration $e) {
    error('500', $e);
  } catch (Exception $e) {
    error('500', $e);
  }
}

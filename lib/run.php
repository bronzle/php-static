<?php
function run($env = null) {
  try {
    define('PHPS_START_TIME', microtime(true));
    if ($env === null) {
      $env = env('env', 'development');
      putenv("ENV=$env");
    }
    if (!defined('PHPS_APP_DOC_ROOT')) {
      $doc_root = env('phps_app_doc_root');
      if ($doc_root) {
        define('PHPS_APP_DOC_ROOT', realpath($doc_root));
      } else {
        $bt = @debug_backtrace();
        $called_from = dirname($bt[0]['file']);
        while (true) {
          $possible_config_files = glob($called_from . '/config{-*,}.json', GLOB_BRACE);
          if ($possible_config_files !== false && count($possible_config_files)) {
            define('PHPS_APP_DOC_ROOT', realpath($called_from));
            break;
          }
          if (realpath($called_from) === realpath($_SERVER['DOCUMENT_ROOT'])) {
            throw new Exception('Application root not found inside document root');
          }
          $called_from = dirname($called_from);
        }
      }
    }
    if (config('run_request')) {
      $run_default_page = true;
      if (function_exists('run_controller')) {
        run_controller();
        $run_default_page = !Controller::$__rendered;
      }
      if ($run_default_page) {
        $GLOBALS['__content'] = &render(request('uri_name'), array(), false, false);
      }

      $layout = layout();
      if ($layout !== false) {
        echo render($layout, array(), false, true);
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

<?php
function run($env) {
  try {
    session();

    // check layout exists before the rest ?
    if (!locate_template(layout(), false)) {
      throw new MissingTemplate(MissingTemplate::LAYOUT, layout());
    }

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
    }
  } catch (MissingTemplate $e) {
    error('404', $e);
  } catch (InvalidConfiguration $e) {
    error('500', $e);
  } catch (Exception $e) {
    error('500', $e);
  }
}

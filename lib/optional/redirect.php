<?php
function redirect_extension() {
  if (in_array(request('extension'), (array) config('redirect.extensions'))) {
    $parts = request('uri_parts');
    list($file, $ext) = explode('.', array_pop($parts));
    $parts[] = $file;
    redirect(implode('/', $parts), request('query_string'));
    return true;
  }
  return false;
}
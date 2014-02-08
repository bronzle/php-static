### Table of Contents
* [Config](#wiki-config---libconfigphp)
	* [`config()`](#wiki-config) gets and sets config values (backed by config.json).
	* [`has_config()`](#wiki-has_config) checks if value for given key exists in config.
* [Request](#wiki-request---librequestphp)
	* [`normalize_uri()`](#wiki-normalize_uri) normalizes a uri.
	* [`request()`](#wiki-request) get request variables.
	* [`env()`](#wiki-env) get environment variables (ENV).
	* [`post()`](#wiki-post) retrieves post variables.
	* [`get()`](#wiki-get) retrieves get variables.
	* [`param()`](#wiki-param) retrieves get and post variables.
* [Response](#wiki-response---libresponsephp)
	* [`error()`](#wiki-error) generates a HTTP error (404, 500, etc...).
	* [`redirect()`](#wiki-redirect) redirects the user to a given page/URL.
	* [`session()`](#wiki-session) gets and sets session variables.
* [Template](#wiki-template---libtemplatephp)
	* [`locate_template()`](#wiki-locate_template) locates a template given a name.
	* [`render()`](#wiki-render) locates, buffers and returns a template's contents.
	* [`get_template_contents()`](#wiki-get_template_contents) buffers and returns a template's content.
	* [`title()`](#wiki-title)  gets and sets the title to be used in the &gt;title&gt; tag.
	* [`layout()`](#wiki-layout) gets and sets the layout to wrap the page.
	* [`content()`](#wiki-content) gets the content from the page to be output in the layout.
* [Optional](#wiki-optional)
	* [Body Classes](#wiki-body-classes---liboptionalbody_classesphp)
		* [`body_classes()`](#wiki-body_classes) gets and sets body classes for use on the &lt;body&gt; tag.
	* [Email](#wiki-email---liboptionalemailphp)
		* [`send_email()`](#wiki-send_email) sends an email for a contact form.
	* [Flash](#wiki-flash---liboptionalflashphp) gets,sets,clears
		* [`flash()`](#wiki-flash) gets and sets flash messages.
		* [`has_flash()`](#wiki-has_flash) checks if there is a flash or flashes.
		* [`clear_flash()`](#wiki-clear_flash) clears all or some flashes.
	* [URL](#wiki-url---liboptionalurlphp)
		* [`url()`](#wiki-url) returns a application relative URL relative to an full URL.
		* [`asset_url()`](#wiki-asset_url) returns a URL for assets.

## Config - *lib/config.php*
### `config()`
`mixed &config([string $key = null], [mixed $default = null])`

`config()` is a getter setter of for defaults and config.json. You may pass in a `$key` to retrieve, in the case the key is nested you can dot separate the keys `config('parent.child')`. You may also pass a default return value in the case the config value is not there, `$default` defaults to `null`. When passing no parameters, it returns the entire config hash.

### `has_config()`
`boolean has_config(string $key, [array $children = array()])`

`has_config()` checks for config values, you can pass `$key` the same as `config()` (i.e. dot separated), but you can also pass an array of `$children` this is useful if you want to check that multiple config values exists for feature branching, e.g.
```php
if (has_config('service_auth', array('user', 'password')) {
  // connect to service
}
```

## Request - *lib/request.php*
### `normalize_uri()`
`string normalize_uri(string $path)`

`normalize_uri` simply takes a URI and removes redundant slashes, useful for piecing together a URI.

### `request()`
`mixed request([string $key = null])`

`request()` gives access to request parameters. The request parameters backing the function are a merged `$_SERVER` with some PHP-Static specific variables including: _root_uri_, _root_path_, _uri_, _uri_name_ and _uri_parts_

### `env()`
`string env(string $key [, mixed $default = false])`

`env()` provides access to environment variables, it internally uses [`getenv()`](http://php.net/getenv), but allows for a `$default` to be returned (which defaults to false).

### `post()`
`mixed &post[(string $key = null [, mixed $default = '']])`

`post()` retrieves `$_POST` with the added bonus of providing a `$default` value, useful for forms. Passing no arguments the function returns a reference to $_POST.

### `get()`
`mixed &get([string $key = null [, mixed $default = '']])`

`get()` retrieves `$_GET` with the added bonus of providing a `$default` value, useful for forms. Passing no arguments the function returns a reference to $_GET.

### `param()`
`mixed &param([string $key = null [, mixed $default = '']])`

`param()` does the same as `get()` and `post()`, but on a both of them, first checking `$_GET` then `$_POST`. Passing no arguments the function returns a merged hash of both (in the same order);

## Response - *lib/response.php*
### `error()`
`void error(string $code)`

`error()` causes an error (404, 500, etc) to be sent to the client (via setting a header). It then responds depending on environment. First it will search for the error_controller defined by config.json, if it finds that it will try to run the action `error_$code()` or `error()` which can render a view see [Controllers](), or it will render the default error page or a default missing error page. When ENV is set to developer, it will render a more useful developer error page.


### `redirect()`
`void redirect(string $page)`

`redirect()` ... redirects the client, it uses current PROTOCOL://HOST/php-static-root so that with PHP-Static running in a subdirectory of the document root: `'area'` `redirect('/page')` will result in `Location: PROTOCOL://HOST/area/page`

### `session()`
`mixed session([string $key = null [, $value = null]])`

`session()` gets and sets session variables, sessions are automatically setup on first use, so be sure to use it before any content is sent, (considering the views are buffered, it should work anywhere). Passing just the `$key` will return a session value, passing in value sets the session value, and passing no arguments returns the session_id.


## Template - *lib/template.php*
### `locate_template()`
`string|null locate_template(string $template [, boolean $partial = true])`

`locate_template()` searches a for a template when `$partial` is false it will look for `$template` and $template/index.php (so that /about can goto pages/about.php and pages/about/index.php), unless `$template` is index already (no pages/index/index.php). When `$partial` is true, it will look for a template with an underscore), it will search relative to page to be rendered, so a request for /page with `$template = 'form'` it will look for pages/page/_form.php. If `$template` begins with a / it will search relative to the `config('pages_root')`

### `render()`
`string &render(string $template, [array $variables = array()])`

`render()` uses output buffering to render a template, it uses `locate_template` to find it, when called from a inside a view, it will locate a partial. You may pass `$variables` as an hash, which get extracted out to top level variables, so `render('template', array('name' => 'bronzle'))` will give the template page a variable called $name.

### `get_template_contents()`
`string &get_template_contents(string $template [, array $variables = array()])`

`get_template_contents()` returns templates of php file, it does no checking for the file, so if passed an incorrect path it will raise PHP errors, it is used internally by `render()`. As with `render()` you can also pass in `$variables`

### `title()`
`string title([string $title = null])`

`title()` gets and sets the title for the page, to be used in the view or controller as `title('Page Title')` and then used in the layout file as `<title><?= title() ?></title>`.

### `layout()`
`string|false layout([$layout = true])`

`layout()` gets and sets the layout to use, so that it can be overridden per page/controller. You may pass a string to set the new layout, if you pass null it will set the layout back to the default layout, if you pass false, it will not render the layout. By passing no arguments (or true) it will just return the layout.

### `content()`
`string &content()`

`content()` is returns the content of the inside view, it is to be used in a layout like this: `<?= content() ?>`

# Optional
## Body Classes - *lib/optional/body_classes.php*
### `body_classes()`
`string body_classes([string $class [, ...]])`

`body_classes()` sets and gets the body classes to be used in `<body class="<?= body_classes() ?>"`. You can set it by passing a variable amount of strings, which it will concatenate into a space separated single string.

## Email - *lib/optional/email.php*
### `send_email()`
`boolean send_email(string $template_name, string $subject, string $name, mixed $from_email, array $variables, string $message)`



## Flash - *lib/optional/flash.php*
### `flash()`
`array|void flash([string $type = null [, string $message = null]])`

`flash()` sets and gets flash (used to display success/error on POST usually). Call with a `$type` and `$message` to add an item to the flash. Calling with just a `$type` returns an array of all flashes with that type. Calling with no arguments returns a hash of arrays of flashes `array($type => array($message, ...), ...)`. When retrieving an item from the flash it is automatically removed.

### `has_flash()`
`boolean has_flash([string $type = null])`

`has_flash()` checks if flash of a `$type` exists (useful to wrap around foreach loops when displaying flashes). When called with no arguments, it will check if there are any flashes at all.

### `clear_flash()`
`void clear_flash([$type = null])`

`clear_flash()` removes all flashes of a `$type`, or when called with no arguments all flashes.


## URL - _lib/optional/url.php_
### `url()`
`string url(string $path)`

`url()` generates a URL relative to the root of the PHP-Static application, useful for running development and production under different subdirectories (or one not under a subdirectory and the other under one).

### `asset_url()`
`string asset_url([[string $type,] string $path = null])`

`asset_url()` generates a URL relative to the asset root, when passing a `$type` (e.g. 'css', 'js', 'image', etc...), it will generate a url under that subdirectory (css, js, images are configurable). With a `$type` it doesn't understand it just uses that as the subdirectory (e.g. `asset_url('pdf', 'pdf_name.pdf') #=> '/assets/pdf/pdf_name.pdf'`). When calling with a single parameter, it will assume it is the path and generate from the asset root.

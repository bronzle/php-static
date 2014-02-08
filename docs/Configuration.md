PHP-Static tries to be as configurable as possible, it comes with some pretty sane (I think) defaults, but all those values are able to be overridden by the projects `config.json` file in the root of the application. To access config inside the application use [`config()`](Function-Reference#wiki-config)


## Defaults

The defaults include defaults for everything that comes with PHP-Static, including optional libraries, this ensures that the full framework works out of the box.


The defaults for config are as follows:

```
{
	"default_layout":       "layout",
	"pages_root":           "pages",
	"error_root":           "errors",
	"assets_base":          "assets",
	"assets_css":           "css",
	"assets_js":            "js",
	"assets_images":        "images",
	"session_name":         "session_token",	
	"controller_path":      "controllers",
	"default_controller":   "default",
	"error_controller":     "error",
	"flash_msg":            "flash_msg",
	"emails_root":          "emails"
}
```
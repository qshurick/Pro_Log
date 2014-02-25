Initialization
---

ensure in application.ini:

```
    autoloaderNamespaces[] = "Pro_"
    pluginPaths.Pro_Resource_ = "Pro/Resource"
    config.log = APPLICATION_PATH "/configs/log.ini"
```

ensure in include_path directory 'vendor/qshurick/pro_log/library', i.g. ensure in public_html/index.php:

```
    set_include_path(implode(PATH_SEPARATOR, array(
        realpath(APPLICATION_PATH . '/../library'),
        realpath(APPLICATION_PATH . '/../vendor/qshurick/pro_log/library'),
        get_include_path(),
    )));
    require_once APPLICATION_PATH . '/../vendor/autoload.php';
```

Usage
---

* with Zend_Registry:

```php
Zend_Registry::get('logger')->log('Log message');
```


* direct call Pro_Log:

```php
    Pro_Log::getInstance()->log('Log message');
```

* embed call

```php
    class SomeLoggedClass {
        /** ... */
        function init() {
            /** ... */
            $this->logger = Pro_Log::getInstance()->ensureStream('custom-logger');
            $this->logger->log('Log message', Zend_Log::INFO);
            /** ... */
        }
        /** ... */
    }
```



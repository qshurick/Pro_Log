Initialization
---

Ensure in application.ini:

```
    autoloaderNamespaces[] = "Logger_"
    pluginPaths.Logger_Application_Resource_ = "Logger/Application/Resource"
    config.logger = APPLICATION_PATH "/configs/log.ini"
```

Ensure in ```include_path``` directory ```'vendor/qshurick/pro_log/library'```, i.g. in ```public_html/index.php```:

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
    // Logger_Application_Logger
    Zend_Registry::get('logger')->log('Log message');
```


* direct:

```php
    // Logger_Application_Logger
    Logger_Application_Logger::getInstance()->log('Log message');
```

* embed:

```php
    class SomeLoggedClass {
        /** ... */
        function init() {
            /** ... */
            // Zend_Log
            $this->logger = Zend_Registry::get('logger')->ensureStream('custom-logger');
            $this->logger->log('Log message', Zend_Log::INFO);
            /** ... */
        }
        /** ... */
    }
```

* ~~~deprecated (just for current compatibility)~~~

```php
    Pro_Log::log('Log message');
```


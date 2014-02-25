Initialization
---

ensure in application.ini:
  

    autoloaderNamespaces[] = "Pro_"
    pluginPaths.Pro_Resource_ = "Pro/Resource"
    config.log = APPLICATION_PATH "/configs/log.ini"
    
    
ensure in include_path directory 'vendor/qshurick/pro_log/library', i.g. ensure in public_html/index.php:
    
    
    set_include_path(implode(PATH_SEPARATOR, array(
        realpath(APPLICATION_PATH . '/../library'),
        realpath(APPLICATION_PATH . '/../vendor/qshurick/pro_log/library'),
        get_include_path(),
    )));
    require_once APPLICATION_PATH . '/../vendor/autoload.php';

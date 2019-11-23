<?php

$webRoot = dirname(__DIR__);
// PHPUnit loader
error_reporting(E_ERROR);
set_include_path(get_include_path() . PATH_SEPARATOR . $webRoot);

include_once 'client/config_thelia.php';
require_once "fonctions/autoload.php";

$modules = ActionsModules::instance()->lister(false, true);

foreach ($modules as $module) {
    try {
        $path = ActionsModules::instance()->lire_chemin_module($module->nom) . "/inclure_session.php";
        if (file_exists($path)) {
            include_once ($path);
        }
    } catch (Exception $e) {}
}


$_d = "{$webRoot}/specs/tests/hooks";

function _require($path) {
    if(preg_match('/\.$/', $path) == 1) {
        return;
    }
    if(is_file($path)) {
        if(preg_match('/^.+\.php$/i', $path) == 1) {
            require_once $path;
        }
        return;
    }
    $files = scandir($path);
    foreach ($files as $file) {
        _require($path . DIRECTORY_SEPARATOR . $file);
    }
}
_require($_d);

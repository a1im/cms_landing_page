<?php
// echo phpinfo();
ini_set('display_errors','On');
error_reporting (E_ALL);
if (version_compare(phpversion(), '7.0.0', '<') == true) { die ('PHP7.0 Only'); }

// Константы
define ('DIRSEP', DIRECTORY_SEPARATOR);
define ('PATH_APP_DIR', realpath(dirname(__FILE__)));
define ('PATH_DIR_CORE', PATH_APP_DIR . DIRSEP . "core");
define ('PATH_DIR_MVC', str_replace('/', DIRSEP, $_SERVER['DOCUMENT_ROOT']));
define ('DIR_MVC', str_replace(PATH_APP_DIR . DIRSEP, '', PATH_DIR_MVC));
define ('PATH_DIR_MVC_ASSETS', PATH_DIR_MVC . DIRSEP . "assets");
define ('PATH_DIR_MVC_CONTROLLERS', PATH_DIR_MVC . DIRSEP . "controllers");
define ('SITE_URL_NAME', "http://" . $_SERVER['HTTP_HOST']);
define ('SITE_URL_ASSETS', SITE_URL_NAME . "/assets");

set_include_path(PATH_APP_DIR);

// Подключаем авто загрузку классов
require_once PATH_DIR_CORE . DIRSEP . "autoload.php";
<?php

use alimmvc\core\registry;
use alimmvc\core\router;

// Названия таблиц

// Путь до папок
registry::app()->dir_controllers = DIR_MVC . DIRSEP . 'controllers';
registry::app()->dir_views = DIR_MVC . DIRSEP . 'views';
registry::app()->dir_views_layouts = registry::app()->dir_views . DIRSEP . 'layouts';
registry::app()->path_save_site = PATH_APP_DIR . DIRSEP . 'arhiveSite'; // папка для временных папок и файлов
// debug(DIR_MVC);
// Подключаем маршрутизатор URL
registry::app()->router = new router();
registry::app()->router->setDefaultController("main");
registry::app()->router->loadController();

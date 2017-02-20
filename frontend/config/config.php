<?php

use alimmvc\core\registry;
use alimmvc\core\router;

// Названия таблиц

// Путь до папок
registry::app()->dir_controllers = DIR_MVC . DIRSEP . 'controllers';
registry::app()->dir_views = DIR_MVC . DIRSEP . 'views';
registry::app()->dir_views_layouts = registry::app()->dir_views . DIRSEP . 'layouts';

// Подключаем маршрутизатор URL
registry::app()->router = new router();
registry::app()->router->setDefaultController("main");
registry::app()->router->loadController();

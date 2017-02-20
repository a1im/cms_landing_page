<?php

use alimmvc\core\registry;

// Подключаем глобальные функции
require_once PATH_DIR_CORE . DIRSEP . "func.php";

// Подключение к бд
//registry::app()->db = new PDO('mysql:host=atom3.beget.com;dbname=qwqwqw_message', 'qwqwqw_message', 'qwer1234');
registry::app()->db = new PDO('mysql:host=localhost;dbname=cms', 'root', '123');
registry::app()->dbform = new PDO('mysql:host=localhost;dbname=mvcform', 'root', '123');
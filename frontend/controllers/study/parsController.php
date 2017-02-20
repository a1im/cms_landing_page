<?php

namespace alimmvc\frontend\controllers\study;

use alimmvc\core\controllers;
use alimmvc\core\registry;

class parsController extends controllers
{
	protected $layout = "sii";

	public function actionTest1($N)
	{
		$result = 0;
		for ($i = 0; $i < $N; $i++) $result += $i;
		return $result;
	}

	public function actionTest2()
	{
		$result = $this->findFile("/srv/http/", "init.bat");
		echo isset($res)?$res:"null";
	}

SELECT USERS.name AS user_name, USER_LOGINS.login_time
FROM USERS 
INNER JOIN USER_LOGINS ON USERS.id = USER_LOGINS.user_id

SELECT USERS.name AS user_name, MAX(USER_LOGINS.login_time) AS last_login
FROM USERS 
LEFT JOIN USER_LOGINS ON USERS.id = USER_LOGINS.user_id
GROUP BY USER_LOGINS.login_time

Взять 1 шарик из первой, 2 из второй, 3 из третьей, 4 из четвертой, 5 из пятой и взвесить все. В зависимости от того, насколько результат будет больше, чем 15 грамм, установить нужный ответ

	public function findFile($path, $name)
	{
		$result = null;
		$path = DIRECTORY_SEPARATOR . trim($path, '/\\') . DIRECTORY_SEPARATOR;

		if (file_exists($path . $name)) 
		{
		    return $path . $name;
		}

		foreach (scandir($path) as $value) 
		{
			if (is_dir($path . $value . DIRECTORY_SEPARATOR) && $value != "." && $value != "..")
			{
				$result = findFile($value, $name);
			}
		}

		return $result;
	}

	public function actionIndex($param = "")
	{
		$file = fopen(PATH_DIR_MVC_ASSETS . DIRSEP . "gr.txt", 'r');
		$resFile = array();
		
		if ($file)
		{
			$arrInt = array();
			for($i = 0; $i < 50; $i++) $arrInt[] = 1;

			$i = 0; $j = 0; $k = 0; $m = 0;
			$index = '';
			$content = '';
			while(!feof($file))
			{
				$chr = fgetc($file);
				if (!feof($file)) $chr1 = fgetc($file);
				if (!feof($file)) fseek($file, -1, SEEK_CUR);

				if ($chr == "\t") 
				{
					if ($i == 0) $index .= "_1_";
					if ($chr1 == "\t") $index .= ($arrInt[$i]-1) . "_";
					$i++;
				}
				else {
					if ($chr == "\n") 
					{
						if ($index == "") $index = "_1_";
						$resFile[$index] = $content;
						$index = '';
						$content = '';
						// $content .= "<br>";
					}
					if ($i !== 0) 
					{
						$index .= ($arrInt[$i-1]) . "_";
						$arrInt[$i-1]++;
						$k = $j;
						$j = $i;
						$i = 0;
					}

					if ($k > $j)
					{
						for($z = $j; $z < 50; $z++) 
						{
							$arrInt[$z] = 1;
						}
					}

					if ($chr !== "\n") 
					{
						$content .= $chr;
					}
				}	
			}
		}

		$result = array();
		$param = $param;
		$isQuestion = true;

		if (!isset($resFile[$param]))
		{
			$param = "_1_";
		}

		$tmp = $param;
		$tmp[strlen($tmp)-2] = 2;
		if (isset($resFile[$tmp]))
		{
			$isQuestion = false;
		}

		// вопрос или ответ
		$result[0] = $resFile[$param];

		if ($isQuestion)
		{
			$tmp = $param;
			for($i = 1; isset($resFile[$tmp]); $i++)
			{
				$tmp = $param;
				$tmp .= $i . "_";
				if (isset($resFile[$tmp])) 
				{
					$result[1][$tmp] = $resFile[$tmp];
				}
			}
			if (!isset($resFile[$param . "1_1_"])) 
			{
				$isQuestion = false;
			}
		}

		return $this->render('index', [
			'title' => "Парс текстовика по СИИ",
			'result' => $result,
			'urlAction' => registry::app()->router->_url_action,
			'urlController' => registry::app()->router->_url_controller,
			'resFile' => $resFile,
			'isQuestion' => $isQuestion,
			]);
	}

	public function actionSuccessful($value)
	{
		return $this->render('successful', [
			'title' => "Удачного путешествия!",
			'value' => $value,
			'urlController' => registry::app()->router->_url_controller,
			]);
	}
}
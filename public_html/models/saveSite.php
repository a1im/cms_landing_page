<?php
namespace alimmvc\public_html\models;

use alimmvc\core\registry;
use alimmvc\core\models;
use alimmvc\public_html\models\tableQuery\siteTags;
use alimmvc\public_html\models\tableQuery\siteCss;
use alimmvc\public_html\models\siteTagsPrint;

class saveSite extends models
{
	public static function save($id_site)
	{
		registry::app()->siteTagsCrud = new siteTags(registry::app()->db, registry::app()->user['id_user'], $id_site);
		registry::app()->siteCssCrud = new siteCss(registry::app()->db, registry::app()->user['id_user'], $id_site);
		$tags = registry::app()->siteTagsCrud->reads();
		$styles = registry::app()->siteCssCrud->reads();
		$dataSite = registry::app()->siteCrud->read([
			'id_user' => registry::app()->user['id_user'], 
			'id_site' => $id_site,
			]);

		if (empty($dataSite))
		{
			// debug("error");
			return ;
		}

		// запиываем сайт в файл
		$path_save = registry::app()->path_save_site . DIRSEP . "user_" . registry::app()->user['id_user'] . DIRSEP . $id_site;
		// debug($path_save);
		self::saveHtml($path_save, $tags, $dataSite);
		self::saveCss($path_save . DIRSEP . "css", $styles);
		self::saveJs($path_save . DIRSEP . "js");
		return self::createZip(registry::app()->path_save_site . DIRSEP . "user_" . registry::app()->user['id_user'], $id_site);
	}

	public static function isDirMkdir($path_save)
	{
		if (is_dir($path_save) || mkdir($path_save, 0777, true)) return true;
		else return false;
	}

	public static function isFileCopy($file, $newfile)
	{
		if (copy($file, $newfile)) return true;
		else return false;
	}

	public static function saveHtml($path_save, $tags, $dataSite)
	{
		if (self::isDirMkdir($path_save))
		{
			$fileHandle = registry::app()->path_save_site . DIRSEP . "head.html";
			$fp = fopen($path_save . DIRSEP . "index.html", "w");
			$fpHead = fopen($fileHandle, "r");
			// загрузим сайт
			$printTags = new siteTagsPrint();
			$title = isset($dataSite['title'])?$dataSite['title']:"";
			$html_site = "<html>\n";
			$html_site .= fread($fpHead, filesize($fileHandle));
			$html_site .= "<body>\n";
			$html_site .= "<content id='site-content'>\n";
			$html_site .= $printTags->freePrint($tags);
			$html_site .= "</content>\n";
			$html_site .= "</body>\n";
			$html_site .= "</html>\n";
			fwrite($fp, $html_site);
			fclose($fp);
			// debug("success html");
		} else {
			// debug("error - Ошибка создания папки для сохранения html");
			return ;
		}
	}

	public static function saveCss($path_save, $styles)
	{
		if (self::isDirMkdir($path_save))
		{
			$fp = fopen($path_save . DIRSEP . "mystyle.css", "w");
			foreach ($styles as $rowstyle)
			{
				fwrite($fp, $rowstyle['selector'] . "{" . $rowstyle['style'] . "}\n");
			}
			fclose($fp);

			if (!self::isFileCopy(PATH_DIR_MVC_ASSETS . DIRSEP . "css" . DIRSEP . "bootstrap.min.css", $path_save . DIRSEP . "bootstrap.min.css")) 
			{
				// debug("error - copy bootstrap.min.css");
				return ;
			}

			if (!self::isFileCopy(PATH_DIR_MVC_ASSETS . DIRSEP . "css" . DIRSEP . "mytinymce.css", $path_save . DIRSEP . "mytinymce.css")) 
			{
				// debug("error - copy mytinymce.css");
				return ;
			}

			if (!self::isFileCopy(PATH_DIR_MVC_ASSETS . DIRSEP . "js" . DIRSEP . "jquery-ui-1.12.1" . DIRSEP . "jquery-ui.min.css", $path_save . DIRSEP . "jquery-ui.min.css")) 
			{
				// debug("error - copy jquery-ui.min.css");
				return ;
			}

			if (!self::isFileCopy(PATH_DIR_MVC_ASSETS . DIRSEP . "css" . DIRSEP . "style-client.css", $path_save . DIRSEP . "style-client.css")) 
			{
				// debug("error - copy style-client.css");
				return ;
			}

			// debug("success css");
		} else {
			// debug("error - Ошибка создания папки для сохранения css");
			return ;
		}
	}

	public static function saveJs($path_save)
	{
		if (self::isDirMkdir($path_save))
		{
			if (!self::isFileCopy(PATH_DIR_MVC_ASSETS . DIRSEP . "js" . DIRSEP . "jquery-3.1.1.min.js", $path_save . DIRSEP . "jquery.min.js")) 
			{
				// debug("error - copy jquery-3.1.1.min.js");
				return ;
			}
			if (!self::isFileCopy(PATH_DIR_MVC_ASSETS . DIRSEP . "js" . DIRSEP . "jquery-ui-1.12.1" . DIRSEP . "jquery-ui.min.js", $path_save . DIRSEP . "jquery-ui.min.js")) 
			{
				// debug("error - copy jquery-ui.min.js");
				return ;
			}
			if (!self::isFileCopy(PATH_DIR_MVC_ASSETS . DIRSEP . "js" . DIRSEP . "bootstrap.min.js", $path_save . DIRSEP . "bootstrap.min.js")) 
			{
				// debug("error - copy bootstrap.min.js");
				return ;
			}
			if (!self::isFileCopy(PATH_DIR_MVC_ASSETS . DIRSEP . "js" . DIRSEP . "script-client.js", $path_save . DIRSEP . "script-client.js")) 
			{
				// debug("error - copy script-client.js");
				return ;
			}

			// debug("success js");
		} else {
			// debug("error - Ошибка создания папки для сохранения js");
			return ;
		}
	}

	public static function createZip($path, $id_site)
	{
		if(extension_loaded('zip'))
		{
			if(is_dir($path))
			{
				// проверяем выбранные файлы
				$zip = new \ZipArchive(); // подгружаем библиотеку zip
				$zip_name = "site_" . $id_site . ".zip"; // имя файла
				$path_zip = registry::app()->path_save_site . DIRSEP . "user_" . registry::app()->user['id_user'];
				if($zip->open($path_zip . DIRSEP . $zip_name, \ZIPARCHIVE::CREATE)!==TRUE)
				{
					// debug("error - create ZIP");
					return ;
				}
				self::addDir($path, $id_site, $zip);
				$zip->close();
				return $path_zip . DIRSEP . $zip_name;
				// if(file_exists($path_zip . DIRSEP . $zip_name))
				// {
				// 	// отдаём файл на скачивание
				// 	header('Content-type: application/zip');
				// 	header('Content-Disposition: attachment; filename="' . $zip_name . '"');
				// 	readfile($path_zip . DIRSEP . $zip_name);
				// 	// удаляем zip файл если он существует
				// 	unlink($path_zip . DIRSEP . $zip_name);
				// }
			} else {
				return ;
			}
			// debug("zip");
		}
	}

	public static function addDir($path, $dir, $zip)
	{
	    $zip->addEmptyDir($dir);
		$nodes = glob($path . DIRSEP . $dir . DIRSEP . '*');
	    // debug($nodes);
	    foreach ($nodes as $node) {
	        if (is_dir($node)) {
	        	// debug(str_replace($path . DIRSEP, '', $node));
	            self::addDir($path, str_replace($path . DIRSEP, '', $node), $zip);
	        } else if (is_file($node)) {
	        	// debug(str_replace($path . DIRSEP, '', $node));
	            $zip->addFile($node, str_replace($path . DIRSEP, '', $node));
	        }
	    }
	}
}
<?php
namespace alimmvc\frontend\models;

use alimmvc\core\registry;
use alimmvc\core\models;

class siteTagsPrint extends models
{
	public function editorPrint($tags, $parent_tag = 0, $parentContent = array())
	{
		foreach ($tags as $tag)
		{
			if ($tag['parent_tag'] == $parent_tag)
			{
				// если не пустой то выводим первый элемент и удаляем его
				if (!empty($parentContent))
				{
					echo array_shift($parentContent);
				}
				$selectors = "";
				$sel = json_decode($tag['selectors']);
				foreach ($sel as $key => $value) {
					$selectors .= " " . $key . "='" . $value . "' ";
				}

				echo "<{$tag['name']} id='{$tag['id_tag']}' index='{$tag['index_tag']}' parent='{$tag['parent_tag']}' {$selectors}>";
				echo "<span class='alm-option'>инфо</span>";

				// позиции тегов внутри данного тега
				$posTegs = registry::app()->siteTagsCrud->readPosTegs($tag['id_tag']);
				// если есть другие теги в нутри текущего
				// делим контент на элементы после которых должны быть другие теги
				if (!empty($posTegs))
				{
					$content = $tag['content'];
					$arrContent = array();
					foreach ($posTegs as $position) 
					{
						$arrContent[] = substr($content, 0, $position['position_in_text']);
						$content = substr($content, $position['position_in_text'], strlen($content));
					}
					$arrContent[] = substr($content, 0, $position['position_in_text']+1);

					$this->editorPrint($tags, $tag['id_tag'], $arrContent);
					echo $arrContent[count($arrContent)-1];
				} 
				else 
				{
					echo "{$tag['content']}";
				}
				echo "</{$tag['name']}>";
			}
		}
	}

	public function freePrint($tags, $parent_tag = 0, $parentContent = array())
	{
		foreach ($tags as $tag)
		{
			if ($tag['parent_tag'] == $parent_tag)
			{
				// если не пустой то выводим первый элемент и удаляем его
				if (!empty($parentContent))
				{
					echo array_shift($parentContent);
				}
				// создаем строку селекторов
				$selectors = "";
				$sel = json_decode($tag['selectors']);
				foreach ($sel as $key => $value) {
					$selectors .= " " . $key . "='" . $value . "' ";
				}

				echo "<{$tag['name']} id='{$tag['id_tag']}' index='{$tag['index_tag']}' parent='{$tag['parent_tag']}' {$selectors}>";

				// позиции тегов внутри данного тега
				$posTegs = registry::app()->siteTagsCrud->readPosTegs($tag['id_tag']);
				// если есть другие теги в нутри текущего
				// делим контент на элементы после которых должны быть другие теги
				if (!empty($posTegs))
				{
					$content = $tag['content'];
					$arrContent = array();
					foreach ($posTegs as $position) 
					{
						$arrContent[] = substr($content, 0, $position['position_in_text']);
						$content = substr($content, $position['position_in_text'], strlen($content));
					}
					$arrContent[] = substr($content, 0, $position['position_in_text']+1);

					$this->freePrint($tags, $tag['id_tag'], $arrContent);
					echo $arrContent[count($arrContent)-1];
				} 
				else 
				{
					echo "{$tag['content']}";
				}
				echo "</{$tag['name']}>";
			}
		}
	}
}
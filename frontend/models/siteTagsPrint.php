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
				$sel = isJSON($tag['selectors'])?json_decode($tag['selectors']):$tag['selectors'];
				$class = "";
				if (!empty($sel)) 
				{
					foreach ($sel as $key => $value) {
						if ($key == 'class') $class = $value;
						$selectors .= " " . $key . "='" . $value . "' ";
					}
				}

				echo "<{$tag['name']} id='{$tag['id_tag']}' index='{$tag['index_tag']}' parent='{$tag['parent_tag']}' {$selectors}>";
				// echo "<div class='alm-option'>";
				// // если элемент блок
				// if (preg_match("|alm-elem-block|ui", $class))
				// 	echo "<span class='glyphicon glyphicon-plus'></span>";
				// // если элемент текстовый
				// if (preg_match("|alm-elem-text|ui", $class))
				// 	echo "<span class='glyphicon glyphicon-pencil'></span>";
				
				// echo "<span class='glyphicon glyphicon-th-large'></span>";
				// echo "<span class='glyphicon glyphicon-minus'></span>";
				// echo "<span class='glyphicon glyphicon-screenshot'></span>";
				// echo "</div>";

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
					// debug($tags);
					$this->editorPrint($tags, $tag['id_tag'], $arrContent);
					
					// echo $arrContent[count($arrContent)-1];
				} 
				// else 
				// {
					if (preg_match("|alm-elem-text|ui", $class) || preg_match("|alm-elem-input|ui", $class))
					{
						echo "<div class='content-text'>{$tag['content']}</div>";
					} else {
						echo "{$tag['content']}";
					}
				// }
				echo "</{$tag['name']}>";
			}
		}
	}

	public function freePrint($tags, $parent_tag = 0)
	{
		$result = "";
		foreach ($tags as $tag)
		{
			if ($tag['parent_tag'] == $parent_tag)
			{
				// создаем строку селекторов
				$selectors = "";
				$sel = isJSON($tag['selectors'])?json_decode($tag['selectors']):$tag['selectors'];
				foreach ($sel as $key => $value) {
					$selectors .= " " . $key . "='" . $value . "' ";
				}
				// echo "{$tag['id_tag']} ";

				$result .= "<{$tag['name']} id='{$tag['id_tag']}' index='{$tag['index_tag']}' parent='{$tag['parent_tag']}' {$selectors}>\n";

				$result .= $this->freePrint($tags, $tag['id_tag']);
				$result .= "{$tag['content']}\n";

				$result .= "</{$tag['name']}>\n";
			}
		}

		return $result;
	}

	public function contentPrintParent($tags, $id_tag = 0, $isIdTag = false)
	{
		$result = "";
		foreach ($tags as $tag)
		{
			if ($tag['parent_tag'] == $id_tag || ($tag['id_tag'] == $id_tag && $isIdTag))
			{
				// создаем строку селекторов
				$selectors = "";
				$sel = isJSON($tag['selectors'])?json_decode($tag['selectors']):$tag['selectors'];
				foreach ($sel as $key => $value) {
					$selectors .= " " . $key . "='" . $value . "' ";
				}
				// echo "{$tag['id_tag']} ";

				$result .= "<{$tag['name']} {$selectors}>";

				$result .= $this->contentPrintParent($tags, $tag['id_tag']);
				$result .= "{$tag['content']}";

				$result .= "</{$tag['name']}>";
				if ($isIdTag) return $result;
			}
		}

		return $result;
	}

	public function replaceParentId($tags, $id_tag = 0, $isIdTag = false)
	{
		$result = "";
		foreach ($tags as $tag)
		{
			if ($tag['parent_tag'] == $id_tag || ($tag['id_tag'] == $id_tag && $isIdTag))
			{
				// создаем строку селекторов
				$selectors = "";
				$sel = isJSON($tag['selectors'])?json_decode($tag['selectors']):$tag['selectors'];
				foreach ($sel as $key => $value) {
					$selectors .= " " . $key . "='" . $value . "' ";
				}
				// echo "{$tag['id_tag']} ";

				$result .= "<{$tag['name']} {$selectors}>";

				$result .= $this->contentPrintParent($tags, $tag['id_tag']);
				$result .= "{$tag['content']}";

				$result .= "</{$tag['name']}>";
				if ($isIdTag) return $result;
			}
		}

		return $result;
	}
}
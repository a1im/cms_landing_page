<?php
namespace alimmvc\frontend\models;

use alimmvc\core\registry;
use alimmvc\core\models;

class parsHtmlCssJs extends models
{
	public static function parsHtml($id_site, $nameTemplate, $parent_tag, $index_tag)
	{
		$tagsData = array();
		$fp = @fopen(PATH_DIR_MVC . DIRSEP . "templates" . DIRSEP . $nameTemplate . ".html", 'r');
		if ($fp)
		{
			$checkTag = false;
			$checkNameTag = false;
			$checkEnd = false; // проверка на закрытие тега
			$checkEndCount = false; // отнять счетчик тега
			$tagName = "";
			$selectors = "";
			$str = "";
			$count = 0; // счетчик вложенности тегов
			$numTag = 0; // нумирация тегов
			$stackParentTag[] = $parent_tag;
			$arrIndex = array();
			// если отсутствует индекс тега то добавляем в конец тегов
			$index_end = 0;
			if ($index_tag == 0)
			{
				$index_end = registry::app()->siteTagsCrud->readLastIndexTeg($parent_tag);
				$index_end++;
			}
			// id последнего тега у сайта
			$endIdTag = registry::app()->siteTagsCrud->readLastIdTag();

			while(!feof($fp))
			{
				$chr = fgetc($fp);

				switch($chr)
				{
					case '<':
						$checkEnd = true;
						if (!empty($str) && count($stackParentTag) > 1) 
						{
							// echo $stackParentTag[count($stackParentTag)-1] . $str . "<br>";
							// var_dump($str);
							//если в первый раз записываем контент
							if (!isset($tagsData[$stackParentTag[count($stackParentTag)-1]]['content']))
							{
								$tagsData[$stackParentTag[count($stackParentTag)-1]]['content'] = $str;
							}
							else $tagsData[$stackParentTag[count($stackParentTag)-1]]['content'] .= $str;
						}
						$str = "";
						$checkTag = $checkNameTag = true;
						break;
					
					case '>':
						// если не закрывающий тег
						if (!preg_match('|^\\.*$|ui', $tagName))
						{
							// вычисляем индекс
							if (!isset($arrIndex[count($stackParentTag)-2])) $arrIndex[count($stackParentTag)-2] = 0;
							$arrIndex[count($stackParentTag)-2]++;

							if (count($stackParentTag) > 1)
							{
								$tagsData[$numTag]['id_tag'] = $numTag + $endIdTag;
								$tagsData[$numTag]['id_site'] = $id_site;
								$tagsData[$numTag]['name'] = $tagName;
								$tagsData[$numTag]['selectors'] = $selectors;
								$tagsData[$numTag]['parent_tag'] = $stackParentTag[count($stackParentTag)-2];
								if ($tagsData[$numTag]['parent_tag'] != $parent_tag) 
								{
									$tagsData[$numTag]['parent_tag'] += $endIdTag;
								}
								// для первого уровня вложенности тегов вычисляем индекс
								if ($count == 1)
								{
									$tagsData[$numTag]['index_tag'] = $index_end + $index_tag++;
								}
								else $tagsData[$numTag]['index_tag'] = $arrIndex[count($stackParentTag)-2];

								// указываем позицию в тексте
								if (isset($tagsData[$stackParentTag[count($stackParentTag)-2]]['content']))
								{
									$tagsData[$numTag]['position_in_text'] = strlen($tagsData[$stackParentTag[count($stackParentTag)-2]]['content']);
								} 
								else $tagsData[$numTag]['position_in_text'] = 0;
								if (!isset($tagsData[$numTag]['content'])) $tagsData[$numTag]['content'] = "";
							}

							// echo $count . ": " . $tagName . $selectors . "<br>";
						}

						$tagName = $selectors = "";
						$checkTag = $checkNameTag = false;
						// если закрывающий тег
						if ($checkEndCount) 
						{
							$checkEndCount = false;
							$count--;
							array_pop($stackParentTag); // удалить последний элемент массива
							
						}
						break;

					case '/':
						if ($checkEnd) 
						{
							$checkEnd = false;
							$checkEndCount = true;
						}

					case ' ':
						if ($checkNameTag) 
						{	
							$checkNameTag = false;
						}

					default:
						if ($checkEnd) 
						{
							$checkEnd = false;
							$count++;
							$numTag++;
							$stackParentTag[] = $numTag;
						}
						if ($checkNameTag) $tagName .= $chr;
						else if ($checkTag) $selectors .= $chr;
						else $str .= $chr;
				}
			}

			// debug($tagsData);
		} 
		@fclose($fp);

		return $tagsData;
	}

	public static function parsCss($id_site, $nameTemplate)
	{
		$cssData = array();
		$fp = @fopen(PATH_DIR_MVC . DIRSEP . "templates" . DIRSEP . $nameTemplate . ".css", 'r');

		if ($fp)
		{
			$str = "";
			while(!feof($fp))
			{
				$chr = fgetc($fp);

				switch($chr)
				{
					case '{':
						$cssData[]['selector'] = preg_replace("|[\n\t]|iu", ' ', $str);
						$cssData[count($cssData) - 1]['id_site'] = $id_site;
						$cssData[count($cssData) - 1]['id_tag'] = NULL;
						$str = "";
						break;
					case '}':
						$cssData[count($cssData) - 1]['style'] = preg_replace("|[\n\t ]|iu", '', $str);
						$str = "";
						break;
					default:
						$str .= $chr;
				}
			}
		}

		return $cssData;
	}

	public static function parsSelectors($selectors)
	{
		$result = array();
		// $selectors
		while (preg_match("|=|iu", $selectors))
		{
			$selector = preg_replace("/=.*$/iu", "", $selectors);
			$selectors = preg_replace("/^.*?=[ \"\']?/iu", "", $selectors);
			$param = preg_replace("/[\"\'].*$/iu", "", $selectors);
			$selectors = preg_replace("/^.*?[\"\']/iu", "", $selectors);
			$result[trim($selector)] = trim($param);
			// echo $selector . ":" . $param . " -" . $selectors . "<br>";
		}
		// debug($result);
		return $result;
	}
}
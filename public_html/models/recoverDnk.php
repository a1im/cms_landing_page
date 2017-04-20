<?php

namespace alimmvc\public_html\models;

class recoverDnk
{
	private $countFragment;
	private $resultPath = array();

	public function recover($dnk)
	{
		if (empty($dnk) || !preg_match("/^[АаГгЦцУу]+(-[АаГгЦцУу]+)*$/u", $dnk)) 
		{
			// return true;
			return false;
		}
		// if (empty($dnk)) return true;
		$arrDnk = explode('-', $dnk);
		$this->countFragment = sizeof($arrDnk);

		$graphPath = array();
		//обходим все фрагменты
		for($i = 0; $i < $this->countFragment; $i++)
		{
			//возводим в верхний регистр чтобы небыло проблем с поиском
			$frag1 = mb_strtoupper($arrDnk[$i]); 
			$graphPath[$frag1] = array();

			// for($ii = $i+1; $ii < $this->countFragment; $ii++)
			// {
			for($ii = 0; $ii < $this->countFragment; $ii++)
			{
				if ($i == $ii) continue;
				$frag2 = mb_strtoupper($arrDnk[$ii]);
				//если больше 2х символов совпадает
				if (($cnt = $this->countCharMatch($frag1, $frag2)) > 1)
				{
					$graphPath[$frag1][] = $frag2;
				}
			}
		}

		// debug($graphPath);

		$this->findPathAll($graphPath);

		$result = "";
		if (isset($this->resultPath) && sizeof($this->resultPath) == 1)
		{
			$result = $this->recoveryFragment($this->resultPath[0]);
		}

		// return (empty($result))?true:false;
		return (empty($result))?false:$result;
	}

	//сколько символов совпадает
	private function countCharMatch($frag1, $frag2)
	{
		$frag1 = preg_split('//u',$frag1, -1, PREG_SPLIT_NO_EMPTY);
		$frag2 = preg_split('//u',$frag2, -1, PREG_SPLIT_NO_EMPTY);
		$summCharMatch = 0;
		for($j = 1; $j <= sizeof($frag1) && $j <= sizeof($frag2); $j++)
		{
			$ismatch = true;
			$k = 0;
			// можно соединить?
			for(; $k < $j; $k++)
			{
				// debug($frag1[sizeof($frag1)-$j+$k]);
				if($frag1[sizeof($frag1)-$j+$k] != $frag2[$k])
				{
					$ismatch = false;
					break;
				}
			}
			if ($ismatch) $summCharMatch = $k;
		}
		return $summCharMatch;
	}

	// вызываем поиск пути от каждой вершины
	private function findPathAll(array $graphPath)
	{
		foreach ($graphPath as $key => $frag) 
		{
			$this->findPath($graphPath, [], $key);
		}

		// debug($this->resultPath);
	}

	// поиск пути рекурсивно
	private function findPath(array $graphPath, array $resultPath, $frag)
	{
		$resultPath[] = $frag;
		if (sizeof($resultPath) == sizeof($graphPath))
		{
			$this->resultPath[] = $resultPath;
		}

		if (isset($graphPath[$frag]))
		{
			foreach ($graphPath[$frag] as $key1 => $frag1) 
			{
				if (in_array($frag1, $resultPath)) continue;
				$result = $this->findPath($graphPath, $resultPath, $frag1);
			}
		}
	}

	// // поиск пути рекурсивно
	// private function findPath(array $graphPath, array $graphResult, $key1, $key2)
	// {
	// 	$graphResult[] = $graphPath[$key1][$key2];
	// 	debug($graphResult);
	// 	if (sizeof($graphResult) == sizeof($graphPath))
	// 	{
	// 		$this->resultPath[] = $graphResult;
	// 	}

	// 	if (isset($graphPath[$key1]))
	// 	{
	// 		$id = 0;
	// 		foreach ($graphPath as $ikey => $ivalue) {
	// 			if ($graphPath[$ikey][0] == $graphPath[$key1][$key2])
	// 			{
	// 				$id = $ikey;
	// 				break;
	// 			}
	// 		}
	// 		foreach ($graphPath[$id] as $ikey => $ivalue) 
	// 		{
	// 			if (in_array($graphPath[$key1][$ikey], $graphResult)) continue;
	// 			$this->findPath($graphPath, $graphResult, $id, $ikey);
	// 		}
	// 	}
	// }

	//соеденить фрагменты ДНК
	private function recoveryFragment($arrFrag)
	{
		if (sizeof($arrFrag) > 1)
		{
			$result = mb_strtoupper($arrFrag[0]);
			for($i = 1; $i < sizeof($arrFrag); $i++)
			{
				$frag = mb_strtoupper($arrFrag[$i]);
				$start = $this->countCharMatch($result, $frag);
				$result .= mb_substr($frag, $start);
			}
			return $result;
		} else {
			return (empty($arrFrag))?"":$arrFrag[0];
		}
	}
}
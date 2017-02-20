<?php

namespace alimmvc\core\fields;

class fieldTextFile extends fieldText
{
	//адрес до папки img
	private $dirUpload = "";
	private $outFileName = "";
	private $files;

	public function __construct($dirUpload, $name, $caption, $files = array(), $isRequired = false)
	{
		parent::__construct($name, $caption, "", $isRequired, "", 255, 41);

		$this->type = "file";
		$this->pattern = "#^.+\.(JPG)|(GIF)|(PNG)$#i";
		$this->strError = "Допустимые форматы: JPG, GIF, PNG";
		$this->css_class = "";
		$this->value = htmlspecialchars(!empty($files[$this->name])?$files[$this->name]['name']:"");
		$this->files = !empty($files[$this->name])?$files[$this->name]:array();

		$this->dirUpload = strval($dirUpload);
	}

	//загрузка файла на сервер
	public function uploadFile()
	{
		$files = $this->files;
		if (empty($files) || $files['error'] != 0) return false;

		$dirUpload = $this->dirUpload;
		$fileName = basename($files['name']);

		//расширение файла
		$extension = preg_replace("/^image\//i", '', $files['type']);

		//если файл существует добавим префикс
		$prefFile = "";
		$i = 1;
		while(file_exists($dirUpload . $prefFile . $fileName))
		{
			$prefFile = $i++ . "_";
		}
		$newFileLocation = $dirUpload . $prefFile . $fileName;

		switch ($extension) {
			case 'jpg':
			case 'jpeg':
			$isfile = $this->resizeImgJpeg($newFileLocation, $files['tmp_name']);
			break;
			case 'png':
			$isfile = $this->resizeImgPng($newFileLocation, $files['tmp_name']);
			break;
			case 'gif':
			$isfile = $this->resizeImgGif($newFileLocation, $files['tmp_name']);
			break;
			default:
			break;
		}
		if (!$isfile && copy($files['tmp_name'] , $newFileLocation)) 
		{
			//уничтожаем временный файл
			@unlink($files['tmp_name']);
		}
		$this->outFileName = $fileName;
		return true;
	}

	//уменьшить изображение если оно больше допустимого значения
	private function resizeImgJpeg($outfile, $infile, $maxWidth = 320, $maxHeight = 240, $quality = 80)
	{
		$img = ImageCreateFromJpeg($infile);
		if (imagesx($img) <= $maxWidth || imagesy($img) <= $maxHeight) return false;
		$img1 = ImageCreateTrueColor($maxWidth,$maxHeight);
		imagecopyresampled($img1,$img,0,0,0,0,$maxWidth,$maxHeight,imagesx($img),imagesy($img));
		//сохраним
		imagejpeg($img1,$outfile,$quality);
		imagedestroy($img);
		imagedestroy($img1);
		return true;
	}

	//уменьшить изображение если оно больше допустимого значения
	private function resizeImgPng($outfile, $infile, $maxWidth = 320, $maxHeight = 240)
	{
		$img = ImageCreateFromPng($infile);
		if (imagesx($img) <= $maxWidth || imagesy($img) <= $maxHeight) return false;
		$img1 = ImageCreateTrueColor($maxWidth,$maxHeight);
		imagecopyresampled($img1,$img,0,0,0,0,$maxWidth,$maxHeight,imagesx($img),imagesy($img));
		//сохраним
		imagePng($img1,$outfile);
		imagedestroy($img);
		imagedestroy($img1);
		return true;
	}

	//уменьшить изображение если оно больше допустимого значения
	private function resizeImgGif($outfile, $infile, $maxWidth = 320, $maxHeight = 240)
	{
		$img = ImageCreateFromGif($infile);
		if (imagesx($img) <= $maxWidth || imagesy($img) <= $maxHeight) return false;
		$img1 = ImageCreateTrueColor($maxWidth,$maxHeight);
		imagecopyresampled($img1,$img,0,0,0,0,$maxWidth,$maxHeight,imagesx($img),imagesy($img));
		//сохраним
		imageGif($img1,$outfile);
		imagedestroy($img);
		imagedestroy($img1);
		return true;
	}

	//получить значение для БД
	public function getDbValue()
	{
		return $this->outFileName;
	}
}
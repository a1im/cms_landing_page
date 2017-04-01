<?php 

function debug($str)
{
	echo "<pre>" . print_r($str, true) . "</pre>";
}

// function getVal($val)
// {
// 	return (isset($val))?$val:"";
// }

function getPost($name)
{
	return (isset($_POST[$name]))?$_POST[$name]:"";
}

// проверка на JSON
function isJSON($string) {
    return ((is_string($string) && (is_object(json_decode($string)) || is_array(json_decode($string))))) ? true : false;
}
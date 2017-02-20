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
<?php

$to = "a1imov233@yandex.ru";

$fields = (isset($_POST['fields']))?json_decode($_POST['fields']):"";
$theme = (isset($_POST['theme']))?$_POST['theme']:"";

function clearString($string){
	return htmlspecialchars(stripslashes($string));
}

$message = (!empty($theme))?clearString($theme)."\r\n\n":"";

foreach ($fields as $value) {
	$message .= clearString($value) . "\r\n";
}

$headers = "";
$headers .= "MIME-Version: 1.0 \r\n"; 
$headers .= "Content-type: text/plain; charset=utf-8 \r\n";
$headers .= "Subject: " . $theme . " \r\n"; 
$headers .= "X-Mailer: PHP/".phpversion()."\r\n";

mail($to, $theme, $message, $headers);

// echo "Заявка '" . $theme . "' отправлена!\n";
echo $message;




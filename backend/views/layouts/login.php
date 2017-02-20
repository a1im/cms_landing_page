<?php
	use alimmvc\core\registry;
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?=isset($title)?$title:""?></title>
	<link rel="stylesheet" type="text/css" href="<?=SITE_URL_ASSETS?>/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?=SITE_URL_ASSETS?>/css/style-auth.css">

	<script type="text/javascript" src="<?=SITE_URL_ASSETS?>/js/jquery-3.1.1.min.js"></script>
</head>
<body>

	<div id="authForm" class="panel panel-default">
		<?=$form?>
	</div>

</body>
</html>
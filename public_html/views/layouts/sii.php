<?php
use alimmvc\core\registry;
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?=isset($title)?$title:""?></title>
	<link rel="stylesheet" type="text/css" href="<?=SITE_URL_ASSETS?>/css/bootstrap.min.css">

	<script type="text/javascript" src="<?=SITE_URL_ASSETS?>/js/jquery-3.1.1.min.js"></script>
	<script type="text/javascript" src="<?=SITE_URL_ASSETS?>/js/bootstrap.min.js"></script>
</head>
<body>
	<div class="panel panel-primary">
		<div class="panel-heading"><?=isset($title)?$title:""?></div>
		<div class="panel-body">
			<?php require_once registry::app()->dir_views_controller . DIRSEP . $file_action . ".php"; ?>
		</div>
		<div class="panel-footer">alim©2016</div>
	</div>
</body>
</html>
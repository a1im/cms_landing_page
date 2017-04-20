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
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<!-- <a class="navbar-brand" href="#">Форма обратной связи</a> -->
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><a href="<?=SITE_URL_NAME?>/">Главная</a></li>
					<li><a href="<?=SITE_URL_NAME?>/editor">Редактор</a></li>
				</ul>
			</div><!-- /.navbar-collapse -->
		</div><!-- /.container-fluid -->
	</nav>
	<?php require_once registry::app()->dir_views_controller . DIRSEP . $file_action . ".php"; ?>

</body>
</html>
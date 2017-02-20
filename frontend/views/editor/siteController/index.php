<?php
use alimmvc\core\registry;
use alimmvc\frontend\models\siteTagsPrint;
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?=isset($dataSite['title'])?$dataSite['title']:""?></title>
	<link rel="stylesheet" type="text/css" href="<?=SITE_URL_ASSETS?>/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?=SITE_URL_ASSETS?>/js/jquery-ui-1.12.1/jquery-ui.min.css">
	<link rel="stylesheet" type="text/css" href="<?=SITE_URL_ASSETS?>/css/style-editor.css">
	<style>
<?php foreach ($styles as $rowstyle): ?>
	<?=$rowstyle['selector']?>{<?=$rowstyle['style']?>}
<?php endforeach; ?>
	</style>


	<script type="text/javascript" src="<?=SITE_URL_ASSETS?>/js/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="<?=SITE_URL_ASSETS?>/js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?=SITE_URL_ASSETS?>/js/script-dad.js"></script>
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
					<li><a href="<?=SITE_URL_NAME?>/editor">Админка</a></li>
					<li><a href="<?=SITE_URL_NAME?>/editor/profile">Профиль</a></li>
					<li><a href="<?=SITE_URL_NAME?>/site/<?=registry::app()->user['id_user']?>/<?=$id_site?>" target="_blank">Посмотреть</a></li>
					<li><a href="<?=SITE_URL_NAME?>/editor/auth/logout">Выход</a></li>
				</ul>
			</div><!-- /.navbar-collapse -->
		</div><!-- /.container-fluid -->
	</nav>

	<div id="id-site" style="display: none;"><?=$id_site?></div>
	<img id="loader" src="<?=SITE_URL_ASSETS?>/image/loader1.gif"/>

<?php
	// вывод сайта
	$printTags = new siteTagsPrint();
	$printTags->editorPrint($tags);
?>

<br>
<div align=center>
	<a href="<?=$urlController?>/addhtml/<?=$dataSite['id_site']?>/screen-block" class="btn btn-default">Добавить блок</a>
</div>

</body>
</html>
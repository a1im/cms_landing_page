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
	<style>
<?php foreach ($styles as $rowstyle): ?>
	<?=$rowstyle['selector']?>{<?=$rowstyle['style']?>}
<?php endforeach; ?>
	</style>
	<script type="text/javascript" src="<?=SITE_URL_ASSETS?>/js/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="<?=SITE_URL_ASSETS?>/js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
</head>
<body>
<?php
	// вывод сайта
	$printTags = new siteTagsPrint();
	$printTags->freePrint($tags);
?>
</body>
</html>
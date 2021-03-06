<?php
use alimmvc\core\registry;
use alimmvc\public_html\models\siteTagsPrint;
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, inicial-scale=1.0" />
	<title><?=isset($dataSite['title'])?$dataSite['title']:""?></title>
	<link rel="stylesheet" type="text/css" href="<?=SITE_URL_ASSETS?>/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?=SITE_URL_ASSETS?>/css/mytinymce.css" />
	<link rel="stylesheet" type="text/css" href="<?=SITE_URL_ASSETS?>/js/jquery-ui-1.12.1/jquery-ui.min.css">
	<link rel="stylesheet" type="text/css" href="<?=SITE_URL_ASSETS?>/css/style-client.css" />
	<style>
<?php foreach ($styles as $rowstyle): ?>
	<?=$rowstyle['selector']?>{<?=$rowstyle['style']?>}
<?php endforeach; ?>
	</style>
	<script type="text/javascript" src="<?=SITE_URL_ASSETS?>/js/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="<?=SITE_URL_ASSETS?>/js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="<?=SITE_URL_ASSETS?>/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?=SITE_URL_ASSETS?>/js/script-client.js"></script>
</head>
<body>

<content id="site-content">

<?php
	// вывод сайта
	$printTags = new siteTagsPrint();
	echo $printTags->freePrint($tags);
?>

</content>

</body>
</html>
<!DOCTYPE HTML>
<html>
<?php
use alimmvc\core\registry;
use alimmvc\frontend\models\siteTagsPrint;
?>
<head>
	<meta charset="utf-8">
	<title><?=isset($dataSite['title'])?$dataSite['title']:""?></title>
	<link rel="stylesheet" type="text/css" href="<?=SITE_URL_ASSETS?>/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?=SITE_URL_ASSETS?>/js/jquery-ui-1.12.1/jquery-ui.min.css">
	<link rel="stylesheet" type="text/css" href="<?=SITE_URL_ASSETS?>/css/style-editor.css">
	<link rel="stylesheet" type="text/css" href="<?=SITE_URL_ASSETS?>/colorpicker/css/colorpicker.css" />
	<link rel="stylesheet" type="text/css" href="<?=SITE_URL_ASSETS?>/css/mytinymce.css" />
	<link rel="stylesheet" type="text/css" href="<?=SITE_URL_ASSETS?>/css/style-client.css" />
<!--  	<link rel="stylesheet" media="screen" type="text/css" href='//fonts.googleapis.com/css?family=Lato:300,300i,400,400i' />
	<link rel="stylesheet" media="screen" type="text/css" href='//www.tinymce.com/css/codepen.min.css' /> -->

	<script type="text/javascript" src="<?=SITE_URL_ASSETS?>/js/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="<?=SITE_URL_ASSETS?>/js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="<?=SITE_URL_ASSETS?>/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?=SITE_URL_ASSETS?>/js/script-client.js"></script>
    <script type="text/javascript" src="<?=SITE_URL_ASSETS?>/colorpicker/js/colorpicker.js"></script>
    <script type="text/javascript" src="<?=SITE_URL_ASSETS?>/tinymce/tinymce.min.js"></script>
	<script type="text/javascript" src="<?=SITE_URL_ASSETS?>/js/script-dad.js"></script>
	<script type="text/javascript" src="<?=SITE_URL_ASSETS?>/js/script-tinymce.js"></script>
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
					<li><a href="<?=SITE_URL_NAME?>/editor/site/uploadFullSite/<?=$id_site?>">Скачать исходники</a></li>
					<li><a href="<?=SITE_URL_NAME?>/editor/auth/logout">Выход</a></li>
				</ul>
			</div><!-- /.navbar-collapse -->
		</div><!-- /.container-fluid -->
	</nav>

	<!-- id сайта -->
	<div id="id-site" style="display: none;"><?=$id_site?></div>
	<!-- загрузка -->
	<img id="loader" src="<?=SITE_URL_ASSETS?>/image/loader1.gif"/>
	<!-- добавление элементов -->
	<div id="popup-add" class="alm-popup">
		<span class="popup-close glyphicon glyphicon-remove"></span>
		<button class='add-elem btn btn-default' type-elem='form'>Добавить форму</button>
		<button class='add-elem btn btn-default' type-elem='block'>Добавить блок</button>
		<button class='add-elem btn btn-default' type-elem='text'>Добавить текст</button>
	</div>
	<!-- изменение элемента -->
	<div id="popup-edit" class="alm-popup">
		<span class="popup-close glyphicon glyphicon-remove"></span>

		<div class="blk-selector-param">
			<select id="opc-select-param" class="form-control">
				<option value="" selected>Обычный</option>
				<option value=":hover">При наведении</option>
				<option value=":active">Активный</option>
				<option value=":focus">Фокус</option>
			</select>
		</div>
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="opc-block-screen opc-block opc-text opc-menu opc-form opc-form-input active"><a href="#popup-edit-glav" aria-controls="popup-edit-glav" role="tab" data-toggle="tab">Главные</a></li>
			<li role="presentation" class="opc-menu"><a href="#popup-edit-ul-li" aria-controls="popup-edit-ul-li" role="tab" data-toggle="tab">Элементы меню</a></li>
			<li role="presentation" class="opc-form-input"><a href="#popup-edit-input" aria-controls="popup-edit-input" role="tab" data-toggle="tab">Поле ввода</a></li>
			<li role="presentation" class="opc-block-screen opc-block opc-text opc-menu opc-form opc-form-input"><a href="#popup-edit-border" aria-controls="popup-edit-border" role="tab" data-toggle="tab">Рамка</a></li>
			<li role="presentation" class="opc-block-screen opc-block opc-text opc-menu opc-form opc-form-input"><a href="#popup-edit-border-radius" aria-controls="popup-edit-border-radius" role="tab" data-toggle="tab">Края</a></li>
			<li role="presentation" class="opc-block-screen opc-block opc-text opc-menu opc-form opc-form-input"><a href="#popup-edit-shadow" aria-controls="popup-edit-shadow" role="tab" data-toggle="tab">Тень</a></li>
		</ul>
		<!-- Tab panes -->
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="popup-edit-glav">
				<table class="table">
					<tr class="opc-block-screen"><td>№ блока:</td><td>
						<input class="numBlockScreen form-control" type="number" min='1' step="1"/>
					</td></tr>
					<tr class="opc-block-screen"><td>Имя блока:</td><td>
						<input class="nameBlockScreen form-control" type="text" size='20'/>
					</td></tr>
					<tr class="opc-form"><td>Тема:</td><td>
						<input class="opc-edit-attr form-control" edit-attr="theme" type="text"/>
					</td></tr>
					<tr class="opc-menu"><td>Отступ:</td>
						<td>
							<label class="opc-style-clear">clear</label>
							<label class="opc-lock">lock<input type="checkbox"/></label>
							<input class="squareStyle opc-edit-style" edit-style="padding-top" edit-type="int" type="number" min='0' step="1"/><br>
							<input class="squareStyle opc-edit-style" edit-style="padding-left" edit-type="int" type="number" min='0' step="1"/>
							<input class="squareStyle opc-edit-style" edit-style="padding-right" edit-type="int" type="number" min='0' step="1"/><br>
							<input class="squareStyle opc-edit-style" edit-style="padding-bottom" edit-type="int" type="number" min='0' step="1"/>
						</td>
					</tr>
					<tr class="opc-block opc-text opc-form opc-form-input"><td>Выравнивание:</td><td>
						<span class="glyphicon glyphicon-object-align-left"></span>
						<span class="glyphicon glyphicon-object-align-vertical"></span>
						<span class="glyphicon glyphicon-object-align-right"></span>
						<br>
						<span class="glyphicon glyphicon-object-align-top"></span>
						<span class="glyphicon glyphicon-object-align-horizontal"></span>
						<span class="glyphicon glyphicon-object-align-bottom"></span>
					</td></tr>
					<tr class="opc-block opc-form opc-form-input"><td>Растянуть:</td><td>
						<span class="glyphicon glyphicon-resize-horizontal"></span>
						<span class="glyphicon glyphicon-resize-vertical"></span>
					</td></tr>
					<tr class="opc-block opc-text opc-form opc-form-input"><td>Координаты:</td><td>
						<div class="form-inline">
							<label>X</label>
							<input class="cordX form-control" type="number" step="1"/>
							<label>Y</label>
							<input class="cordY form-control" type="number" step="1"/>
						</div>
					</td></tr>
					<tr class="opc-block opc-form opc-form-input"><td>Размер:</td><td>
						<div class="form-inline">
							<label>W</label>
							<input class="sizeW form-control" type="number" step="1"/>
							<label>H</label>
							<input class="sizeH form-control" type="number" step="1"/>
						</div>
					</td></tr>
					<tr class="opc-block-screen opc-block opc-text opc-menu opc-form opc-form-input"><td>Цвет заливки:</td><td>
						<label class="opc-style-clear">clear</label>
						<input class="opc-edit-style" edit-style="background-color" edit-color="color" type="text" />
					</td></tr>
					<tr class="opc-block-screen opc-block opc-text opc-menu opc-form opc-form-input"><td>Прозрачность:</td><td>
						<label class="opc-style-clear">clear</label>
						<input class="opc-edit-style" edit-style="opacity" type="range" min="0" max="1" step="0.01" value="1">
					</td></tr>
				</table>
			</div>
			<div role="tabpanel" class="tab-pane" id="popup-edit-ul-li">
				<table class="table">
					<tr><td>Размер шрифта:</td><td>
						<label class="opc-style-clear">clear</label>
						<input class="opc-edit-style form-control" edit-style="font-size" edit-type="int" edit-elem=" li a" type="number" min='0' step="1"/>
					</td></tr>
					<tr><td>Цвет заливки:</td><td>
						<label class="opc-style-clear">clear</label>
						<input class="opc-edit-style" edit-style="background-color" edit-color="color" edit-elem=" li a" type="text" />
					</td></tr>
					<tr><td>Цвет текста:</td><td>
						<label class="opc-style-clear">clear</label>
						<input class="opc-edit-style" edit-style="color" edit-color="color" edit-elem=" li a" type="text" />
					</td></tr>
				</table>
			</div>
			<div role="tabpanel" class="tab-pane" id="popup-edit-input">
				<table class="table">
					<tr><td>Тип:</td><td>
						<select class="opc-edit-attr form-control" edit-attr="type" edit-elem="input">
							<!-- <option>button</option> -->
							<option>reset</option>
							<option>submit</option>
							<option>text</option>
							<option>date</option>
							<option>email</option>
							<option>number</option>
							<option>range</option>
							<option>tel</option>
							<option>time</option>
							<option>month</option>
							<option>week</option>
						</select>
					</td></tr>
					<tr><td>Имя:</td><td>
						<input class="opc-edit-attr form-control" edit-attr="name" edit-elem="input" type="text"/>
					</td></tr>
					<tr><td>Значение:</td><td>
						<input class="opc-edit-attr form-control" edit-attr="value" edit-elem="input" type="text"/>
					</td></tr>
					<tr><td>Заполнитель:</td><td>
						<input class="opc-edit-attr form-control" edit-attr="placeholder" edit-elem="input" type="text"/>
					</td></tr>
					<tr><td>Размер шрифта:</td><td>
						<label class="opc-style-clear">clear</label>
						<input class="opc-edit-style form-control" edit-style="font-size" edit-type="int" edit-elem=" input" type="number" min='0' step="1"/>
					</td></tr>
					<tr><td>Цвет ввода:</td><td>
						<label class="opc-style-clear">clear</label>
						<input class="opc-edit-style" edit-style="color" edit-color="color" edit-elem=" input" type="text" />
					</td></tr>
					<tr><td>Цвет заполнителя:</td><td>
						<label class="opc-style-clear">clear</label>
						<input class="opc-edit-style" edit-style="color" edit-color="color" edit-elem=" input" edit-vendor="::-webkit-input-placeholder" type="text" />
					</td></tr>
				</table>
			</div>
			<div role="tabpanel" class="tab-pane" id="popup-edit-border">
				<table class="table">
					<tr><td>Стиль рамки:</td><td>
						<label class="opc-style-clear">clear</label>
						<select class="opc-edit-style form-control" edit-style="border-style">
							<option>dotted</option>
							<option>dashed</option>
							<option>solid</option>
							<option>double</option>
							<option>groove</option>
							<option>ridge</option>
							<option>inset</option>
							<option>outset</option>
						</select>
					</td></tr>
					<tr>
						<td>Цвет:</td>
						<td>
							<label class="opc-style-clear">clear</label>
							<label class="opc-lock">lock<input class="borderColorLock" type="checkbox" step="1"/></label>
							<input class="squareStyle opc-edit-style" edit-style="border-top-color" edit-color="color" type="text" /><br>
							<input class="squareStyle opc-edit-style" edit-style="border-left-color" edit-color="color" type="text" />
							<input class="squareStyle opc-edit-style" edit-style="border-right-color" edit-color="color" type="text" /><br>
							<input class="squareStyle opc-edit-style" edit-style="border-bottom-color" edit-color="color" type="text" />
						</td>
					</tr>
					<tr>
						<td>Размер:</td>
						<td>
							<label class="opc-style-clear">clear</label>
							<label class="opc-lock">lock<input type="checkbox"/></label>
							<input class="squareStyle opc-edit-style" edit-style="border-top-width" edit-type="int" type="number" step="1"/><br>
							<input class="squareStyle opc-edit-style" edit-style="border-left-width" edit-type="int" type="number" step="1"/>
							<input class="squareStyle opc-edit-style" edit-style="border-right-width" edit-type="int" type="number" step="1"/><br>
							<input class="squareStyle opc-edit-style" edit-style="border-bottom-width" edit-type="int" type="number" step="1"/>
						</td>
					</tr>
				</table>
			</div>
			<div role="tabpanel" class="tab-pane" id="popup-edit-border-radius">
				<table class="table">
					<tr><td>Радиус:</td><td>
						<label class="opc-style-clear">clear</label>
						<label class="opc-lock">lock<input type="checkbox"/></label>
						<label class="opc-label">Размер верх лево:</label><br>
						<input class="opc-edit-style" edit-style="border-top-left-radius" edit-type="radius" type="range" step="1" min="0" max="100" value="0"/>
						<input class="opc-edit-style" edit-style="border-top-left-radius" edit-type="radius" type="range" step="1" min="0" max="100" value="0"/>
						<label class="opc-label">Размер верх право:</label><br>
						<input class="opc-edit-style" edit-style="border-top-right-radius" edit-type="radius" type="range" step="1" min="0" max="100" value="0"/>
						<input class="opc-edit-style" edit-style="border-top-right-radius" edit-type="radius" type="range" step="1" min="0" max="100" value="0"/>
						<label class="opc-label">Размер низ лево:</label><br>
						<input class="opc-edit-style" edit-style="border-bottom-left-radius" edit-type="radius" type="range" step="1" min="0" max="100" value="0"/>
						<input class="opc-edit-style" edit-style="border-bottom-left-radius" edit-type="radius" type="range" step="1" min="0" max="100" value="0"/>
						<label class="opc-label">Размер низ право:</label><br>
						<input class="opc-edit-style" edit-style="border-bottom-right-radius" edit-type="radius" type="range" step="1" min="0" max="100" value="0"/>
						<input class="opc-edit-style" edit-style="border-bottom-right-radius" edit-type="radius" type="range" step="1" min="0" max="100" value="0"/>
					</td></tr>
				</table>
			</div>
			<div role="tabpanel" class="tab-pane" id="popup-edit-shadow">
				<table class="table">
					<tr><td>Сдвиг по x:</td><td>
						<label class="opc-style-clear">clear</label>
						<input class="opc-edit-style form-control" edit-style="box-shadow" edit-type="shadow" type="number" step="1" value="0"/>
					</td></tr>
					<tr><td>Сдвиг по y:</td><td>
						<input class="opc-edit-style form-control" edit-style="box-shadow" edit-type="shadow" type="number" step="1" value="0"/>
					</td></tr>
					<tr><td>Радиус размытия:</td><td>
						<input class="opc-edit-style form-control" edit-style="box-shadow" edit-type="shadow" type="number" step="1" value="0"/>
					</td></tr>
					<tr><td>Растяжение:</td><td>
						<input class="opc-edit-style form-control" edit-style="box-shadow" edit-type="shadow" type="number" step="1" value="0"/>
					</td></tr>
					<tr><td>Цвет:</td><td>
						<input class="opc-edit-style" edit-style="box-shadow" edit-type="shadow" edit-color="color" type="text" />
					</td></tr>
				</table>
			</div>
		</div>
	</div>

	<!-- Изменение текста -->
	<div id="popup-edit-text" class="alm-popup">
		<span class="popup-close glyphicon glyphicon-remove"></span>
		<textarea id='textarea-tiny'></textarea>
	</div>

	<!-- опции для редактирования -->
	<div id='alm-option'>
		<span class='option-add glyphicon glyphicon-plus'></span>
		<span class='option-edit glyphicon glyphicon-pencil'></span>
		<span class='option-param glyphicon glyphicon-th-large'></span>
		<span class='option-copy glyphicon glyphicon-copy'></span>
		<span class='option-del glyphicon glyphicon-minus'></span>
	</div>

<div class="menu-add-elem" align="center">
	<button class='add-elem add-main-menu btn btn-default' type-elem='main-menu'>Меню</button>
	<button class='add-elem add-screen-block btn btn-default' type-elem='screen-block'>Блок</button>
</div>

<content id="site-content">

<?php
	// вывод сайта
	$printTags = new siteTagsPrint();
	$printTags->editorPrint($tags);
?>

</content>

<div class="menu-add-elem" align="center">
	<button class='add-elem add-main-menu btn btn-default' type-elem='main-menu'>Меню</button>
	<button class='add-elem add-screen-block btn btn-default' type-elem='screen-block'>Блок</button>
</div>

<!-- Стили из бд -->
<?php foreach ($styles as $rowstyle): ?>
	<style id-tag="<?=$rowstyle['id_tag']?>"><?=$rowstyle['selector']?></style>
<?php endforeach; ?>

</body>
</html>
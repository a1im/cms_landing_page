<?php
use alimmvc\frontend\models\messageForm;
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4>Сортировка: </h4>
		<ul class="list-inline">
			<li>
				<a href="<?=$urlAction?>/name/<?=($sort == "name")?$sc:"DESC"?>">по имени</a>
				<?php if ($sort == "name" && $sc == "DESC"): ?>
					<span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span>
				<?php endif; ?>
				<?php if ($sort == "name" && $sc == "ASC"): ?>
					<span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span>
				<?php endif; ?>
			</li>
			<li>
				<a href="<?=$urlAction?>/email/<?=($sort == "email")?$sc:"DESC"?>">по e-mail</a>
				<?php if ($sort == "email" && $sc == "DESC"): ?>
					<span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span>
				<?php endif; ?>
				<?php if ($sort == "email" && $sc == "ASC"): ?>
					<span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span>
				<?php endif; ?>
			</li>
			<li>
				<a href="<?=$urlAction?>/date/<?=($sort == "date")?$sc:"DESC"?>">по дате</a>
				<?php if ($sort == "date" && $sc == "DESC"): ?>
					<span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span>
				<?php endif; ?>
				<?php if ($sort == "date" && $sc == "ASC"): ?>
					<span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span>
				<?php endif; ?>
			</li>
		</ul>
	</div>
	<div class="panel-body">
		<ul class="list-group">
			<?php foreach ($messages as $row): ?>
				<li class="list-group-item">
					<!-- <span class="badge">22</span> -->
					<div class="media media-primary">
						<div class="media-left">
							<a href="<?=messageForm::getUrlAvatar($row['avatar'])?>">
								<img class="media-object" src="<?=messageForm::getUrlAvatar($row['avatar'])?>" width="160" height="120" alt="">
							</a>
						</div>
						<div class="media-body">
							<h4 class="media-heading"><?=$row['name']?> : <?=$row['email']?></h4>
							<?=$row['message']?>
							<br>
							<?php if ($row['isadminedit'] == true): ?>
								<span class="label label-info">изменен администратором</span>
							<?php endif; ?>
						</div>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
	<div class="panel-footer">
		<div id="preview" class="panel panel-primary" style="display: none;">
			<div class="panel-heading">Предварительный просмотр</div>
			<div class="panel-body">
				<li class="list-group-item">
					<!-- <span class="badge">22</span> -->
					<div class="media media-primary">
						<div class="media-left">
							<a href="#">
								<img id="preview_image" class="media-object" src="<?=SITE_URL_ASSETS?>/image/avatar/noavatar.png" width="160" height="120" alt="">
							</a>
						</div>
						<div class="media-body">
							<h4 class="media-heading"></h4>
							<p class="message"></p>
						</div>
					</div>
				</li>
			</div>
		</div>
		<?=$form?>
	</div>
</div>

<script type="text/javascript" src="<?=SITE_URL_ASSETS?>/js/script.js"></script>
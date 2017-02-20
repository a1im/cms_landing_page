<div class="list-group">
	<p class="list-group-item active">
		Список сайтов
	</p>
	<?php foreach ($sitesRead as $rowsite): ?>
		<a href="/editor/site/edit/<?=$rowsite['id_site']?>" class="list-group-item">
			<?=$rowsite['sitename']?>
			<small>(<?=$rowsite['title']?>)</small>
		</a>
		<a href="/editor/main/edit/<?=$rowsite['id_site']?>" class="btn btn-default">Изменить</a>
		<a href="/editor/main/delete/<?=$rowsite['id_site']?>" class="btn btn-default">Удалить</a>
	<?php endforeach; ?>
</div>

<div class="panel panel-primary">
	<div class="panel-heading">Добавить сайт</div>
	<div class="panel-body">
		<?=$formCreateSite?>
	</div>
</div>



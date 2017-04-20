<div class="list-group">
	<p class="list-group-item list-group-item-info"> <?=$result[0]?> </p>
	<?php if (isset($result[1])): ?>
		<?php foreach ($result[1] as $key => $value): ?>
			<?php if($isQuestion): ?>
	  			<a href="<?=$urlAction?>/<?=$key?>1_" class="list-group-item"><?=$value?></a>
	  		<?php else: ?>
	  			<a href="<?=$urlController?>/successful/<?=$value?>" class="list-group-item"><?=$value?></a>
	  		<?php endif; ?>
	  	<?php endforeach; ?>
	<?php endif; ?>
</div>

<a href="<?=$urlAction?>/" class="btn btn-primary">В начало</a>
<br><br>
<?=debug($resFile)?>
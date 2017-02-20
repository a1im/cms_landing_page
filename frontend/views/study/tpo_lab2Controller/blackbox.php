
<?php foreach($res as $value): ?>
	<?php if ($value[0]): ?>
		<div class="alert alert-success" role="alert"><?=$value[1]?></div>
	<?php else: ?>
		<div class="alert alert-danger" role="alert"><?=$value[1]?></div>
	<?php endif; ?>	
<?php endforeach; ?>
<!-- <?=debug($res)?> -->
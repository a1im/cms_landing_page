
<?php if(!empty($dnk)): ?>
	<div class="alert alert-success" role="alert"><?=$dnk?></div>
<?php endif; ?>
<?=$form?>

<script>
	$(document).ready(function() {
		$("button[name='btnCancel']").on('click', function() {
			window.location.href = window.location.href;
		});
	});
</script>
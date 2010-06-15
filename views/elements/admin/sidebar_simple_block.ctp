<?php if (isset($sidebar_for_layout['simple'])) : ?>
<?php foreach ($sidebar_for_layout['simple'] as $simple_block) : ?>
<div class="block">
	<h3><?php echo $simple_block['title']; ?></h3>
	<div class="content">
		<p><?php echo $simple_block['content']; ?></p>
	</div>
</div>
<?php endforeach; ?>
<?php endif; ?>
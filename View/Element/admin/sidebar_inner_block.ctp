<?php if (isset($sidebar_for_layout['inner'])) : ?>
<?php foreach ($sidebar_for_layout['inner'] as $simple_block) : ?>
<div class="block">
	<div class="sidebar-block">
		<h4><?php echo $inner_block['title']; ?></h4>
		<p><?php echo $inner_block['content']; ?></p>
	</div>
</div>
<?php endforeach; ?>
<?php endif; ?>
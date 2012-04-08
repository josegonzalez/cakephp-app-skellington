<?php if (isset($sidebar_for_layout['notice'])) : ?>
<?php foreach ($sidebar_for_layout['notice'] as $simple_block) : ?>
<div class="block notice">
	<h4><?php echo $notice_block['title']; ?></h4>
	<p><?php echo $notice_block['content']; ?></p>
</div>
<?php endforeach; ?>
<?php endif; ?>
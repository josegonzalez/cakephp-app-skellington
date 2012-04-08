<?php if (isset($sidebar_for_layout['navigation'])) : ?>
<?php foreach ($sidebar_for_layout['navigation'] as $key => $sidebar_navigation) : ?>
<div class="block">
	<h3><?php echo $key; ?></h3>
	<ul class="navigation">
<?php foreach ($sidebar_navigation as $link) : ?>
		<li><?php echo $this->Html->link($link['title'], $link['url'], $link['options']); ?></li>
<?php endforeach; ?>
	</ul>
</div>
<?php endforeach; ?>
<?php endif; ?>
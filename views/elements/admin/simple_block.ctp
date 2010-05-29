<?php foreach ($this->viewVars['sidebar_simple_blocks'] as $simple_block) : ?>
	<div class="block">
		<h3><?php echo $simple_block['title']; ?></h3>
		<div class="content">
			<p>
				<?php echo $simple_block['content']; ?>
			</p>
		</div>
	</div>
<?php endforeach; ?>
<?php 
	echo $html->link('Generate ALL', array('action'=>'generate_all')). ' ';
	if ($editable) {
		echo $html->link('Reanalyze ALL', array('action'=>'analyze_all'));
	}
?>
<h2><?php __('Active Tables');?></h2>
<div class="dummyTables view">
<table cellpadding="0" cellspacing="0" style='width: auto'>
	<tr>
		<th><?php __('Name');?></th>
		<th><?php __('Number to generate');?></th>
		<th><?php __('Actions');?></th>
	</tr>
<?php
$i = 1; $inactive = false;
foreach ($data as $one ):
	$table = $one['DummyTable'];
if (!isset($table['active']) ||(isset($table['active']) && $table['active']) ) {
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr <?php echo $class;?>>
		<td>
			<?php echo $html->link($table['name'], array('controller'=>'dummy_fields', 'action'=>'index', $table['id'])); ?>
		</td>
		<td>
			<?php 
				if ($editable) {
					echo $form->create('DummyTable',array(
					'style' => 'width:100%;margin:0;',
					'url' => array('action'=>'number',$table['id'])));
					echo $form->text('DummyTable.number',array(
						'value' => $table['number'],
						'style'=>'width:100%',
						'onchange' => 'submit()'					
					));
					echo $form->end();
				} else {
					echo $table['number'] ;
				} ?>
		</td>		
		<td>
			<?php
				 echo $html->link(__('Generate',true), array('action'=>'generate', $table['id'])); 
			if ($editable) {
				echo ' '.$html->link(__('Deactivate',true), array('action'=>'deactivate', $table['id'])); 
			}
			?>
		</td>
	</tr>
<?php 
} else {
	$inactive = true;
}
endforeach; ?>
</table>
<?php if ($inactive) :?>
<h4>Inactive tables</h4>
<table cellpadding="0" cellspacing="0" style='width: auto'>
	<tr>
		<th><?php __('Name');?></th>
<?php if ($editable) { ?>
		<th><?php __('Actions');?></th>
<?php }?>
	</tr>
<?php
$i = 1;
foreach ($data as $one ):
	$table = $one['DummyTable'];
if (!$table['active']) :
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr <?php echo $class;?>>
		<td>
			<?php echo $html->link($table['name'], array('controller'=>'dummy_fields', 'action'=>'index', $table['name'])); ?>
		</td>
<?php if ($editable) { ?>
		<td>
			<?php 
				echo $html->link(__('Activate',true), array('action'=>'activate', $table['id'])); 
			?>
		</td>
<?php }?>
	</tr>
<?php 
endif;
endforeach; ?>
</table>
<?php endif; ?>
</div>
<?php 
	echo $html->link('Back', array('controller'=>'dummy_tables','action'=>'index','admin'=>true,'plugin'=>'dummy')). ' ';
	if ($editable) {
	    echo $html->link(
	        'Reanalyze table', 
	         array('controller'=>'dummy_tables','action'=>'analyze',$data['DummyTable']['id'])
	    ). ' ';
	} 
	echo $html->link('Empty data', 
         array('controller'=>'dummy_tables','action'=>'truncate',$data['DummyTable']['id']),
         array(),
         __('Are you sure?',true)
    ). ' ';	    

	echo $html->link('Generate', array('controller'=>'dummy_tables','action'=>'generate',$data['DummyTable']['id'],'admin'=>true,'plugin'=>'dummy'));
	
?>
<div class="dummyFields index">
<h2><?php echo $data['DummyTable']['name'];?></h2>
<h4><?php __('Active'); ?></h4>
<table cellpadding="0" cellspacing="0">
	<tr>
		<th><?php __('name');?></th>
		<th><?php __('type');?></th>
		<th><?php __('allow_null');?></th>
		<th><?php __('default');?></th>
		<th><?php __('custom_min');?></th>
		<th><?php __('custom_max');?></th>
		<th><?php __('custom_var');?></th>
<?php if ($editable) {	?>
		<th class="actions"><?php __('Actions');?></th>
<?php } ?>
	</tr>
<?php
$i = 1;
foreach ($data['DummyField'] as $dummyField):
	$class = null;
	if ($dummyField['active'] ) {
	
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr <?php echo $class;?>>
		<td>
			<?php echo $dummyField['name']; ?>
		</td>
		<td style="text-align:left">
			<?php 
			if ($editable && false !== strpos($dummyField['generator'], '->')) {
				$field_type = $dummyField['type'];
				$type_options = $types[$field_type];
				echo $form->create('DummyField', array(
					'style' => 'width:100%;margin:0;',
					'url' => array('action'=>'change',$data['DummyTable']['id'])));
				echo $form->hidden('DummyField.id', array('value' => $dummyField['id']));
				echo $form->select(
					'DummyField.generator',
					$type_options,
					$dummyField['generator'],
					array(
						'style'=>'width:100%',
						'onchange' => 'submit()',
					),
					false
				);
			
				echo $form->end();
			} else {
				echo $dummyField['generator'];
			}
			?>
		</td>
		<td>
			<?php echo ($dummyField['allow_null'])? __('YES',true) : __('No',true); ?>
		</td>
		<td>
			<?php echo $dummyField['default']; ?>
		</td>
		<td>
			<?php echo $dummyField['custom_min']; ?>
		</td>
		<td>
			<?php echo $dummyField['custom_max']; ?>
		</td>
		<td>
			<?php echo $dummyField['custom_variable']; ?>
		</td>
	<?php 
	if ($editable) { ?>
		<td class="actions">
<?php 			echo $html->link(__('Deactivate', true), array('action'=>'deactivate', $dummyField['id'], 'admin' => true));	; 
				echo ' '.$html->link(__('Edit', true), array('action'=>'edit', $dummyField['id']));
?>
		</td>
<?php 		}
?>		
	</tr>
<?php  }
endforeach; ?>
</table>

<h4><?php __('Inactive'); ?></h4>
<table cellpadding="0" cellspacing="0" style="width:auto;">
	<tr>
		<th><?php __('name');?></th>
		<th><?php __('default');?></th>
<?php if ($editable) { ?>
		<th class="actions"><?php __('Actions');?></th>
<?php } ?>		
	</tr>
<?php
$i = 1;
foreach ($data['DummyField'] as $dummyField):
	$class = null;
	if (!$dummyField['active'] ) {
	
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr <?php echo $class;?>>
		<td>
			<?php echo $dummyField['name']; ?>
		</td>
		<td>
			<?php echo $dummyField['default']; ?>
		</td>
<?php if ($editable) { ?>
		<td class="actions">
<?php 		echo $html->link(__('Activate', true), array('action'=>'activate', $dummyField['id'], 'admin' => true));	?>
		</td>
<?php } ?>
	</tr>
<?php  }
endforeach; ?>
</table>
<?php  
if (sizeof($contents)) { ?>
	<br />
	<h4><?php __('Contents'); ?></h4>
	<table cellpadding="0" cellspacing="0" style='width: auto'>
		<thead>
			<tr>
	<?php	
		foreach ($contents[0]['Model'] as $key => $value) {
			echo '<th>' . $key . '</th>';
		} ?>
			<th>&nbsp;</th>
	  	</tr>
		</thead>
		<tbody>
	<?php $i = 1;
		 foreach ($contents as $one) : 
		 $row = $one['Model'];
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}?>
		<tr <?php echo $class?>>
			<?php foreach ($row as $field) : ?>
			<td><?php echo str_replace('<','&lt;',$field); ?></td>
			<?php endforeach; ?>
			<td><?php echo $html->link('View in app', array('plugin'=>null,'admin'=>false,'controller'=>$data['DummyTable']['table'],'action'=>'view',$row['id'])); ?></td>
		</tr>  	
	<?php endforeach; ?>
	  	</tbody>
	</table>	
	<?php 
	echo $form->end();
} else {
	echo '<p>'; 
	__('No contents yet. Generate some.');
	echo '</p>';
} ?>
</div>
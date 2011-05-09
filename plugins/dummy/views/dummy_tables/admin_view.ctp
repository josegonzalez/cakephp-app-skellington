<?php 
	echo $html->link('Back', array('action'=>'index')). ' ';
	echo $html->link('Generate data', array('action'=>'generate',$data['DummyTable']['name'])). ' ';
	echo $html->link(
        'Empty data', 
         array('action'=>'truncate',$data['DummyTable']['name']),
         array(),
         __('Are you sure?',true)
    ). ' ';
	if ($editable) {
		if ($data['DummyTable']['active']) {
			echo $html->link('Deactivate', array('action'=>'deactivate',$data['DummyTable']['name']));
		} else {
			echo $html->link('Activate', array('action'=>'activate',$data['DummyTable']['name']));
		}		
	}

	
?>
<h2><?php echo $data['DummyTable']['name']; ?></h2>
<div class="dummyTables view">
<?php if ($editable) { ?>
<p>Table <strong><?php echo $data['DummyTable']['name']; ?></strong> was created 
<strong><?php echo $time->timeAgoInWords($data['DummyTable']['created']);?></strong>,</p>
<p> is <strong><?php echo ($data['DummyTable']['active'])?__('active',true):__('not active',true);?></strong>
and set to generate <strong><?php echo $data['DummyTable']['number'];?></strong> rows.</p>
<?php } else { ?>
<p>Dummy in realtime modus</p>
<?php } ?>
<br />
<?php  
if (sizeof($data['DummyTable']['contents'])) { ?>
	<br />
	<br />
	<table cellpadding="0" cellspacing="0" style='width: auto'>
		<thead>
			<tr>
	<?php		
		foreach ($data['DummyTable']['contents'][0] as $key => $value) {
			echo '<th>' . $key . '</th>';
		} ?>
	  	</tr>
		</thead>
		<tbody>
	<?php $i = 1;
		 foreach ($data['DummyTable']['contents'] as $row) : 
		$class = null;
		if (++$i % 2 == 0) {
			$class = ' class="altrow"';
		}?>
		<tr>
			<?php foreach ($row as $field) : ?>
			<td><?php echo str_replace('<','&lt;',$field); ?></td>
			<?php endforeach; ?>
		</tr>  	
	<?php endforeach; ?>
	  	</tbody>
	</table>	
	<?php 
	echo $form->end();
} else {
	__('No contents yet. Generate some.');
} ?>
</div>
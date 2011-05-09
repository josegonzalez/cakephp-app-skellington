<div class="form"><fieldset><legend><?php echo __('Edit field',true); ?></legend>
<h3><?php echo $this->data['DummyField']['generator']; ?> 
<span style="font-size:50%;">(<?php echo $this->data['DummyField']['type']; ?>)</span>
</h3>
<br />
<?php
	echo $form->create('DummyField');
	echo $form->inputs(array('fieldset'=>false,
		'id',
		'field_type' => array('type'=>'hidden'),
		'tablename' => array('type'=>'hidden'),
		'custom_variable',
	));
	switch ($this->data['DummyField']['type']) {
		case 'Date':
			echo $form->inputs(array('fieldset'=>false,
				'DummyField.custom_min' => array('type'=>'date','dateFormat'=>'DMY'),
				'DummyField.custom_max' => array('type'=>'date','dateFormat'=>'DMY')
			));
		break;	
		case 'DateTime':
			echo $form->inputs(array('fieldset'=>false,
				'custom_min' => array('type'=>'datetime', 'timeFormat' => 24),
				'custom_max' => array('type'=>'datetime', 'timeFormat' => 24)
			));
		break;	
		case 'Time':
			echo $form->inputs(array('fieldset'=>false,
				'custom_min' => array('type'=>'time', 'timeFormat' => 24),
				'custom_max' => array('type'=>'time', 'timeFormat' => 24)
			));
		break;		
		default:
			echo $form->inputs(array('fieldset'=>false,
				'custom_min',
				'custom_max'
			));
		break;
	}
	echo $form->end('Update');
	
	echo $html->link('Back', array('action'=>'index',$this->data['DummyField']['dummy_table_id']));
?>
</fieldset>
<fieldset>
<p>These custom values have different meanings depending on the generator.</p>
<br />
<h4>min / max</h4>
<p> For numbers it (in most cases) means the minimum and maximum values that the generator make. 
Date and time generators take in string representations of their min and max values. For most
 strings, the max value states the maximum number of characters allowed. In Lorem->sentence max
 is used for the maximum number of words. Look up specific rule for details</p>
<br />
<h4>custom variable</h4>
<p>This value is used differently depending on the generator. Most common uses are date and time
generators (valid values 'past','now','future') and belongsTo (valid values are existing table names).
Float uses it to state it's range (defaults to '%01.2f') and Name->firstname and Name->surname can
take a custom_variable of 'single' to only return one name.
</p>
<br />
<h4>examples : </h4>
<p>For a date type field called "published" you want a value between now and last christmas. You
could use a custom_var of "past" and a custom_min of "2008-12-24".</p>
<br />
<p>For date and time, the min and max values take all strtotime() valid strings, so to get a time
of between +/- 2 hours around generation time, use  min:'-2hours' and max:'+2hours'</p>
<br />
<h4>more examples :</h4>
<p>Check the /app/plugins/tests/cases/vendor/php_faker.test.php</p>
</fieldset>
</div>
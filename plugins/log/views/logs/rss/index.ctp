<?php $this->set('documentData', array('xmlns:dc' => 'http://purl.org/dc/elements/1.1/')); ?>
<?php $this->set('channelData', array(
		'title_for_layout' => __("Log", true),
		'link' => $this->Html->url('/', true),
		'description' => __("Log of most recent events.", true),
		'language' => 'en-us')); ?>
<?php App::import('Sanitize'); ?>
<?php foreach ($logs as $a_log) : ?>
	<?php $logTime = strtotime($a_log['Log']['created']); ?>
	<?php $logLink = array(
			'controller' => Inflector::tableize($a_log['Log']['model']),
			'action' => 'view',
			$a_log['Log']['model_id']); ?>
	<?php $bodyText = preg_replace('=\(.*?\)=is', '', $a_log['Log']['description']); ?>
	<?php $bodyText = $this->Text->stripLinks($bodyText); ?>
	<?php $bodyText = $this->Text->truncate(Sanitize::stripAll($bodyText), 400, array('html' => true)); ?>
	<?php echo  $this->Rss->item(array(), array(
		'title' => $a_log['Log']['description'],
		'link' => $logLink,
		'guid' => array('url' => $logLink, 'isPermaLink' => 'true'),
		'description' =>  $bodyText,
		'dc:creator' => $a_log['User']['name'],
		'pubDate' => $a_log['Log']['created'])); ?>
<?php echo "\n"?>
<?php endforeach; ?>
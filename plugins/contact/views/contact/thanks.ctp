<?php echo $this->element('contact', array('plugin' => null)); ?>
<?php
$this->addScript($html->css('/contacts/css/contacts.css'));
if (Configure::read('debug') > 0):
    debug($session->read('Message.email'));
endif;
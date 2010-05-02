<?php echo $this->element('contact', array('plugin' => null)); ?>
<?php $this->addScript($html->css('/contact/css/contact.css')) ?>
<h2><?php echo $this->pageTitle = __d('contact', 'Contact', true) ?></h2>
<?php
echo $form->create('Contact');
echo $form->input('name', array(
    'label' => 'Name',
    'error' => array(
        'notEmpty' => __d('contact', 'Please specify your name', true))));
echo $form->input('address', array('label' => 'Adress'));
echo $form->input('zip', array('label' => ' Zip Code'));
echo $form->input('city', array('label' => 'City'));
echo $form->input('country', array('label' => 'State'));
echo $form->input('phone', array('label' => 'Telephone'));
echo $form->input('email', array(
    'label' => 'Email',
    'error' => array(
        'email' => __d('contact', 'Please specify your email', true))));
echo $form->input('message', array(
    'label' => 'Message',
    'error' => array(
        'notEmpty' => __d('contact', 'Please specify your message', true))));
echo $form->submit(__d('contact', 'Submit', true));
?>
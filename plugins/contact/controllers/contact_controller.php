<?php
class ContactController extends ContactAppController {
	var $name = 'Contact';
	var $components = array('Email');

	function beforeFilter() {
		parent::beforeFilter();
		if (isset($this->Auth))
		{
			$this->Auth->allowedActions = array('*');
		}
	}

	/**
	 * You can create a view in app/views/plugins/contacts/contacts/add.ctp
	 * if you need to customize the contact form
	 */
	function add() {
		if ($this->RequestHandler->isGet()) {
			return;
		}

		$this->Contact->set($this->data);
		if (!$this->Contact->validates()) {
			return $this->Session->setFlash(
				__d('contacts', "Please fill-in all required fields", true),
				'message_notice');
		}

		if (!$this->Contact->save($this->data, false)) {
			return $this->Session->setFlash(
				__d('contacts', "An error occured while saving", true),
				'message_error');
		}

		$this->Email->reset();
		if (Configure::read('debug') > 0) {
			$this->Email->delivery = 'debug';
		}
		$this->Email->to = Configure::read('Contact.email');
		$this->Email->from = $this->data['Contact']['email'];
		$this->Email->replyTo = $this->data['Contact']['email'];
		$this->Email->subject = __d('contacts', 'New Contact', true);
		$this->Email->template = 'add';
		$this->Email->sendAs = 'text';
		$this->set('contact', $this->data);
		$this->Email->send();

		$this->Session->setFlash(
			__d('contacts', 'Your message was sent successfully.', true),
			'message_success');

		$this->redirect(array('action' => 'thanks'));
	}

	/**
	 * Create a app/views/plugins/contacts/contacts/thanks.ctp
	 * to customize your thanks page
	 */
	function thanks() {
	}
}
?>
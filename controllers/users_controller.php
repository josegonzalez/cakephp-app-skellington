<?php
class UsersController extends AppController {
	var $name = 'Users';
	var $layout = 'alternate';

	function dashboard() {
		$user = $this->User->find('dashboard');
		$this->set(compact('user'));
	}

	function login() {
		if (empty($this->data)) {
			return;
		}

		$user = Authsome::login($this->data['User']);

		if (!$user) {
			$this->Session->setFlash(__('Unknown user or Wrong Password', true));
			return;
		}

		$remember = (!empty($this->data['User']['remember']));
		if ($remember) {
			Authsome::persist('2 weeks');
		}

		if ($user) {
			$this->Session->setFlash(__('You have been logged in', true));
			$this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
		}
	}

	function logout() {
		$this->Authsome->logout();
		$this->Session->delete('User');
		$this->redirect(array('action' => 'login'));
	}

	function forgot_password() {
		if (!empty($this->data) && isset($this->data['User']['email'])) {
			if ($this->data['User']['email'] == '') {
				$this->Session->setFlash(__('Invalid email address', true));
				$this->redirect(array('controller' => 'users', 'action' => 'forgot_password'));
			}

			$user = $this->User->find('forgot_password', $this->data['User']['email']);
			if (!$user) {
				$this->Session->setFlash(__('No user found for this email address', true));
				$this->redirect(array('controller' => 'users', 'action' => 'forgot_password'));
			}

			$activationKey = $this->User->changeActivationKey($user['User']['id']);
			try {
				if ($this->Mail->send(array(
					'to' => $user['User']['email'],
					'mailer' => 'swift',
					'subject' => '[Site] ' . __('Reset Password', true),
					'variables' => compact('maintainer', 'activationKey')))) {
						$this->Session->setFlash(
							__('An email has been sent with instructions for resetting your password', true));
						$this->redirect(array('controller' => 'users', 'action' => 'login'));
				} else {
					$this->Session->setFlash(__('An error occurred', true));
					$this->log("Error sending email");
				} else {
					$this->Session->setFlash(
						__('An email has been sent with instructions for resetting your password', true));
					$this->redirect(array('controller' => 'users', 'action' => 'login'));
				}
			} catch(Exception $e) {
				$this->Session->setFlash(__('An error occurred', true));
				$this->log("Failed to send email: " . $e->getMessage());
			}
		}
	}

	function reset_password($username = null, $key = null) {
		if ($username == null || $key == null) {
			$this->Session->setFlash(__('An error occurred', true));
			$this->redirect(array('action' => 'login'));
		}

		$user = $this->User->find('reset_password', array('username' => $username, 'key' => $key));
		if (!isset($user)) {
			$this->Session->setFlash(__('An error occurred', true));
			$this->redirect(array('controller' => 'users', 'action' => 'login'));
		}

		if (!empty($this->data) && isset($this->data['User']['password'])) {
			if ($this->User->save($data, array('fields' => array('id', 'password', 'activation_key'), 'callback' => 'reset_password', 'user_id' => $user['User']['id']))) {
				$this->Session->setFlash(__('Your password has been reset successfully', true));
				$this->redirect(array('controller' => 'users', 'action' => 'login'));
			} else {
				$this->Session->setFlash(__('An error occurred please try again', true));
			}
		}

		$this->set(compact('user', 'username', 'key'));
	}

	function change_password() {
		if (!empty($this->data)) {
			if ($this->User->save($this->data, array('fieldList' => array('id', 'password'), 'callback' => 'change_password'))) {
				$this->Session->setFlash(__('Your password has been successfully changed', true));
				$this->redirect(array('action' => 'dashboard'));
			} else {
				$this->Session->setFlash(__('Your password could not be changed', true));
			}
		}
	}
}
?>
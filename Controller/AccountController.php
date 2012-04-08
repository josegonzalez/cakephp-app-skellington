<?php
class AccountController extends AppController {

    public $uses = array('User');
    
    public function settings() {
        if (!empty($this->data)) {
            if ($this->User->updateAccount($this->data)) {
                $this->Session->setFlash(__('Your account has been updated', true));
                $this->redirect(array('action' => 'account'));
            } else {
                $this->Session->setFlash(__('You account could not be updated. Please fix any errors and try again', true));
            }
        } else {
            $this->data = $this->User->find('account');
        }
    }

    public function password() {
        if (!empty($this->data)) {
            if ($this->User->updatePassword($this->data)) {
                $this->Session->setFlash(__('Your password has been updated', true));
                $this->redirect(array('action' => 'password'));
            } else {
                $this->Session->setFlash(__('You password could not be updated. Please fix any errors and try again', true));
            }
        }
    }

    public function delete() {
        if (!empty($this->data['User']['delete'])) {
            if ($this->User->deleteAccount()) {
                $this->Session->setFlash(__('Your account has been deleted', true));
                $this->_logout('/');
            } else {
                $this->Session->setFlash(__('Your account could not be deleted', true));
            }
        }
    }
}
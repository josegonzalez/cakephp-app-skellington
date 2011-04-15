<?php
class UsersController extends AppController {

    public function login() {
        if (empty($this->data)) {
            return;
        }

        $user = $this->Authsome->login('credentials', $this->data['User']);
        if (!$user) {
            $this->Session->setFlash(__('Unknown user or incorrect Password', true));
            return;
        }

        if (!empty($this->data['User']['remember'])) $this->Authsome->persist();

        $this->Session->setFlash(__('You have been logged in', true));
        $this->redirect(array('action' => 'index'));
    }

    public function logout() {
        $this->_logout();
    }

    public function register() {
        if (!empty($this->data)) {
            if ($this->User->register($this->data)) {
                $this->Session->setFlash(__('You\'ve been successfully registered!', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('You could not be registered. Please fix any errors and try again', true));
            }

            if (isset($this->data['User']['password'])) {
                unset($this->data['User']['password']);
            }
        }
    }

    public function index() {
        
    }

    public function admin_index() {
        $this->paginate = array('forapproval');
        $this->set('users', $this->paginate());
    }

    public function admin_approve($id = null) {
        $message = sprintf(__('Unable to approve user #id %s', true), $id);
        if ($this->User->approveAccount($id)) {
            $message = sprintf(__('Approved user #id %s', true), $id);
        }

        $this->Session->setFlash($message);
        $this->redirect(array('action' => 'index', 'admin' => true));
    }

}
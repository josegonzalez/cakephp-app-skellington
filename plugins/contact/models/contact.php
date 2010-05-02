<?php

class Contact extends ContactAppModel
{
    var $validate = array(
        'name' => array(
            'rule' => 'notEmpty',
            'required' => true,
            'allowEmpty' => false,
            'message' => 'notEmpty'),
        'email' => array(
            'rule' => 'email',
            'required' => true,
            'allowEmpty' => false,
            'message' => 'email'),
        'message' => array(
            'rule' => 'notEmpty',
            'required' => true,
            'allowEmpty' => false,
            'message' => 'notEmpty'));
}
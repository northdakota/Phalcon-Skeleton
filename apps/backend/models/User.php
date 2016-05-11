<?php
namespace Multiple\Backend\Models;

use Phalcon\Mvc\Model;
use Phalcon\Db\RawValue;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;

class User extends Model
{
    public $id;
    public $email;
    public $password;
    public $status;
    public $date_registration;
    public $admin_comment;
    public $firstname;
    public $lastname;
    public $patronymic;
    public $phone;

    public function initialize()
    {
        $this->setSource('user');
        $this->allowEmptyStringValues(['admin_comment', 'firstname', 'lastname', 'patronymic', 'phone']);
    }

    public function validation()
    {
        #From 2.1.x onwards Phalcon\Mvc\Model\Validation is deprecated,
        /*$validation = new Validation();
        $validation->add('email', new Uniqueness(array(
            'message' => 'The e-mail :field already exist',
            'model' => $this)));
        return $this->validate($validation);*/
        /*$this->validate(new Uniqueness(array(
            'field' => 'email',
            'message' => 'The e-mail :field already exist')));*/


        $this->validate(new \Phalcon\Mvc\Model\Validator\PresenceOf(array(
            'field'   => 'email',
            'message' => 'The email not specified'
        )));
        $this->validate(new \Phalcon\Mvc\Model\Validator\Uniqueness(array(
            'field'   => 'email',
            'message' => 'The e-mail :field already exist'
        )));

        return ($this->validationHasFailed() != true);
    }

    public function getSource()
    {
        return 'user';
    }
}
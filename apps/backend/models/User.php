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
        $validation = new Validation();
        $validation->add('email', new Uniqueness(array(
            'message' => 'The e-mail already exist',
            'model' => $this)));
        return $this->validate($validation);
    }

    public function getSource()
    {
        return 'user';
    }
}
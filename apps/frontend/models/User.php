<?php

namespace Multiple\Frontend\Models;
use Phalcon\Mvc\Model;
use Multiple\Frontend\Models\UserCompany;
use Multiple\Frontend\Models\UserAgent;

class User extends Model
{
    public $id;
    public $first_name;
    public $last_name;
    public $email;
    public $password;
    public $user_type;
    public $activity;
    public $sessionId;

    public function initialize()
    {
        $this->setSource("user");
    }

    public function beforeCreate()
    {
        if (!$this->sessionId) { // use default value if the value is not set
            $this->sessionId = new RawValue('default');
        }
    }

    public function getSource()
    {
        return "user";
    }
    public function findUser($user_id){
        $user = User::findFirstById($user_id);
        if(!$user)
            return false;

        $ua = UserAgent::findFirstByUser_id($user_id);

        if($ua){
            $user->user_type = array(
                'type' => 'user_agent',
                'object' => $ua
            );
            return $user;
        }
        $uc = UserCompany::findFirstByUser_id($user_id);

        if($uc){
            $user->user_type = array(
                'type' => 'user_vendor',
                'object' => $uc
            );
            return $user;
        }
        return false;
    }

    public function generatePassword() {
        $charsL = 'abcdefghijklmnopqrstuvwxyz';
        $charsU = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $specialChars = '!#$&*()_-,.?@[]+-=~^%?:;';
        $password = substr(str_shuffle($charsL), 0, 3).
            substr(str_shuffle($charsU), 0, 3).
            substr(str_shuffle($specialChars), 0, 1).
            substr(str_shuffle($numbers), 0, 1);
        return str_shuffle($password);
    }
}
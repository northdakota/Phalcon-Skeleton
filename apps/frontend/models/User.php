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

    public function initialize()
    {
        $this->setSource("user");
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


}
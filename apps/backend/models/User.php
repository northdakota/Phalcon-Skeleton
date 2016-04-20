<?php

namespace Multiple\Backend\Models;
use Phalcon\Mvc\Model;

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
    public function filterUser($data){

        $sql = "SELECT u.*, c.name FROM user as u
              LEFT JOIN  user_company uc ON(u.id = uc.user_id)
              LEFT JOIN company c ON(uc.company_id = c.id) ";

        if($data){
            $where = false;

            if(isset($data['name_email'])){
                $where .= "WHERE (u.email LIKE '%".$data['name_email']."%'
                 OR u.first_name LIKE '%".$data['name_email']."%'
                 OR u.last_name LIKE '%".$data['name_email']."%' )";
            }
            if(isset($data['user_type']) && $data['user_type'] !='all'){
                if(!$where)
                    $where .= ' WHERE ';
                else
                    $where .= ' AND ';
                $where .= " u.user_type ='".$data['user_type']."'";
            }
            if(isset($data['company']) && $data['company'] !='all'){
                if(!$where)
                    $where .= ' WHERE ';
                else
                    $where .= ' AND ';
                $where .= " c.id ='".$data['company']."'";
            }
            $sql.= $where;
        }

        $db = $this->getDi()->getShared('db');
        $result = $db->query($sql);
        return $result->fetchAll();

    }
    public function getUserWithoutCompany(){
        $sql = "SELECT u.id, u.email FROM user as u
           LEFT JOIN user_company uc ON(u.id = uc.user_id)
            WHERE u.user_type='user' AND uc.user_id IS NULL";
        $db = $this->getDi()->getShared('db');
        $result = $db->query($sql);
        return $result->fetchAll();

    }
    public function saveUser($data){
        $this->first_name = $data['first_name'];
        $this->last_name = $data['last_name'];
        $this->email = $data['email'];
        $this->password = sha1(time().$data['email']);
        $this->user_type = $data['user_type'];
        if(isset($data['activity']))
           $this->activity = 0;
        else
            $this->activity = 1;
       return $this->save();

    }
    public function getAccountManagers(){

        return  User::find("user_type = 'account_manager'");

    }

}
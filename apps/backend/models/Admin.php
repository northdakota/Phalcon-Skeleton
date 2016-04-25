<?php
namespace Multiple\Backend\Models;
use Phalcon\Mvc\Model;
use Phalcon\Db\RawValue;
use  Multiple\Library\PHPImageWorkshop\ImageWorkshop;

class Admin extends Model
{
    public $id;
    public $email;
    public $password;
    public $surname;
    public $name;
    public $patronymic;
    public $avatar;

    public function initialize()
    {
        $this->setSource("admin");
        $this->skipAttributesOnCreate(array('avatar','surname','name','patronymic'));
        $this->skipAttributesOnUpdate(array('avatar','surname','name','patronymic'));
    }
    public function getSource()
    {
        return "admin";
    }
    public function getId()
    {
        return $this->id;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function getFullName(){
        return $this->surname.' '.$this->name.' '.$this->patronymic;
    }
    public function getSurnameAndInitials(){
        return $this->surname.' '.substr($this->name,0,1).'.'.substr($this->patronymic,0,1).'.';
    }
    public function saveAvatar($avatar){


        $layer = ImageWorkshop::initFromPath($avatar['tmp_name']);
        var_dump($layer); exit;
    }

    public function beforeValidationOnCreate() {
        /*$metaData = $this->getModelsMetaData();
        $attributes = $metaData->getNotNullAttributes($this);
        // Set all not null fields to their default value.
        foreach($attributes as $field) {
            if(!isset($this->{$field}) || is_null($this->{$field})) {
                $this->{$field} = new RawValue('default');
            }
        }*/
    }
}
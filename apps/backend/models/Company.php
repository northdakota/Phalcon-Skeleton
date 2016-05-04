<?php
namespace Multiple\Backend\Models;

use Phalcon\Mvc\Model;

class Company extends Model
{
    public $id;
    public $name;



    public function initialize()
    {
        $this->setSource("company");


        $this->hasManyToMany("id", "Multiple\Backend\Models\AgentCompany", "company_id", "agent_id", "Multiple\Backend\Models\Agent", "id", array(
            'alias' => 'agent'));

    }

    public function getSource()
    {
        return "company";
    }


    public function getAgent($parameters = null)
    {
        return $this->getRelated('Agent', $parameters);
    }    
}
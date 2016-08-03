<?php

namespace Multiple\Backend\Models;

use Phalcon\Mvc\Model;
class Agent extends Model
{
    public $id;
    public $name;

    public function initialize()
    {
        $this->setSource("agent");
        $this->hasManyToMany("id", "Multiple\Backend\Models\AgentCompany","agent_id","company_id" ,"Multiple\Backend\Models\Company" , "id",array(
            'alias' => 'company'));
    }

    public function getSource()
    {
        return "agent";
    }
}
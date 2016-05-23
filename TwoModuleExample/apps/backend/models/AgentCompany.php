<?php

namespace Multiple\Backend\Models;
use Phalcon\Mvc\Model;

class AgentCompany extends Model
{
    public $id;
    public $company_id;
    public $agent_id;


    public function initialize()
    {
        $this->setSource("agent_company");
        $this->belongsTo("company_id", "Multiple\Backend\Models\Company", "id");
        $this->belongsTo("agent_id", "Multiple\Backend\Models\Agent", "id");
    }
    public function getSource()
    {
        return "agent_company";
    }

}
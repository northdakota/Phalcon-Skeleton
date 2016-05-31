<?php

namespace Multiple\Models;

use Phalcon\Mvc\Model;

class Mode2 extends Model
{
    public $id;
    public $name;

    public function initialize()
    {
        $this->setSource("Model2");
    }

    public function getSource()
    {
        return "Model2";
    }
}
<?php

namespace Multiple\Models;

use Phalcon\Mvc\Model;

class Model1 extends Model
{
    public $id;
    public $name;

    public function initialize()
    {
        $this->setSource("Model");
    }

    public function getSource()
    {
        return "Model";
    }
}
<?php
namespace Multiple\Library;

class Method {

    public static function get($type){
        return new $type();
    }
}



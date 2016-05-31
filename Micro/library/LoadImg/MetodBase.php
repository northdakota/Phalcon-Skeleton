<?php
use Multiple\Library;
abstract class MetodBase {
    public
        $_id = null,
        $_errors = array(),
        $_errorCode = 0;



    public function action($request){
        $post = $request->getPost();
        if(!empty($this->_id)) {
            return true;
        } else {
            $this->_errors[] = 'Invalid id format';
            $this->_errorCode = 1;
        }
        return false;
    }

    public function getErrors(){
        return $this->_errors;
    }
    public function getErrorCode()
    {
        return $this->_errorCode;
    }
}




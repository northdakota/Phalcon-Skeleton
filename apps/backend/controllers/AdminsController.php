<?php
namespace Multiple\Backend\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Multiple\Library\PaginatorBuilder as PaginatorBuilder;
use Multiple\Backend\Models\Admin;
use Multiple\Backend\Form\AdminForm;

class AdminsController extends ControllerBase
{

    public function indexAction()
    {

        $this->persistent->searchParams = null;
        $this->view->form               = new AdminForm;

    }  

}
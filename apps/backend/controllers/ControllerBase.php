<?php
namespace Multiple\Backend\Controllers;
use Multiple\Backend\Models\User;
use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{

    public $admin;

    protected function initialize()
    {

        $auth = $this->session->get('auth');

        if (!$auth) {
            $admin_id = false;
        } else {
            $admin_id = $auth['id'];
        }
        if ($admin_id) {
            $this->admin = User::findFirst(
                array(
                    "id = :id:",
                    'bind' => array(
                        'id' => $admin_id,
                    )
                )
            );

        }else{
            header( 'Location: http://'.$_SERVER['HTTP_HOST'].'/admin/login/index' );
            exit;
        }
       // $this->tag->prependTitle('INVO | ');
       // $this->view->setTemplateAfter('main');

    }

    protected function forward($uri)
    {
        $uriParts = explode('/', $uri);
        $params = array_slice($uriParts, 2);
        return $this->dispatcher->forward(
            array(
                'controller' => $uriParts[0],
                'action' => $uriParts[1],
                'params' => $params
            )
        );
    }

    protected function redirect($uri)
    {
        header( 'Location: http://'.$_SERVER['HTTP_HOST'].$uri);
        exit;
    }
}
<?php
namespace Multiple\Frontend\Controllers;

use Phalcon\Mvc\Controller;
use Multiple\Frontend\Models\User;

class ControllerBase extends Controller
{
    protected $user;

    protected function initialize()
    {
        $permission = array(
            'user_vendor' => array(
                'conroller1',
                'conroller2',
                'conroller3'
            ),
            'user_agent' => array(
                'conroller4',
                'conroller5'
            )
        );
        $auth = $this->session->get('auth');

        if (!$auth) {
            $user_id = false;
        } else {
            $user_id = $auth['id'];
        }

        if ($user_id) {
            $u = new User();
            $this->user = $u->findUser($user_id);

            if (!$this->user)
                $this->redirect('/login/index');
            $dispatcher = $this->getDi()->getShared('dispatcher');
            $controller = strtolower($dispatcher->getControllerName());
            $allowed = false;

            foreach($permission[$this->user->user_type['type']] as $perm){
               if($perm == $controller)
                   $allowed = true;
            }
            $this->view->t = Di::getDefault()->get("translate", [$this->user->language]);
            if(!$allowed)
                $this->redirect('/login/index');
        } else {
            $this->redirect('/login/index');
        }

    }

    protected function redirect($url)
    {
        header('Location: http://' . $_SERVER['HTTP_HOST'] . $url);
        exit;
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
    
}
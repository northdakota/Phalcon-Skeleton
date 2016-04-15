<?php

namespace Multiple\Frontend\Controllers;

use Phalcon\Mvc\Controller;
use Multiple\Frontend\Models\User;


class LoginController extends Controller
{
    private function _registerSession($user)
    {
        $this->session->set(
            'auth',
            array(
                'id' => $user->id,
                'email' => $user->email,
            )
        );
    }

    public function logoutAction()
    {
        $auth = $this->session->get('auth');

        if (!$auth) {
            $user_id = false;
        } else {
            $user_id = $auth['id'];
        }
        if ($user_id) {
            $admin = User::findFirst(
                array(
                    "id = :id:",
                    'bind' => array(
                        'id' => $user_id,
                    )
                )
            );

        }
        if ($auth)
            $this->session->destroy();
        return $this->dispatcher->forward(
            array(
                'controller' => 'login',
                'action' => 'index'
            )
        );

    }

    public function startAction()
    {

        if ($this->request->isPost()) {


            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');


            $user = User::findFirst(
                array(
                    "email = :email:  AND password = :password: AND activity=1 ",
                    'bind' => array(
                        'email' => $email,
                        'password' => sha1($password)
                    )
                )
            );

            if ($user) {
                $this->_registerSession($user);

                $u = new User();
                $user = $u->findUser($user->id);


                if($user->user_type['type'] == 'user_agent'){
                    header( 'Location: http://'.$_SERVER['HTTP_HOST'].'/announcement/index' );
                    exit;
                }
                if($user->user_type['type'] == 'user_vendor'){
                    header( 'Location: http://'.$_SERVER['HTTP_HOST'].'/myfleet/index' );
                    exit;
                }
                header( 'Location: http://'.$_SERVER['HTTP_HOST'].'/login/index' );
                exit;

            }
        }
        return $this->dispatcher->forward(
            array(
                'controller' => 'login',
                'action' => 'index'
            )
        );
    }

    public function indexAction()
    {
       $this->view->loginpage = true;
    }
}

<?php

namespace Multiple\Backend\Controllers;

use Phalcon\Mvc\Controller;
use Multiple\Backend\Models\User;


class LoginController extends Controller
{
    private function _registerSession($admin)
    {
        $this->session->set(
            'auth',
            array(
                'id' => $admin->id,
                'email' => $admin->email,
                'user_type' => $admin->user_type
            )
        );
    }

    public function logoutAction()
    {
        $auth = $this->session->get('auth');

        if (!$auth) {
            $admin_id = false;
        } else {
            $admin_id = $auth['id'];
        }
        if ($admin_id) {
            $admin = User::findFirst(
                array(
                    "id = :id:",
                    'bind' => array(
                        'id' => $admin_id,
                    )
                )
            );

            //Log::addAdminLog(Log::$typeAdminAuth, Log::$textAdminLogout, $admin);
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


            $admin = User::findFirst(
                array(
                    "email = :email:  AND password = :password: AND activity=1 AND (user_type='admin' OR user_type='account_manager')",
                    'bind' => array(
                        'email' => $email,
                        'password' => sha1($password)
                    )
                )
            );

            if ($admin != false) {

                $this->_registerSession($admin);

                header( 'Location: http://'.$_SERVER['HTTP_HOST'].'/admin/dashboard/index' );
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

<?php


namespace Multiple\Frontend\Controllers;

use Phalcon\Mvc\Controller;
use Multiple\Frontend\Form\UserForm as UserForm;
use Multiple\Frontend\Models\User;

class ProfileController extends ControllerBase
{

    public function indexAction()
    {
        $user = User::findFirstById($this->user->id);
        $form = new UserForm($user, null);
        $this->view->form = $form;
    }
    public function saveAction()
    {   $user = User::findFirstById($this->user->id);
        if($user){
            if ($this->request->isPost()) {
                $form = new UserForm(null);
                $this->view->form = $form;
                $data = $this->request->getPost();
                if (!$form->isValid($data, $user)) {
                    foreach ($form->getMessages() as $message) {
                        $this->flashSession->error($message);
                    }
                    return $this->dispatcher->forward(array(
                        'module' => 'frontend',
                        'controller' => 'profile',
                        'action' => 'index'
                    ));
                }
                if(!empty($data['newpassword']) || !empty($data['passwordconfim'])) {
                    if ($data['newpassword'] == $data['passwordconfim']){
                        $user->password = sha1($data['newpassword']);
                    }
                    else {
                        $this->flashSession->error('Passwords are not the same');
                        return $this->dispatcher->forward(array(
                            'module' => 'frontend',
                            'controller' => 'profile',
                            'action' => 'index'
                        ));
                    }

                }
                if ($user->save() == false) {
                    foreach ($user->getMessages() as $message) {
                        $this->flashSession->error($message);
                    }
                }
                $form->clear();
            }
        }
        return $this->dispatcher->forward(array(
            'module' => 'frontend',
            'controller' => 'profile',
            'action' => 'index'
        ));
    }
}
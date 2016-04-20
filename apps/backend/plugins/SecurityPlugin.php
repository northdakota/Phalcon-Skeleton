<?php
namespace Multiple\Backend\Plugins;


use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl\Adapter\Memory as AclList;


class SecurityPlugin extends Plugin
{
    /**
     * Returns an existing or new access control list
     *
     * @returns AclList
     */
    public function getAcl()
    {
        if (!isset($this->persistent->acl)) {

            $acl = new AclList();

            $acl->setDefaultAction(Acl::DENY);

            //Register roles
            $roles = array(
                'admin' => new Role('admin'),
                'guests' => new Role('Guests')
            );
            foreach ($roles as $role) {
                $acl->addRole($role);
            }

            $adminResources = array(
                'dashboard' => array('index'),
                'admins' =>array( 'index','search','save','add','create','edit','del')
            );
            foreach ($adminResources as $resource => $actions) {
                $acl->addResource(new Resource($resource), $actions);
            }

            //Public area resources
            $publicResources = array(
                'login' => array('index', 'start'),
            );
            foreach ($publicResources as $resource => $actions) {
                $acl->addResource(new Resource($resource), $actions);
            }

            //Grant access to public areas to both users and guests
            foreach ($roles as $role) {
                foreach ($publicResources as $resource => $actions) {
                    foreach ($actions as $action) {
                        $acl->allow($role->getName(), $resource, $action);
                    }
                }
            }

            foreach ($adminResources as $resource => $actions) {
                foreach ($actions as $action) {
                    $acl->allow('admin', $resource, $action);
                }
            }

            //The acl is stored in session, APC would be useful here too
            $this->persistent->acl = $acl;
        }

        return $this->persistent->acl;
    }


    /**
     * This action is executed before execute any action in the application
     *
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @return bool
     */
    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {
        $auth = $this->session->get('auth');
        if (!$auth) {
            $role = 'Guests';
        } else {
            $role = $auth['role'];
        }
        $controller = $dispatcher->getControllerName();
        $action = $dispatcher->getActionName();
        $acl = $this->getAcl();
        $allowed = $acl->isAllowed($role, $controller, $action);
      //var_dump($allowed); var_dump($role); var_dump($controller); var_dump($action); exit;
        if (!$allowed) {
            $dispatcher->forward(array(
                'controller' => 'login',
                'action' => 'index'
            ));
            if ($auth)
               // $this->session->destroy();
            return false;
        }
    }
}
<?php

namespace Multiple\Frontend;

use Phalcon\Loader;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Db\Adapter\Pdo\Mysql as Database;
use Phalcon\Config\Adapter\Ini as ConfigIni;
use Phalcon\Mvc\View;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Flash\Direct as FlashDirect;
class Module
{

	public function registerAutoloaders()
	{

		$loader = new Loader();

		$loader->registerNamespaces(array(
			'Multiple\Frontend\Controllers' => '../apps/frontend/controllers/',
			'Multiple\Frontend\Models' => '../apps/frontend/models/',
			'Multiple\Library'     => '../apps/library/',
			'Multiple\Frontend\Form'        => '../apps/frontend/form/',
		));

		$loader->register();
	}

	/**
	 * Register the services here to make them general or register in the ModuleDefinition to make them module-specific
	 */
	public function registerServices($di)
	{
		$di->set('translate', function($language) {
			if(in_array($language, ['en', 'sw', 'enmail', 'swmail']))
				$dictionary =  new \Phalcon\Config\Adapter\Php("dictionary/{$language}.php");
			else
				$dictionary =  new \Phalcon\Config\Adapter\Php("dictionary/en.php");
			return new \Phalcon\Translate\Adapter\NativeArray(array("content" => $dictionary->toArray()));
		});
		//Registering a dispatcher
		$di->set('dispatcher', function () {
			$dispatcher = new Dispatcher();

			//Attach a event listener to the dispatcher
			$eventManager = new \Phalcon\Events\Manager();
			//$eventManager->attach('dispatch', new \Acl('frontend'));

			$dispatcher->setEventsManager($eventManager);
			$dispatcher->setDefaultNamespace("Multiple\Frontend\Controllers\\");
			return $dispatcher;
		});

		$di->set('config',function(){
			$config = new ConfigIni("config/config.ini");
			return $config->api->toArray();
		});

		$di->set('mail',function(){
			$config = new ConfigIni("config/config.ini");
			return $config->mail->toArray();
		});

		//Registering the view component
		$di->set('view', function() {
			$view = new View();
			$view->setViewsDir('../apps/frontend/views/');
			$view->registerEngines(
				array(
					".phtml" => 'Phalcon\Mvc\View\Engine\Volt'
				)
			);
			return $view;
		});

		$di->set('db', function() {
			$config = new ConfigIni("config/config.ini");
			return new Database($config->database->toArray());
		});
		$di->set('session', function () {
			$session = new SessionAdapter();
			$session->start();
			return $session;
		});
		$di->set('flash', function () {
			return new FlashDirect();
		});
	}
}

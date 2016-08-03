<?php

error_reporting(E_ALL);

use Phalcon\Loader;
use Phalcon\Mvc\Router;
use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\Application as BaseApplication;


class Application extends BaseApplication
{

	/**
	 * Register the services here to make them general or register in the ModuleDefinition to make them module-specific
	 */
	protected function registerServices()
	{
		$config =  new \Phalcon\Config\Adapter\Php("../config/config.php");
		if ($config->appmode == 'development') {
			$debug = new \Phalcon\Debug();
			$debug->listen();
		}

		$di = new FactoryDefault();

		$loader = new Loader();

		/**
		 * We're a registering a set of directories taken from the configuration file
		 */
		$loader->registerDirs(
			array(
                __DIR__ . '/../apps/library/',
                __DIR__ . '/../apps/plugins/'
			)
		)->register();



		//Registering a router
		$di->set('router', function(){

			$router = new Router();

			$router->setDefaultModule("frontend");

			$router->add("/admin", array(
				'module' => 'backend',
				'controller' => 'login',
				'action' => 'index',
			));
			$router->add("/admin/:controller/:action/:params", array(
				'module' => 'backend',
				'controller' => 1,
				'action' => 2,
				'params'=>3
			));
			$router->add("/:controller/:action/:params", array(
				'module' => 'frontend',
				'controller' => 1,
				'action' => 2,
				'params'=>3
			));

			return $router;

		});

		$this->setDI($di);
	}

	public function main()
	{

		$this->registerServices();

		//Register the installed modules
		$this->registerModules(array(
			'frontend' => array(
				'className' => 'Multiple\Frontend\Module',
				'path' => '../apps/frontend/Module.php'
			),
			'backend' => array(
				'className' => 'Multiple\Backend\Module',
				'path' => '../apps/backend/Module.php'
			)
		));
		echo $this->handle()->getContent();
	}

}

$application = new Application();
$application->main();

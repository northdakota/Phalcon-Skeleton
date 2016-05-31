<?php

error_reporting(E_ALL);
ini_set('display_errors', true);
use Phalcon\Loader;
//use Phalcon\Mvc\View; //todo: need this only for test. Remove or comment this if you do not need test any more
use Phalcon\Mvc\Micro;
use Phalcon\DI\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as Database;
use Multiple\Library\Method;
use Phalcon\Config\Adapter\Ini as ConfigIni;
try {
	header('Access-Control-Allow-Origin: *');
	$di = new FactoryDefault();

	$di->set('url', function() {
		$url = new \Phalcon\Mvc\Url();
		$url->setBaseUri('/');
		return $url;
	});

	$di->set('config',function(){
		$config = new ConfigIni("../config/config.ini");
		return $config->api->toArray();
	});

	$di->set('db', function() {
		$config = new ConfigIni("../config/config.ini");
		return new Database($config->database->toArray());
	});

	/**
	 * Registering an autoloader
	 */
	$loader = new Loader();
	$loader->registerNamespaces(array(
		'Multiple\Library'     => '../library/',
		'Multiple\Models' => '../models/',
	))->registerDirs(
		array(
			'../library/',
			'../library/LoadImg/',
			'../models/',
		)
	)->register();

	$app = new Micro();
	$app->get('/testform', function () use ($app) { 
		ini_set('display_errors', true);
		$view = new View();
		$view->setViewsDir('../views/');
		$view->setDI(new \Phalcon\DI\FactoryDefault());
		$view->registerEngines(array(".phtml" => "\Phalcon\Mvc\View\Engine\Volt"));
		$view->setRenderLevel(View::LEVEL_NO_RENDER);
		echo $view->getRender('', 'testform',[]);
	});
	$app->post('/method1', function () use ($app) {
		$method = Method::get('method1');
		echo json_encode(['success'=>$method->action($app->request), 'errors'=>$method->getErrors(), 'errorCode' => $method->getErrorCode()]);
	});

	$app->post('/method2', function () use ($app) {
		$method = Method::get('method2');
		echo json_encode(['success'=>$method->action($app->request), 'errors'=>$method->getErrors(), 'errorCode' => $method->getErrorCode()]);
	});

	$app->notFound(function () use ($app) {
		$app->response->setStatusCode(404, "Not Found")->sendHeaders();
		echo json_encode(['errors'=>['No such method'], 'success'=>false, 'errorCode' => 404]);
	});
	
	$app->get('/', function () use ($app) {
		$app->response->setStatusCode(400, "Bad Request")->sendHeaders();
		echo json_encode(['errors'=>['No method set'], 'success'=>false, 'errorCode' => 400]);
	});

	$app->handle();

} catch (\Exception $e) {
	//echo $e->getMessage(), PHP_EOL; //todo: send email to developer
	//echo $e->getTraceAsString();
	header("HTTP/1.0 500 Internal Server Error");
	echo json_encode(['errors'=>['Internal Server Error'], 'success'=>false, 'errorCode' => 500]);
}

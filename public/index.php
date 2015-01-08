<?php
error_reporting(E_ALL);

if (isset($_SERVER["REQUEST_URI"])) {
	$url = parse_url($_SERVER["REQUEST_URI"])["path"];
	$_GET['_url'] = $url;
}

try {

	$path = __DIR__ . '/../apps/config/';

	Phalcon\Config\Adapter\Php::setBasePath($path);
	
	$config = Phalcon\Config\Adapter\Php::factory('config.php');

	$filename = 'config.local.php';
	if (file_exists($path . $filename)) {
		$config->merge($config->load($filename));
	}

	$loader = new Phalcon\Loader();

	$loader->registerDirs(
			array(
				$config->controllersDir,
				$config->modelsDir,
				$config->collectionDir,
				$config->libraryDir,
			)
	)->register();

	$di = new Phalcon\DI\FactoryDefault();

	$di->set('session', function () {
		$session = new Phalcon\Session\Adapter\Files();
		$session->start();

		return $session;
	}, TRUE);

	$di->set('url', function () use ($config) {
		$url = new Phalcon\Mvc\Url();
		$url->setBaseUri($config->baseUri);
		return $url;
	}, true);

	$di->set('view', function () use ($config) {
		$view = new Phalcon\Mvc\View();
		$view->setBasePath($config->viewsDir);
		return $view;
	}, true);

	$di->set('flashSession', function() use ($config) {
		$flash = new Phalcon\Flash\Session(array(
			'error' => $config->style->error,
			'success' => $config->style->success,
			'notice' => $config->style->notice,
		));
		return $flash;
	}, TRUE);

	$di->set('config', function () use ($config) {
		$config['docroot'] = __DIR__.DIRECTORY_SEPARATOR;
		return $config;
	}, TRUE);

	$di->set('crypt', function() {
		$crypt = new Phalcon\Crypt();
		$crypt->setKey($config->crypt);
		return $crypt;
	}, TRUE);

	$di->set('collectionManager', function() {
		return new Phalcon\Mvc\Collection\Manager();
	}, TRUE);

	$di->set('mongo', function() use ($config) {
		$m = new MongoClient($config->mongo->server);
		return $m->selectDB('files');
	}, TRUE);

	$di->set('router', function() {
		$router = new \Phalcon\Mvc\Router();
		$router->add('/:namespace/:controller/:action/:params', array(
			'namespace' => 1,
			'controller' => 2,
			'action' => 3,
			'params' => 4,
		));
		return $router;
	}, TRUE);

	$application = new \Phalcon\Mvc\Application($di);

	echo $application->handle()->getContent();
} catch (\Exception $e) {
	echo $e->getMessage();
}

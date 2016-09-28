<?php
/**
 * Services are globally registered in this file
 *
 * @var \Phalcon\Config $config
 */

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Di\FactoryDefault;
use Phalcon\Session\Adapter\Redis as SessionAdapter;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Flash\Direct as Flash;

/**
 * The FactoryDefault Dependency Injector automatically registers the right services to provide a full stack framework
 */
$di = new FactoryDefault();

/**
 * Registering a router
 */
$di->setShared('router', function () {
    $router = new Router();

    $router->setDefaultModule('frontend');
    $router->setDefaultNamespace('Cloud\Frontend\Controllers');

    return $router;
});

/**
 * The URL component is used to generate all kinds of URLs in the application
 */
$di->setShared('url', function () use ($config) {
    $url = new UrlResolver();
    //$url->setBaseUri($config->application->baseUri);
	$url->setStaticBaseUri("/");

    return $url;
});

/**
 * Setting up the view component
 */
$di->setShared('view', function () use ($config) {

    $view = new View();

//    $view->setViewsDir($config->application->viewsDir);
	/*
    $view->registerEngines(array(
        '.volt' => function ($view, $di) use ($config) {

            $volt = new VoltEngine($view, $di);

            $volt->setOptions(array(
				'compileAlways'=>true,
                'compiledPath' => $config->application->cachesDir,
                'compiledSeparator' => '_'
            ));

            return $volt;
        },
        '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
    ));
	*/

    return $view;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () use ($config) {
    $dbConfig = $config->database->toArray();
    $adapter = $dbConfig['adapter'];
    unset($dbConfig['adapter']);

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $adapter;

    return new $class($dbConfig);
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->setShared('modelsMetadata', function () {
    return new MetaDataAdapter();
});

/**
 * Starts the session the first time some component requests the session service
 */
$di->setShared('session', function () use ($config) {

    $session = new SessionAdapter(array(
        'host' => '127.0.0.1',
        'port' => '6379',
        'lifetime' => 86400,
        'prefix' => 'edu_'
    ));
    $session->start();
    return $session;
});

/**
 * Register the session flash service with the Twitter Bootstrap classes
 */
$di->set('flash', function () {
    return new Flash(array(
        'error'   => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info',
        'warning' => 'alert alert-warning'
    ));
});

/**
* Set the default namespace for dispatcher
*/
$di->setShared('dispatcher', function() use ($di) {
	$dispatcher = new Phalcon\Mvc\Dispatcher();
	$dispatcher->setDefaultNamespace('Cloud\Frontend\Controllers');

	$eventsManager = new Phalcon\Events\Manager();
	$eventsManager->attach("dispatch:beforeDispatchLoop", function ($event, $dispatcher) {
	});

	$eventsManager->attach("dispatch:beforeException", function ($event, $dispatcher, $exception) use ($di){
		if ($exception instanceof Phalcon\Mvc\Dispatcher\Exception) {
			$dispatcher->forward(
				array(
					'controller' => 'error',
					'action'     => 'show404'
				)
			);

			//  不再继续执行,直接forward
			return false;
		}
	});

	$dispatcher->setEventsManager($eventsManager);
	return $dispatcher;
});

/**
 * AutoLoader
 */
$di->setShared('loader', function(){
	$loader = new Phalcon\Loader();
	$loader->registerDirs(array(
		APP_PATH.'/lib/',
	));

	$loader->registerNamespaces(array(
		'Cloud\Models' => APP_PATH. '/models/',
	));
	return $loader;
});

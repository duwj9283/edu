<?php

namespace Cloud\API;

use Phalcon\DiInterface;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;

use Phalcon\Events\Manager as EventsManager;
use Phalcon\Db\Profiler as ProfilerDb;
use Phalcon\Logger\Adapter\File as FileAdapter;

class Module implements ModuleDefinitionInterface
{
    /**
     * Registers an autoloader related to the module
     *
     * @param DiInterface $di
     */
    public function registerAutoloaders(DiInterface $di = null)
    {

		$loader = $di->getShared("loader");

        $loader->registerNamespaces(array(
            'Cloud\API\Controllers' => __DIR__ . '/controllers/',
        ),true);

        $loader->register();
    }

    /**
     * Registers services related to the module
     *
     * @param DiInterface $di
     */
    public function registerServices(DiInterface $di)
    {
        /**
         * Read configuration
         */
        $config = include APP_PATH . "/apps/api/config/config.php";

        /**
         * Setting up the view component
         */
        $di['view'] = function () {
            $view = new View();
            $view->setViewsDir(__DIR__ . '/views/');
            return $view;
        };

        $di['redis'] = function () use ($config) {
            $redis = new \redis();
            $redis->connect($config->redis->host, $config->redis->port);
            $redis->select(1);
            return $redis;
        };

        $di['config'] = $config;

        $di['profiler'] = function(){
            return new ProfilerDb();
        };

        /**
         * Database connection is created based in the parameters defined in the configuration file
         */
        $di['db'] = function () use ($di,$config) {
            $config = $config->database->toArray();

            $eventsManager = new EventsManager();

            // Get a shared instance of the DbProfiler
            $profiler      = $di['profiler'];

            $eventsManager->attach('db', function ($event, $connection) use ($profiler) {
                if ($event->getType() == 'beforeQuery') {
                    $profiler->startProfile($connection->getSQLStatement());
                }
                if ($event->getType() == 'afterQuery') {
                    $profiler->stopProfile();
                }
            });
            $dbAdapter = '\Phalcon\Db\Adapter\Pdo\\' . $config['adapter'];
            unset($config['adapter']);
            $connection = new $dbAdapter($config);
            $connection->setEventsManager($eventsManager);
            return $connection;
        };

		$di->setShared('logger', function() use($config){
			$logger = new FileAdapter(
				$config->application->logsDir."error_".date("Y-m-d").".log",
				array(
					'mode'=>'a'
				)
			);
			return $logger;
		});
    }
}

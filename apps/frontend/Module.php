<?php

namespace Cloud\Frontend;

use Phalcon\DiInterface;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;


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
            'Cloud\Frontend\Controllers' => __DIR__ . '/controllers/',
			'Cloud\Frontend\Components' => __DIR__ . '/components/',
        ), true);

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
        $config = include APP_PATH . "/apps/frontend/config/config.php";
        $constant = include APP_PATH . "/apps/frontend/config/constant.php";
        $di['constant']=$constant;
        /**
         * Database connection is created based in the parameters defined in the configuration file
         */
        $di['db'] = function () use ($config) {
            $config = $config->database->toArray();

            $dbAdapter = '\Phalcon\Db\Adapter\Pdo\\' . $config['adapter'];
            unset($config['adapter']);

            return new $dbAdapter($config);
        };
        $di['config'] = $config;

		$di->setShared('view', function () use ($config) {

			$view = new View();

			$view->setViewsDir($config->application->viewsDir);
			$view->setRenderLevel(View::LEVEL_ACTION_VIEW);

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

			return $view;
		});

		$di->setShared("elements", new \Cloud\Frontend\Components\Elements());
    }
}

<?php
use Phalcon\Mvc\Application;
error_reporting(E_ALL);
define('APP_PATH', realpath('..'));
define('UPLOAD_PATH','/alidata/upload/');
try {
    /**
     * Read the configuration
     */
    $config = include APP_PATH . "/apps/frontend/config/config.php";
    /**
     * Read the constant
     */
	include APP_PATH . "/config/constants.php";

    define('PERIOD',\Period::College);//学段

    /**
     * Include services
     */
    require __DIR__ . '/../config/services.php';

    /**
     * Handle the request
     */
    $application = new Application($di);

    /**
     * Include modules
     */
    require __DIR__ . '/../config/modules.php';

    /**
     * Include routes
     */
    require __DIR__ . '/../config/routes.php';

    echo $application->handle()->getContent();

} catch (\Exception $e) {
    echo $e->getMessage();
}

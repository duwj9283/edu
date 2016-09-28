<?php

return new \Phalcon\Config(array(
    'database' => array(
        'adapter'  => 'Mysql',
        'host'     => 'localhost',
        'username' => 'root',
        'password' => 'duwj9283',
        'dbname'   => 'bbyxy',
        'charset'  => 'utf8',
    ),
    'application' => array(
        'controllersDir' => __DIR__ . '/../controllers/',
        'modelsDir'      => __DIR__ . '/../models/',
        'libraryDir'     => __DIR__ . '/../library/',
        'migrationsDir'  => __DIR__ . '/../migrations/',
        'viewsDir'       => __DIR__ . '/../views/',
		'cachesDir'      => __DIR__ . '/../caches/',
        'baseUri'        => '/live/'
    ),
    'redis' => array(
        'host'        => '127.0.0.1',
        'port'        => '6379'
    ),
    'vs2_serv' => array(
        'host' => 'edu.iguangj.com',
        'port' => 1935,
        'app_name' => 'live'
    )
));

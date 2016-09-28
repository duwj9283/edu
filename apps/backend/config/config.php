<?php

return new \Phalcon\Config(array(
    'database' => array(
        'adapter'  => 'Mysql',
        'host'     => 'localhost',
        'username' => 'admin',
        'password' => 'J6NQ6PDQgUxdgr6j',
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
        'baseUri'        => '/backend/'
    ),
    'redis' => array(
        'host'        => '127.0.0.1',
        'port'        => '6379'
    ),
));

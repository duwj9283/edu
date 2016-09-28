<?php

/**
 * Register application modules
 */
$application->registerModules(array(
    'frontend' => array(
        'className' => 'Cloud\Frontend\Module',
        'path' => __DIR__ . '/../apps/frontend/Module.php'
    ),
	'backend' => array(
		'className' => 'Cloud\Backend\Module',
		'path' => __DIR__ . '/../apps/backend/Module.php'
	),
	'live' => array(
		'className' => 'Cloud\Live\Module',
		//'path' => __DIR__ . '/../apps/aa/Module.php'
		'path' => __DIR__ . '/../apps/live/Module.php'
	),
	'api' => array(
		'className' => 'Cloud\API\Module',
		'path' => __DIR__ . '/../apps/api/Module.php'
	)
));

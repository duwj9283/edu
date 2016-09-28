<?php

/**
 * Register application modules
 */
$application->registerModules(array(
    'frontend' => array(
        'className' => 'Cloud\Frontend\Module',
        'path' => __DIR__ . '/../apps/frontend/Module.php'
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

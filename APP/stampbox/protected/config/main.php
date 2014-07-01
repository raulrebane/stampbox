<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
//Yii::setPathOfAlias('bootstrap', realpath(__DIR__.'/../../../yiibooster/'));

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'StampBox',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii too
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
                        'loginUrl'=>array('site/login'),
		),
		// uncomment the following to enable URLs in path-format
		/*
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		*/
		'db'=>array(
//    			'tablePrefix'=>'ds',
    			'connectionString' => 'pgsql:host=localhost;dbname=ds',
    			'username' => 'ds_user',
    			'password' => 'Apua1234',
    			'charset' => 'UTF8',
		),
                'session' => array (
                    'class' => 'system.web.CDbHttpSession',
                    'connectionID' => 'db',
                    'sessionTableName' => 'websessions',
                ),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning, info',
                                        'filter'=>'CLogFilter',
                                        'logFile'=>'stampbox.log'
				),
				// uncomment the following to show log messages on web pages
				array(
					'class'=>'CWebLogRoute',
				        'levels'=>'error, info',
                                        'filter'=>'CLogFilter',
				),
                                /*
                                array(
                                        'class'=>'CFileLogRoute',
                                        'categories'=>'system.db.*',
                                        'logFile'=>'sql.log',
                                ),
                                 * 
                                 */
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);

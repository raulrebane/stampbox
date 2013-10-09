<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Console Application',

	// preloading 'log' component
	'preload'=>array('log'),

	// application components
	'components'=>array(
                'db'=>array(
//                      'tablePrefix'=>'ds',
                        'connectionString' => 'pgsql:host=localhost;dbname=ds',
                        'username' => 'ds_user',
                        'password' => 'Apua1234',
                        'charset' => 'UTF8',
                ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
	),
);

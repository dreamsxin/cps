<?php

return array(
	'mongo' => array(
		'server' => 'mongodb://localhost:27017'
	),
	'style' => array(
		'error' => 'alert alert-danger',
		'success' => 'alert alert-success',
		'notice' => 'alert alert-warning',
	),
	'controllersDir' => __DIR__ . '/../controllers/',
	'modelsDir' => __DIR__ . '/../models/',
	'collectionDir' => __DIR__ . '/../collections/',
	'libraryDir' => __DIR__ . '/../library/',
	'viewsDir' => __DIR__ . '/../views/',
	'domain' => 'http://www.eotu.com/',
	'baseUri' => '/',
	'crypt' => '$%^$#$%',
);

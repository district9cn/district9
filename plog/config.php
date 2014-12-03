<?php
return array(
	'loggers' => array(
		'base' => '../log/',
		'system' => 'system',
		'app' => 'app'
	),
	'levels' => array('DEBUG', 'INFO', 'ERROR', 'WARN', 'FATAL'),
	'handlers' => array(
		'file' => array(
			'driver' => 'file',
			'level' => array('DEBUG', 'INFO', 'ERROR', 'WARN'),
			'formatter' => 'generic',
			'enabled' => true,
			'config' => array(
				'dir' => '../log/var/log',
			),
		),
	),
	'formatters' => array(
		'generic' => '{time} {level} [{logger}:{line}] {uri} {message}',
	),
);

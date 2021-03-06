<?php
error_reporting(-1);
ini_set('display_errors', true);

require_once __DIR__.'/../vendor/autoload.php';

use eio\Client;

$config=json_decode(file_get_contents(__DIR__.'/config.json'), true);

try {
	foreach ([
		// 'socket_create', // ok!
		'stream_socket_create', // ok!
		// 'fsockopen', // failed...
	] as $method) {
		$uri = sprintf('%s://%s:%d/engine.io', $config['protocol'], $config['host'], $config['port']);
		$client = new Client($uri, ['connect_method' => $method], true);
		$res = $client->send(json_encode([
			'abc' => 123
		]));
		printf('send receive: (%s)' . PHP_EOL, $res);
		$res = $client->ping();
		printf('ping receive: (%s)' . PHP_EOL, $res);
		$client->close();
	}
} catch (\Exception $e) {
	error_log($e->getMessage());
} catch (\Error $e) {
	error_log($e->getMessage());
}

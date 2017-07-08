<?php

$api_key = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';

$url = 'http://flarum.docker.home.balancer.ru/api';

// POST /api/posts - create a new post

$client = new GuzzleHttp\Client([
    'base_uri' => $url,
    // You can set any number of default request options.
    'timeout'  => 2.0,
]);

$r = $client->request('POST', $url.'/post', [
    'json' => [
		'' => 'bar'
	]
]);

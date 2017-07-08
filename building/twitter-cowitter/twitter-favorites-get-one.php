<?php

require 'vendor/autoload.php';
require '/home/balancer/config.php';

use mpyw\Co\Co;
use mpyw\Co\CURLException;
use mpyw\Cowitter\Client;
use mpyw\Cowitter\HttpException;

$id = 000000000000000000;

$client = new Client([$twitter_consumer_key, $twitter_consumer_secret, $twitter_access_token, $twitter_access_token_secret]);

$statuses = $client->get('search/tweets', ['q' => 'mod_russia', 'since_id' => $id-1, 'max_id' => $id+1, 'include_entities' => 'true'])->statuses;
//$statuses = $client->get('statuses/show', ['id' => $id]);
dump($statuses);

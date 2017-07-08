<?php

require 'vendor/autoload.php';
require 'config.php';

$file = '/home/balancer/Sync/airbase-forums-push/topic-30291.md';

B2\FluxBB\Topic::infonesy_import($file);


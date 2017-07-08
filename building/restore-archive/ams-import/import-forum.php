<?php

use Symfony\Component\Yaml\Yaml;

require 'vendor/autoload.php';
require 'config.php';

$file = '/home/balancer/Sync/airbase-forums-push/forum-79.md';

B2\FluxBB\Forum::infonesy_import($file);


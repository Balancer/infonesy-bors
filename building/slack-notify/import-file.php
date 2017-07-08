<?php

use CL\Slack\Transport\ApiClient;
use CL\Slack\Payload\ChatPostMessagePayload;

define('COMPOSER_ROOT', __DIR__);

require 'vendor/autoload.php';
require 'config.php';

$file = $argv[1];

if(!is_file($file) || !preg_match('/\.json$/', $file) || !filesize($file))
	return;

$data = json_decode(file_get_contents($file), true);

if(empty($data['Author']['Title']))
	$user = preg_replace('/^(.+?) пишет в.+/s', '$1', trim($data['Message']));
else
	$user = $data['Author']['Title'];

echo $file, PHP_EOL;

require '/home/balancer/config.php';

/*
$client = new Slack\Client('balancer', $slack_token);
$slack = new Slack\Notifier($client);

$message = new Slack\Message\Message($data['Message']);

$message->setChannel('#forum-messages')
//    ->setMrkdwn(true)
    ->setIconEmoji(':ghost:')
    ->setUsername('slack-php');

$slack->notify($message);
*/

// https://github.com/maknz/slack
// Instantiate without defaults
// $client = new Maknz\Slack\Client('http://balancer.slack.com');
// Instantiate with defaults, so all messages created
// will be sent from 'Cyril' and to the #accounting channel
// by default. Any names like @regan or #channel will also be linked.
//$settings = [
  //  'username' => 'BalaBOT',
//    'channel' => '#forum-messages',
//    'link_names' => true
//];

// $client = new Maknz\Slack\Client('http://balancer.slack.com', $settings);
//$client->to('#forum-messages')->send($data['Message']);

$client = new ApiClient($slack_token);

$payload = new ChatPostMessagePayload();
$payload->setChannel('#forum-messages');
$payload->setMessage($data['Message']);
$payload->setUsername($user);

$response = $client->send($payload);

// the following is very much up to you, this is just a very simple example
if ($response->isOk()) {
    echo sprintf('Successfully posted message on %s', $response->getChannelId())."\n";
} else {
    echo sprintf('Failed to post message to Slack: %s', $response->getErrorExplanation())."\n";
}


/*
use Frlnc\Slack\Http\SlackResponseFactory;
use Frlnc\Slack\Http\CurlInteractor;
use Frlnc\Slack\Core\Commander;

$interactor = new CurlInteractor;
$interactor->setResponseFactory(new SlackResponseFactory);

$commander = new Commander($slack_token, $interactor);

$response = $commander->execute('chat.postMessage', [
    'channel' => '#forum-messages',
    'text'    => $data['Message']
]);

if ($response['ok'])
{
    // Command worked
}
else
{
    // Command didn't work
}
*/

//system("echo \"".addslashes($data['Message'])."\" | slacker -t $slack_token -c 'forum-messages' -n BalaBOT");

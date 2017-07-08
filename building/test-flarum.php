<?php

define('FLARUM_DIR', '/var/docker/sites/flarum/htdocs');

require FLARUM_DIR.'/vendor/autoload.php';

class MyServer extends Flarum\Forum\Server
{
	public function app()
	{
		$app = $this->getApp();
		return $app;
	}
}

$server = new MyServer(FLARUM_DIR);

$app = $server->app();

$discussionId = 2;
$actorId = 2;

$actor = Flarum\Core\User::find($actorId);
$discussion = Flarum\Core\Discussion::find($discussionId);

$data = [
	'attributes' => [
		'content' => '@Administrator1#21  Hello **world**! (with Markdown)',
		'time' => Carbon\Carbon::now('utc')->toDateTimeString(),
	],
];

$ipAddress = NULL;

$cmd = new Flarum\Core\Command\PostReply($discussionId, $actor, $data, $ipAddress);

// echo '<xmp>'; var_dump($app->mailer); exit();

	/**
	 * @param Dispatcher $events
	 * @param DiscussionRepository $discussions
	 * @param NotificationSyncer $notifications
	 * @param PostValidator $validator
	 */
//	public function __construct(
//	   Dispatcher $events,
//		DiscussionRepository $discussions,
//		NotificationSyncer $notifications,
//		PostValidator $validator
//	) {

$handler = new Flarum\Core\Command\PostReplyHandler(
	$app->events,
	new	Flarum\Core\Repository\DiscussionRepository,
	new	Flarum\Core\Notification\NotificationSyncer(new Flarum\Core\Repository\NotificationRepository, new Flarum\Core\Notification\NotificationMailer($app->mailer)),
	new Flarum\Core\Validator\PostValidator($app->validator, $app->events, $app->make('Symfony\Component\Translation\TranslatorInterface'))
);

$post = $handler->handle($cmd);

echo 'Posted as '.$post->id;


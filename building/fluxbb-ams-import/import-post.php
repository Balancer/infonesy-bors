<?php

use Symfony\Component\Yaml\Yaml;
use Respect\Relational\Sql;

require 'vendor/autoload.php';
require 'config.php';

$file = '/home/balancer/Sync/airbase-forums-push/2015-12-14-20-50-20--post-4043285.md';

$content = file_get_contents($file);

if(!preg_match("/^---\n(.+?)\n---\n+(.+)$/s", trim($content), $m))
	exit("Oops: ".$content);

list($foo, $yaml, $src) = $m;

$data = Yaml::parse($yaml);

print_r($data);

echo "-----------------------------------\n";

echo $src;

$fluxbb_mapper = new Respect\Relational\Mapper($fluxbb_connector);

// Пробуем прочитать старое значение
$post = $fluxbb_mapper->posts(['uuid' => $data['UUID']])->fetch();
if(!$post) // Если не нашли, то новая запись
    $post = new stdClass;

// Заполняем объект — строку для записи
// [UUID] => ru.balancer.board.post.4043285
$post->uuid = $data['UUID'];
// [Node] => ru.balancer.board
// [TopicUUID] => ru.balancer.board.topic.92272
// [Title] => РККФ в ВОВ. [Вованыч_1977#14.12.15 20:50]
// [Author] => Вованыч_1977
$post->poster = $data['Author'];
// [AuthorMD] => 931f04765b7b0bb2aa09e2726f857364
// [Date] => Mon, 14 Dec 2015 20:50:15 +0300
$post->posted =  strtotime($data['Date']);
// [Modify] => Mon, 14 Dec 2015 20:50:15 +0300
// [Type] => Post
// [Markup] => lcml
// [AnswerTo] => ru.balancer.board.post.4043266
$post->message =  $src;

$post->topic_id = find_topic_id_by_uuid($data['TopicUUID'], $fluxbb_connector);
$post->poster_id = 1;

$fluxbb_mapper->posts->persist($post);

$fluxbb_mapper->flush();

topic_update($post->topic_id, $fluxbb_connector);

function find_topic_id_by_uuid($topic_uuid, $connector)
{
	$fluxbb_mapper = new Respect\Relational\Mapper($connector);
	$topic = $fluxbb_mapper->topics(['uuid' => $topic_uuid])->fetch();

	if($topic)
		return $topic->id;

	return 1;
}

function topic_update($topic_id, $connector)
{
	$topic_id = intval($topic_id);

	$fluxbb_mapper = new Respect\Relational\Mapper($connector);
	$topic = $fluxbb_mapper->topics($topic_id)->fetch();
	if(!$topic)
		return;

	$first_post = $fluxbb_mapper->posts(['topic_id' => $topic_id])->fetch(Sql::orderBy('posted'));
	$last_post  = $fluxbb_mapper->posts(['topic_id' => $topic_id])->fetch(Sql::orderBy('posted DESC'));

	$topic->first_post_id	= $first_post->id;
	$topic->last_post		= $last_post->posted;
	$topic->last_post_id	= $last_post->id;
	$topic->last_poster		= $last_post->poster;
	$topic->num_replies		= $connector->query('SELECT COUNT(*) FROM posts WHERE topic_id='.$topic_id)->fetchColumn(0)-1;

	$topic->forum_id = find_forum_id_by_uuid($topic->forum_uuid, $connector);

	$fluxbb_mapper->topics->persist($topic);
	$fluxbb_mapper->flush();

	forum_update($topic->forum_id, $connector);
}

function find_forum_id_by_uuid($forum_uuid, $connector)
{
	$fluxbb_mapper = new Respect\Relational\Mapper($connector);
	$forum = $fluxbb_mapper->forums(['uuid' => $forum_uuid])->fetch();

	if($forum)
		return $forum->id;

	return 1;
}

function forum_update($forum_id, $connector)
{
	$forum_id = intval($forum_id);

	$fluxbb_mapper = new Respect\Relational\Mapper($connector);
	$forum = $fluxbb_mapper->forums($forum_id)->fetch();
	if(!$forum)
		return;

var_dump($forum_id, $forum->id);

	$last_topic  = $fluxbb_mapper->topics(['forum_id' => $forum_id])->fetch(Sql::orderBy('posted DESC'));

	$forum->num_topics		= $connector->query('SELECT COUNT(*) FROM topics WHERE forum_id='.$forum_id)->fetchColumn(0);
	$forum->num_posts		= $connector->query('SELECT SUM(`num_replies`+1) FROM topics WHERE forum_id='.$forum_id)->fetchColumn(0);

	$forum->last_post		= $last_topic->last_post;
	$forum->last_post_id	= $last_topic->last_post_id;
	$forum->last_poster		= $last_topic->last_poster;


	$fluxbb_mapper->forums->persist($forum);
	$fluxbb_mapper->flush();
}

<?php

$url = $argv[1];

$html = file_get_contents($url);
file_put_contents('test.html', $html);

//$html = file_get_contents('test.html');

$doc = new DOMDocument();
@$doc->loadHTML($html);

$finder = new DomXPath($doc);
$nodes = $finder->query("//p[contains(@class, 'tweet-text')]");

$tweet = $nodes->item(0)->nodeValue;

$title = preg_replace("!pic.twitter.com/\w+$!", " ", $tweet);
$title = preg_replace("!#!", " ", $title);

$metas = $doc->getElementsByTagName('meta');

$title = trim(preg_replace('/\s+/', ' ', $title));

// <span class="_timestamp js-short-timestamp js-relative-timestamp"  data-time="1459780988" data-time-ms="1459780988000" data-long-form="true" aria-hidden="true">14 мин</span>

$date = $finder->query("//span[contains(@class, '_timestamp')]");
$date = $date->item(0)->getAttribute('data-time');

$dirname = $title;
$dirname = preg_replace('=[/#\s,\.]+=', '-', $dirname);
$dirname = preg_replace('/\-+/', '-', $dirname);

$dirname = trim($dirname, '-');

$dirname = date('Ymd-Hi-', $date).$dirname;

mkdir($dirname);

$md = "$title\n"
	.str_repeat("=", mb_strlen($title))."\n\n"
	.$tweet
	."\n\n";

foreach($metas as $meta)
{
	if($meta->getAttribute('property') == 'og:image')
	{
		$image_url = $meta->getAttribute('content');
		$image_file = str_replace(':large', '', basename($image_url));
		if(!file_exists("$dirname/$image_file"))
		{
			$image = file_get_contents($image_url);
			if($image)
				file_put_contents("$dirname/$image_file", $image);
		}

		$md .= "![]($image_file)\n";
	}
}

$md .= "\n// $url\n";


file_put_contents("$dirname/index.md", $md);

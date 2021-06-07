<?php

use Algolia\AlgoliaSearch\SearchClient;

require_once __DIR__ . '/vendor/autoload.php';

const ALG_APP = 'S7OGBIAJTV';
const ALG_KEY = 'd161a2f4cd2d69247c529a3371ad3050';
const ALG_IDX = 'getkirby-3';
/*
	App ID / App Key are taken from getkirby.com, as allowed by Bastian in a private messages.
	Source: https://github.com/getkirby/getkirby.com/blob/main/site/config/search.php
*/

function results(array $items){
	echo json_encode(['items' => $items]);
	exit;
}

$query = count($argv) > 1 ? $argv[1] : '';
$algolia = SearchClient::create(ALG_APP, ALG_KEY);
$index = $algolia->initIndex(ALG_IDX);

$results = $index->search($query, [
	'hitsPerPage' => 30,
	'X-Forwarded-For' => 'Alfred Workflow'
]);

if ($results['nbHits'] === 0){
	results([]);
}

$alfredResults = array_map(function($hit){
	$area = !is_null($hit['area']) ? ucfirst($hit['area']) : null;
	$desc= html_entity_decode($hit['description']);
	return [
		'uid' => $hit['objectID'],
		'title' => $hit['title'],
		'subtitle' => !is_null($area)
			? "{$area} • {$desc}"
			: $desc,
		'icon' => [
			"path" => is_string($area) ? "Icon-{$area}.png" : "icon.png"
		],
		'arg' => "https://getkirby.com/{$hit['objectID']}",
		'quicklookurl' => "https://getkirby.com/{$hit['objectID']}",
		'valid' => true
	];
}, $results['hits']);

results($alfredResults);

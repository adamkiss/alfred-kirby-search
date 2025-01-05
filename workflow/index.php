<?php

use Algolia\AlgoliaSearch\SearchClient;

error_reporting(E_ALL & ~E_DEPRECATED);

require_once __DIR__ . '/vendor/autoload.php';

const ALG_APP = 'S7OGBIAJTV';
const ALG_KEY = 'd161a2f4cd2d69247c529a3371ad3050';
const ALG_IDX = 'getkirby-3';
/*
	App ID / App Key are taken from getkirby.com, as allowed by Bastian in a private messages.
	Source: https://github.com/getkirby/getkirby.com/blob/main/site/config/search.php
*/

function results(array $items) {
	echo json_encode(['items' => $items]);
	exit;
}

$query = count($argv) > 1 ? $argv[1] : '';
$algolia = SearchClient::create(ALG_APP, ALG_KEY);
$index = $algolia->initIndex(ALG_IDX);

if (preg_match('/^([gprck]) /i', $query, $m)) {
	$area = [
		'g' => 'guide',
		'p' => 'plugin',
		'r' => 'reference',
		'c' => 'cookbook',
		'k' => 'kosmos',
	][strtolower($m[1])];
	$query = substr($query, 2);
}

$results = $index->search($query, [
	'hitsPerPage' => 30,
	'X-Forwarded-For' => 'Alfred Workflow',
	'filters' => $area ?? false ? "area:{$area}" : ''
]);

if ($results['nbHits'] === 0) {
	results([]);
}

$alfredResults = array_map(function ($hit) {
	$area = !is_null($hit['area']) ? ucfirst($hit['area']) : null;
	$desc = html_entity_decode($hit['intro'] ?? '');
	return [
		'uid' => $hit['objectID'],
		'title' => $hit['title'],
		'subtitle' => !is_null($area)
			? "{$area} • {$desc}"
			: $desc,
		'icon' => [
			'path' => is_string($area) ? "Icon-{$area}.png" : 'Icon.png'
		],
		'arg' => "https://getkirby.com/{$hit['objectID']}",
		'quicklookurl' => "https://getkirby.com/{$hit['objectID']}",
		'valid' => true
	];
}, $results['hits']);

/**
 * Now prepend: custom Adam's results not tagged by index, if they match
 */
$shortcuts = [
	'kt' => ['https://getkirby.com/docs/reference/text/kirbytags', 'KirbyText: Reference index'],
	'help' => ['https://getkirby.com/docs/reference/templates/helpers', 'Helpers'],
	'fmidx' => ['https://getkirby.com/docs/reference/templates/field-methods', 'Field methods index'],
	'icons' => ['https://getkirby.com/docs/reference/panel/icons', 'Icon index'],
	'allc' => ['https://getkirby.com/docs/reference/objects', 'All classes'],
	'roots' => ['https://getkirby.com/docs/reference/system/roots', 'System > Roots'],
	'validators' => ['https://getkirby.com/docs/reference/system/validators', 'System > Validators'],
	'hooks' => ['https://getkirby.com/docs/reference/plugins/hooks', 'List of hooks'],
	'uilist' => ['https://getkirby.com/docs/reference/plugins/ui', 'UI Kit Index'],
	'tools' => ['https://getkirby.com/docs/reference/objects#toolkit', 'Toolkit']
];
if (getenv('user_adam_shortcuts') === '1' && $match = $shortcuts[$query] ?? false) {
	array_unshift($alfredResults, [
		'uid' => "adam-{$query}",
		'title' => $match[1],
		'subtitle' => "One of Adam's shortcuts",
		'icon' => [
			'path' => 'Icon-Adam.png'
		],
		'arg' => $match[0],
		'quicklookurl' => $match[0],
		'valid' => true
	]);
}

results($alfredResults);

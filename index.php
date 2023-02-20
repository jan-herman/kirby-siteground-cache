<?php

use Kirby\Cms\App as Kirby;

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('jan-herman/siteground-cache', [
    'cacheTypes' => [
		'siteground' => 'JanHerman\SiteGroundCache\SiteGroundCache'
	]
]);

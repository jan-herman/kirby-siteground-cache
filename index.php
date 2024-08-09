<?php

use Kirby\Cms\App as Kirby;

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('jan-herman/siteground-cache', [
    'cacheTypes' => [
        'siteground' => 'JanHerman\SiteGroundCache\SiteGroundCache'
    ],
    'routes' => [
        [
            'pattern' => 'flush-cache',
            'action'  => function () {
                $kirby = kirby();
                $site = $kirby->site();

                if ($site->user()) {
                    $kirby->cache('pages')->flush();
                }

                go($site->url());
            }
        ]
    ]
]);

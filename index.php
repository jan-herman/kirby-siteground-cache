<?php

use Kirby\Cms\App as Kirby;
use Kirby\Cms\Page;
use JanHerman\SiteGroundCache\Cache;

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('jan-herman/siteground-cache', [
    'cacheTypes' => [
        'siteground' => Cache::class
    ],
    'hooks' => [
        'page.render:after' => function (string $contentType, array $data, string $html, Page $page): string {
            $kirby = kirby();
            $options = $kirby->cache('pages')->options();

            if (
                ($options['active'] ?? false) !== true ||
                ($options['type'] ?? null) !== 'siteground' ||
                $contentType !== 'html'
            ) {
                return $html;
            }

            $ignore = $options['ignore'] ?? null;
            $ignored = false;

            if ($ignore instanceof Closure) {
                $ignored = $ignore($page) === true;
            } elseif (is_array($ignore) === true) {
                $ignored = in_array($page->id(), $ignore, true);
            }

            if ($ignored === true) {
                $kirby->response()->header('Cache-Control', 'no-cache');
            } elseif (array_key_exists('maxAge', $options) === true) {
                $kirby->response()->header('Cache-Control', 'max-age=' . max(0, (int) $options['maxAge']));
            }

            return $html;
        }
    ],
    'routes' => [
        [
            'pattern' => 'flush-cache',
            'action'  => function () {
                $kirby = kirby();
                $site = $kirby->site();

                if ($kirby->user()) {
                    $kirby->cache('pages')->flush();
                }

                go($site->url());
            }
        ]
    ]
]);

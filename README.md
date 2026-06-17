# Kirby SiteGround Cache

Kirby cache driver for purging SiteGround Dynamic Cache and controlling SiteGround cache headers from Kirby's pages cache configuration.

## Installation

Install the plugin and enable it as the pages cache driver:

```php
return [
    'cache' => [
        'pages' => [
            'active' => true,
            'type'   => 'siteground',
        ]
    ]
];
```

## Options

### maxAge

Set `maxAge` to send a `Cache-Control: max-age=<seconds>` header for rendered HTML pages. SiteGround uses this header to control how long the page should be kept in the Dynamic Cache.

```php
return [
    'cache' => [
        'pages' => [
            'active' => true,
            'type'   => 'siteground',
            'maxAge' => 6000,
        ]
    ]
];
```

If `maxAge` is not configured, the plugin does not send a cache lifetime header and SiteGround's default cache duration applies.

### ignore

Use Kirby's native pages cache `ignore` option to exclude pages from SiteGround Dynamic Cache. Ignored pages send `Cache-Control: no-cache`.

```php
return [
    'cache' => [
        'pages' => [
            'active' => true,
            'type'   => 'siteground',
            'maxAge' => 6000,
            'ignore' => fn ($page) => $page->template()->name() === 'contact',
        ]
    ]
];
```

You can also ignore pages by ID:

```php
return [
    'cache' => [
        'pages' => [
            'active' => true,
            'type'   => 'siteground',
            'ignore' => ['contact', 'private-area'],
        ]
    ]
];
```

## Purging

Logged-in users can visit `/flush-cache` to purge Kirby's pages cache, which also purges SiteGround Dynamic Cache.

You can also purge the cache with the Kirby CLI:

```sh
vendor/bin/kirby clear:cache
```

Any code or command that calls `kirby()->cache('pages')->flush()` will trigger the SiteGround purge as well.

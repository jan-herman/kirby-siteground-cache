<?php

namespace JanHerman\SiteGroundCache;

use Kirby\Cache\Cache;
use Kirby\Cache\Value;

class SiteGroundCache extends Cache
{
	/**
	 * Returns whether the cache is ready to
	 * store values
	 */
	public function enabled(): bool
	{
		return false;
	}

	/**
	 * Writes an item to the cache for a given number of minutes and
	 * returns whether the operation was successful
	 *
	 * <code>
	 *   // put an item in the cache for 15 minutes
	 *   $cache->set('value', 'my value', 15);
	 * </code>
	 */
	public function set(string $key, $value, int $minutes = 0): bool
	{
		return true;
	}

	/**
	 * Internal method to retrieve the raw cache value;
	 * needs to return a Value object or null if not found
	 */
	public function retrieve(string $key): Value|null
	{
		return null;
	}

	/**
	 * Removes an item from the cache and returns
	 * whether the operation was successful
	 */
	public function remove(string $key): bool
	{
		return true;
	}

	/**
	 * Flushes the entire cache and returns
	 * whether the operation was successful
	 */
	public function flush(): bool
	{
		// Check if we're on siteground
		if (!Site_Tools_Client::is_siteground()) {
			return true;
		}

		// Get hostname
		$kirby = kirby();
		$url = $kirby->site()->url();
		$hostname = str_replace( 'www.', '', parse_url($url, PHP_URL_HOST));

		// Build the request params.
		$args = array(
			'api'      => 'domain-all',
			'cmd'      => 'update',
			'settings' => array( 'json' => 1 ),
			'params'   => array(
				'flush_cache' => '1',
				'id'          => $hostname,
				'path'        => '(.*)',
			),
		);

		$site_tools_result = Site_Tools_Client::call_site_tools_client($args, true);

		if ($site_tools_result === false) {
			return false;
		}

		if (isset($site_tools_result['err_code'])) {
			error_log('There was an issue purging the cache for this URL: ' . $hostname . '. Error code: ' . $site_tools_result['err_code'] . '. Message: ' . $site_tools_result['message'] . '.');
			return false;
		}

		return true;
	}
}

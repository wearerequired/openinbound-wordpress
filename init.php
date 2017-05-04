<?php
/**
 * Separate init file that isn't compatible with PHP 5.3 or lower.
 *
 * @package Required\OpenInbound
 */

namespace Required\OpenInbound;

/**
 * Initializes the plugin.
 *
 * @since 1.0.0
 */
function init() {
	require_once __DIR__ . '/vendor/openinbound/openinbound-api/src/API/OI.php';

	$plugin = new Plugin();
	$plugin->init();
}

add_action( 'plugins_loaded', __NAMESPACE__ . '\init' );

<?php
/**
 * @wordpress-plugin
 * Plugin Name: OpenInbound for WP
 * Plugin URI:  https://openinbound.com
 * Description: Connector plugin for OpenInbound.com.
 * Version:     1.0.0
 * Author:      required
 * Author URI:  https://required.com
 * Text Domain: openinbound
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

namespace Required\OpenInbound;

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	include __DIR__ . '/vendor/autoload.php';
}

if ( ! class_exists( __NAMESPACE__ . '\Plugin' ) ) {
	trigger_error( sprintf( '%s does not exist. Check Composer\'s autoloader.', __NAMESPACE__ . '\Plugin' ) );

	return;
}

/**
 * Initializes the plugin.
 *
 * @since 1.0.0
 */
function init() {
	$plugin = new Plugin();
	$plugin->init();
}

add_action( 'plugins_loaded', __NAMESPACE__ . '\init' );

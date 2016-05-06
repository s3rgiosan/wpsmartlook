<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/s3rgiosan/wpsmartlook/
 * @since      1.0.0
 *
 * @package    Smartlook
 * @subpackage Smartlook/lib
 */

namespace s3rgiosan\Smartlook;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Smartlook
 * @subpackage Smartlook/lib
 * @author     Sérgio Santos <me@s3rgiosan.com>
 */
class Frontend {

	/**
	 * The plugin's instance.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    Plugin $plugin This plugin's instance.
	 */
	private $plugin;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param Plugin $plugin This plugin's instance.
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * Add custom javascript within head section.
	 *
	 * @since 1.0.0
	 */
	public function add_snippet() {

		if ( \is_admin() ) {
			return;
		}

		if ( \is_feed() ) {
			return;
		}

		if ( \is_robots() ) {
			return;
		}

		if ( \is_trackback() ) {
			return;
		}

		$snippet = trim( \get_option( 'smartlook_snippet' ) );

		if ( empty( $snippet ) ) {
			return;
		}

		echo html_entity_decode( $snippet );
	}
}

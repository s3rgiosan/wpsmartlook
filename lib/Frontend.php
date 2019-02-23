<?php

namespace s3rgiosan\WP\Plugin\Smartlook;

/**
 * The public-facing functionality of the plugin.
 *
 * @since   1.0.0
 */
class Frontend {

	/**
	 * The plugin's instance.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    Plugin
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
	 * Register hooks.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		\add_action( 'wp_head', [ $this, 'add_snippet' ], 99 );
	}

	/**
	 * Add custom javascript within head section.
	 *
	 * @since 1.2.0 Sanitize snippet code.
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

		// Is recording disabled for this content type?
		if ( $this->is_recording_disabled( \get_the_id() ) ) {
			return;
		}

		$snippet = trim( \get_option( 'smartlook_snippet' ) );
		if ( empty( $snippet ) ) {
			return;
		}

		echo \wp_kses(
			$snippet,
			[
				'script' => [
					'async' => [],
					'src'   => [],
				],
			]
		);
	}

	/**
	 * Check if the recording is disabled for a specific post ID.
	 *
	 * @param  int $post_id The post ID.
	 * @return bool
	 */
	public function is_recording_disabled( $post_id ) {
		return boolval( \get_post_meta( $post_id, 'smartlook_disable_rec', true ) );
	}
}

<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/s3rgiosan/wpsmartlook/
 * @since      1.0.0
 *
 * @package    Smartlook
 * @subpackage Smartlook/lib
 */

namespace s3rgiosan\Smartlook;

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @package    Smartlook
 * @subpackage Smartlook/lib
 * @author     Sérgio Santos <me@s3rgiosan.com>
 */
class I18n {

	/**
	 * The domain specified for this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string $domain The domain identifier for this plugin.
	 */
	private $domain;

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since 1.0.0
	 */
	public function load_plugin_textdomain() {

		\load_plugin_textdomain(
			$this->domain,
			false,
			dirname( dirname( \plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}

	/**
	 * Set the domain equal to that of the specified domain.
	 *
	 * @since 1.0.0
	 * @param string $domain The domain that represents the locale of this plugin.
	 */
	public function set_domain( $domain ) {
		$this->domain = $domain;
	}
}

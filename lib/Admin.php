<?php
/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       https://github.com/s3rgiosan/wpsmartlook/
 * @since      1.0.0
 *
 * @package    Smartlook
 * @subpackage Smartlook/lib
 */

namespace s3rgiosan\Smartlook;

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Smartlook
 * @subpackage Smartlook/lib
 * @author     Sérgio Santos <me@s3rgiosan.com>
 */
class Admin {

	/**
	 * The plugin's instance.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    Plugin
	 */
	private $plugin;

	/**
	 * The unique identifier of this plugin settings group name.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected $settings_name = 'smartlook_settings';

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
	 * The settings group name.
	 *
	 * @since  1.0.0
	 * @return string The settings group name.
	 */
	public function get_settings_name() {
		return $this->settings_name;
	}

	/**
	 * Add sub menu page to the Settings menu.
	 *
	 * @since 1.0.0
	 */
	public function admin_settings_menu() {

		if ( ! \current_user_can( 'manage_options' ) ) {
			return;
		}

		\add_options_page(
			\__( 'Smartlook', 'wpsmartlook' ),
			\__( 'Smartlook', 'wpsmartlook' ),
			'manage_options',
			'smartlook',
			array( $this, 'display_option_page' )
		);

	}

	/**
	 * Output the content of the settings page.
	 *
	 * @since 1.0.0
	 */
	public function display_option_page() {
	?>
		<div class="wrap">
			<h1><?php \_e( 'Smartlook Settings', 'wpsmartlook' ); ?></h1>
			<form action='options.php' method='post'>
			<?php
				\settings_fields( $this->get_settings_name() );
				\do_settings_sections( $this->get_settings_name() );
				\submit_button();
			?>
			</form>
		</div>
	<?php
	}

	/**
	 * Register groups of settings and their fields.
	 *
	 * @since 1.0.0
	 */
	public function admin_settings_init() {
		$this->register_settings_sections();
		$this->register_settings_fields();
	}

	/**
	 * Register groups of settings.
	 *
	 * @since 1.0.0
	 */
	public function register_settings_sections() {

		\add_settings_section(
			'smartlook_settings_section',
			'',
			null,
			$this->get_settings_name()
		);

	}

	/**
	 * Register settings fields.
	 *
	 * @since 1.0.0
	 */
	public function register_settings_fields() {
		$this->register_snippet_field();
	}

	/**
	 * Register the snippet field.
	 *
	 * @since 1.0.0
	 */
	public function register_snippet_field() {

		\register_setting(
			$this->get_settings_name(),
			'smartlook_snippet',
			''
		);

		\add_settings_field(
			'smartlook_snippet',
			\__( 'Snippet Code', 'wpsmartlook' ),
			array( $this, 'display_snippet_field' ),
			$this->get_settings_name(),
			'smartlook_settings_section',
			array(
				'label_for' => 'smartlook_snippet',
			)
		);

	}

	/**
	 * Output the snippet field.
	 *
	 * @since 1.0.0
	 */
	public function display_snippet_field() {

		printf(
			'<textarea rows="10" id="%1$s" name="%1$s" class="widefat" style="font-family: Courier New;">%2$s</textarea>',
			'smartlook_snippet',
			\get_option( 'smartlook_snippet' )
		);

		printf(
			'<p class="description">%s</p>',
			\__( 'This code is going to be embedded into your website between tags &lt;head&gt; and &lt;/head&gt;', 'wpsmartlook' )
		);

	}
}

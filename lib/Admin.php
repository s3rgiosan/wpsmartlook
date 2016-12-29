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
			array( $this, 'display_options_page' )
		);

	}

	/**
	 * Output the content of the settings page.
	 *
	 * @since 1.0.0
	 */
	public function display_options_page() {
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

	/**
	 * Register settings.
	 *
	 * @since 1.1.0
	 */
	public function register_settings() {

		if ( ! \current_user_can( 'edit_posts' ) ) {
			return;
		}

		$post_types = \wp_cache_get( 'wpsmartlook_post_types', $this->plugin->get_name() );

		if ( ! $post_types ) {

			$post_types = \get_post_types( array( 'public' => true ) );

			/**
			 * Filter the available post type(s).
			 *
			 * @see https://codex.wordpress.org/Post_Type
			 * @see https://codex.wordpress.org/Post_Types#Custom_Types
			 *
			 * @since  1.0.0
			 * @param  array Name(s) of the post type(s).
			 * @return array Possibly-modified name(s) of the post type(s).
			 */
			$post_types = \apply_filters( 'wpsmartlook_post_types', \get_post_types( array(
				'public' => true,
			) ) );

			\wp_cache_set( 'wpsmartlook_post_types', $post_types, $this->plugin->get_name(), 600 );
		}

		foreach ( $post_types as $post_type ) {
			\add_meta_box(
				'wpsmartlook_settings',
				\__( 'Smartlook Settings', 'wpsmartlook' ),
				array( $this, 'display_settings' ),
				$post_type
			);
		}
	}

	/**
	 * Output the settings meta box.
	 *
	 * @since 1.1.0
	 * @param \WP_Post $post Current post object.
	 */
	public function display_settings( $post ) {

		\wp_nonce_field( \plugin_basename( __FILE__ ), 'smartlook_settings_meta_box_nonce' );

		echo '<table class="form-table"><tbody>';
		$this->display_disable_fields( $post );
		echo '</tbody></table>';
	}

	/**
	 * Output the disable fields.
	 *
	 * @since 1.1.0
	 * @param \WP_Post $post Current post object.
	 */
	public function display_disable_fields( $post ) {
		echo '<tr>';
		printf(
			'<th scope="row"><label for="%s">%s:</label></th>',
			\esc_attr( 'smartlook_disable_rec' ),
			\__( 'Disable Recording', 'wpsmartlook' )
		);

		printf(
			'<td><input type="checkbox" id="%1$s" name="%1$s" value="1"%2$s></td>',
			'smartlook_disable_rec',
			\checked( \get_post_meta( $post->ID, 'smartlook_disable_rec', true ), 1, false )
		);
		echo '</tr>';
	}

	/**
	 * Save settings.
	 *
	 * @since 1.1.0
	 * @param int $post_id The post ID.
	 */
	public function save_settings( $post_id ) {

		// Verify meta box nonce
		if ( ! isset( $_POST['smartlook_settings_meta_box_nonce'] ) ||
			! \wp_verify_nonce( $_POST['smartlook_settings_meta_box_nonce'], \plugin_basename( __FILE__ ) ) ) {
			return;
		}

		// Bail out if post is an autosave
		if ( \wp_is_post_autosave( $post_id ) ) {
			return;
		}

		// Bail out if post is a revision
		if ( \wp_is_post_revision( $post_id ) ) {
			return;
		}

		// Bail out if current user can't edit posts
		if ( ! \current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Update/delete the analytics tag
		if ( ! empty( $_POST['smartlook_disable_rec'] ) ) {
			\update_post_meta( $post_id, 'smartlook_disable_rec', boolval( $_POST['smartlook_disable_rec'] ) );
		} else {
			\delete_post_meta( $post_id, 'smartlook_disable_rec' );
		}
	}
}

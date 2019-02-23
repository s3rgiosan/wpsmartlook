<?php

namespace s3rgiosan\WP\Plugin\Smartlook;

/**
 * The dashboard-specific functionality of the plugin
 *
 * @since   1.0.0
 */
class Admin {

	/**
	 * The unique identifier of this plugin settings group name.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected $settings_name = 'smartlook_settings';

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
	 * The settings group name.
	 *
	 * @since  1.0.0
	 * @return string The settings group name.
	 */
	public function get_settings_name() {
		return $this->settings_name;
	}

	/**
	 * Register hooks.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		\add_action( 'admin_menu', [ $this, 'admin_settings_menu' ] );
		\add_action( 'admin_init', [ $this, 'admin_settings_init' ] );
		\add_action( 'add_meta_boxes', [ $this, 'register_settings' ] );
		\add_action( 'save_post', [ $this, 'save_settings' ] );
		\add_filter( 'plugin_action_links_' . WPSMARTLOOK_PLUGIN_FILE, [ $this, 'add_action_links' ], 90, 1 );
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
			[
				$this,
				'display_options_page',
			]
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
			[ $this, 'display_snippet_field' ],
			$this->get_settings_name(),
			'smartlook_settings_section',
			[
				'label_for' => 'smartlook_snippet',
			]
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

			$post_types = \get_post_types( [ 'public' => true ] );

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
			$post_types = \apply_filters(
				'wpsmartlook_post_types',
				\get_post_types(
					[
						'public' => true,
					]
				)
			);

			\wp_cache_set( 'wpsmartlook_post_types', $post_types, $this->plugin->get_name(), 600 );
		}

		foreach ( $post_types as $post_type ) {
			\add_meta_box(
				'wpsmartlook_settings',
				\__( 'Smartlook', 'wpsmartlook' ),
				[ $this, 'display_settings' ],
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

		\wp_nonce_field( $this->plugin->get_name(), 'smartlook_settings_meta_box_nonce' );

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
		printf(
			'<tr>
				<td>
					<input type="checkbox" id="%1$s" name="%1$s" value="1"%2$s>
					<span class="description">%3$s</span>
				</td>
			</tr>',
			'smartlook_disable_rec',
			\esc_attr( \checked( \get_post_meta( $post->ID, 'smartlook_disable_rec', true ), 1, false ) ),
			sprintf(
				\esc_html__( 'Check to disable Smartlook on this %s.', 'wpsmartlook' ),
				\esc_html( \get_post_type_object( $post->post_type )->labels->singular_name )
			)
		);
	}

	/**
	 * Save settings.
	 *
	 * @since 1.1.0
	 * @param int $post_id The post ID.
	 */
	public function save_settings( $post_id ) {

		// Verify meta box nonce.
		if (
			! isset( $_POST['smartlook_settings_meta_box_nonce'] )
			|| ! \wp_verify_nonce( $_POST['smartlook_settings_meta_box_nonce'], $this->plugin->get_name() ) ) {
			return;
		}

		// Bail out if post is an autosave.
		if ( \wp_is_post_autosave( $post_id ) ) {
			return;
		}

		// Bail out if post is a revision.
		if ( \wp_is_post_revision( $post_id ) ) {
			return;
		}

		// Bail out if current user can't edit posts.
		if ( ! \current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Update/delete the analytics tag.
		if ( ! empty( $_POST['smartlook_disable_rec'] ) ) {
			\update_post_meta( $post_id, 'smartlook_disable_rec', boolval( $_POST['smartlook_disable_rec'] ) );
		} else {
			\delete_post_meta( $post_id, 'smartlook_disable_rec' );
		}
	}

	/**
	 * Add action links.
	 *
	 * @since  1.2.0
	 * @param  array $actions An array of plugin action links.
	 * @return array Possibly-modified action links.
	 */
	public function add_action_links( $links ) {

		$plugin_links = [
			sprintf(
				'<a href="%s">%s</a>',
				\esc_url( \admin_url( 'options-general.php?page=smartlook' ) ),
				\esc_html__( 'Settings', 'wpsmartlook' )
			),
		];

		return array_merge( $links, $plugin_links );
	}
}

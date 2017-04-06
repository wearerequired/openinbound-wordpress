<?php
/**
 * @package Required\OpenInbound
 */

namespace Required\OpenInbound;

use OI;

class Plugin {

	/**
	 * Registers all the needed hooks.
	 */
	public function init() {
		// General & Admin.
		add_action( 'init', [ $this, 'load_textdomain' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
		add_action( 'admin_menu', [ $this, 'register_admin_menu' ] );

		// Contact Form 7 tracking
		add_action( 'wpcf7_before_send_mail', [ $this, 'track_contact_form7' ] );
	}

	/**
	 * Load the plugin's translations.
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'openinbound', false, basename( dirname( __DIR__ ) ) . '/languages' );
	}

	/**
	 * Registers setting and settings fields.
	 *
	 * @uses register_setting()
	 *
	 * @uses add_settings_section()
	 *
	 * @uses add_settings_field()
	 */
	public function register_settings() {
		/**
		 * Register a new section on the "openinbound" page.
		 */
		add_settings_section(
			'openinbound_keys',
			__( 'API Key &amp; Tracking ID for your website', 'openinbound' ),
			[ $this, 'section_cb' ],
			'openinbound'
		);

		/**
		 * Register new settings field in the "openinbound_keys" section on the "openinbound" page.
		 */
		add_settings_field(
			'openinbound_api_key',
			__( 'API Key', 'openinbound' ),
			[ $this, 'field_cb' ],
			'openinbound',
			'openinbound_keys',
			[
				'label_for' => 'openinbound_api_key',
				'class'     => 'openinbound_row',
			]
		);

		/**
		 * Register new settings field in the "openinbound_keys" section on the "openinbound" page.
		 */
		add_settings_field(
			'openinbound_tracking_id',
			__( 'Tracking ID', 'openinbound' ),
			[ $this, 'field_cb' ],
			'openinbound',
			'openinbound_keys',
			[
				'label_for' => 'openinbound_tracking_id',
				'class'     => 'openinbound_row',
			]
		);

		/**
		 * Register settings for the "openinbound" page.
		 */
		register_setting( 'openinbound', 'openinbound_tracking_id', 'esc_attr' );
		register_setting( 'openinbound', 'openinbound_api_key', 'esc_attr' );
	}

	/**
	 * Adding a custom admin page.
	 */
	public function register_admin_menu() {
		add_menu_page(
			__( 'OpenInbound Settings', 'openinbound' ),
			__( 'OpenInbound', 'openinbound' ),
			'manage_options',
			'openinbound',
			[ $this, 'render_admin_page' ],
			'dashicons-format-status',
			75
		);
	}

	public function render_admin_page() {
		/**
		 * Check user capabilities.
		 */
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		/**
		 * Check whether the settings are saved properly.
		 */
		if ( isset( $_GET['settings-updated'] ) ) {
			add_settings_error(
				'openinbound_messages',
				'openinbound_message',
				__( 'Settings Saved! Enjoy data flowing into OpenInbound.', 'openinbound' ),
				'updated'
			);
		}

		/**
		 * Show setting errors/messages.
		 */
		settings_errors( 'openinbound_messages' );
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
				<?php
				/**
				 * Output security fields for the registered setting "openinbound".
				 */
				settings_fields( 'openinbound' );
				/**
				 * Render setting sections and their fields.
				 */
				do_settings_sections( 'openinbound' );
				/**
				 * Output a save button.
				 */
				submit_button( __( 'Save Settings', 'openinbound' ) );
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Callback when rendering the section on the "openinbound" page.
	 *
	 * @param array $args setting args.
	 */
	public function section_cb( $args ) {
		?>
		<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Get your API Key and Tracking ID from the OpenInbound.com Settings page.', 'openinbound' ); ?></p>
		<?php
	}

	/**
	 * Renders the input fields for the settings.
	 *
	 * @param array $args for this field.
	 */
	public function field_cb( $args ) {
		if ( ! isset( $args['label_for'] ) ) {
			return;
		}
		$option = get_option( $args['label_for'] );
		?>
		<input class="regular-text code" id="<?php echo esc_attr( $args['label_for'] ); ?>" value="<?php esc_attr_e( $option ); ?>" name="<?php echo esc_attr( $args['label_for'] ); ?>" type="text" />
		<?php
	}

	public function track_contact_form7( $form ) {
		/*

     // Demo code from Drupal
    if (valid_email_address($component_data['e_mail'][0])) {
        $data['email'] = $component_data['e_mail'][0];
    }
    if (valid_email_address($component_data['email'][0])) {
        $data['email'] = $component_data['email'][0];
    }
    if ($component_data['company_name'][0]) {
        $data['company_name'] = $component_data['company_name'][0];
    }
    if ($component_data['company'][0]) {
        $data['company_name'] = $component_data['company'][0];
    }
    if ($component_data['phone'][0]) {
        $data['phone'] = $component_data['phone'][0];
    }
    if ($component_data['first_name'][0]) {
        $data['first_name'] = $component_data['first_name'][0];
    }
    if ($component_data['last_name'][0]) {
        $data['last_name'] = $component_data['last_name'][0];
    }


    $oi = new OI(variable_get('openinbound_tracking_id'), variable_get('openinbound_api_key'));
    $oi->updateContact($_COOKIE['_oi_contact_id'], $data);

    $properties = array();
    $properties['title'] = 'Form submission by '.$data['email'].' - '.$node->title;
    $properties['event_type'] = 'submission';
    $properties['raw'] = json_encode($component_data);
    $oi->addEvent($_COOKIE['_oi_contact_id'], $properties);
    */
	}
}

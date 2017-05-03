<?php
/**
 * @package Required\OpenInbound
 */

namespace Required\OpenInbound;

use OI;

class Plugin {
	/**
	 * URL to the tracking script.
	 */
	const OI_TRACKER_URL = '//dims-api.netnode.ch/tracker.js';

	protected $oi_tracking_id_meta = 'openinbound_tracking_id';

	protected $oi_api_key_meta = 'openinbound_api_key';

	/**
	 * Registers all the needed hooks.
	 */
	public function init() {
		// General & Admin.
		add_action( 'init', [ $this, 'load_textdomain' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
		add_action( 'admin_menu', [ $this, 'register_admin_menu' ] );
		add_action( 'plugin_action_links_openinbound-wordpress/openinbound-wordpress.php', [ $this, 'plugin_action_links' ] );

		// Add tracker script to front-end.
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_filter( 'script_loader_tag', [ $this, 'add_async_attr' ], 10, 2 );

		// Contact Form 7 tracking
		add_action( 'wpcf7_before_send_mail', [ $this, 'track_contact_form7' ] );
	}

	/**
	 * Load the plugin's translations.
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'openinbound' );
	}

	public function plugin_action_links( $links ) {
		$links[] = '<a href="' . admin_url( 'options-general.php?page=openinbound' ) . '">' . __( 'Settings', 'openinbound' ) . '</a>';
		return $links;
	}

	/**
	 * Add the OI tracker.js script to the front-end.
	 */
	public function enqueue_scripts() {
		// Get the tracking_id option.
		$tracking_id = get_option( $this->oi_tracking_id_meta, false );

		// Don't register the script when no tracking_id is set.
		if ( false === $tracking_id || empty( $tracking_id ) ) {
			return;
		}

		/**
		 * Add ?tracking_id=<tracking_id> to the tracker.js URL.
		 */
		$tracker_url = add_query_arg( 'tracking_id', trim( $tracking_id ), self::OI_TRACKER_URL );

		wp_enqueue_script( 'openinbound', $tracker_url, null, '1.0.0', true );
		wp_script_add_data( 'openinbound', 'async', true );
	}

	/**
	 * Add async loading to the script.
	 *
	 * @param string $tag    script tag.
	 * @param string $handle script name.
	 *
	 * @return string mixed script tag with async option.
	 */
	public function add_async_attr( $tag, $handle ) {
		if ( wp_scripts()->get_data( $handle, 'async' ) ) {
			$tag = str_replace( '></', ' async></', $tag );
		}

		return $tag;
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
			[ $this, 'keys_section_cb' ],
			'openinbound'
		);

		/**
		 * Register new settings field in the "openinbound_keys" section on the "openinbound" page.
		 */
		add_settings_field(
			$this->oi_api_key_meta,
			__( 'API Key', 'openinbound' ),
			[ $this, 'field_cb' ],
			'openinbound',
			'openinbound_keys',
			[
				'label_for' => $this->oi_api_key_meta,
				'class'     => 'openinbound_row',
			]
		);

		/**
		 * Register new settings field in the "openinbound_keys" section on the "openinbound" page.
		 */
		add_settings_field(
			$this->oi_tracking_id_meta,
			__( 'Tracking ID', 'openinbound' ),
			[ $this, 'field_cb' ],
			'openinbound',
			'openinbound_keys',
			[
				'label_for' => $this->oi_tracking_id_meta,
				'class'     => 'openinbound_row',
			]
		);

		/**
		 * Register settings for the "openinbound" page.
		 */
		register_setting( 'openinbound', $this->oi_tracking_id_meta, 'sanitize_text_field' );
		register_setting( 'openinbound', $this->oi_api_key_meta, 'sanitize_text_field' );
	}

	/**
	 * Adding a custom admin page.
	 */
	public function register_admin_menu() {
		add_options_page(
			__( 'OpenInbound Settings', 'openinbound' ),
			__( 'OpenInbound', 'openinbound' ),
			'manage_options',
			'openinbound',
			[ $this, 'render_admin_page' ]
		);
	}

	public function render_admin_page() {
		/**
		 * Check user capabilities.
		 */
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
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
	public function keys_section_cb( $args ) {
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

		$option = get_option( $args['label_for'], '' );
		?>
		<input class="regular-text code" id="<?php echo esc_attr( $args['label_for'] ); ?>" value="<?php echo esc_attr( $option ); ?>" name="<?php echo esc_attr( $args['label_for'] ); ?>" type="text" />
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

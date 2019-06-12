<?php
namespace sv_100;

/**
 * @version         1.00
 * @author			straightvisions
 * @package			sv_100
 * @copyright		2019 straightvisions GmbH
 * @link			https://straightvisions.com
 * @since			1.0
 * @license			See license.txt or https://straightvisions.com
 */

class sv_settings extends init {
	public function __construct() {
	
	}

	public function init() {
		// Translates the module
		load_theme_textdomain( 'sv_settings', $this->get_path( 'languages' ) );

		// Module Info
		$this->set_module_title( 'SV Settings' );
		$this->set_module_desc( __( 'Import and export your settings.', 'sv_settings' ) );

		// Section Info
		$this->set_section_title( __( 'Settings Import/Export', 'sv_settings' ) );
		$this->set_section_desc( __( 'Import and export your settings', 'sv_settings' ) );
		$this->set_section_type( 'tools' );
		$this->get_root()->add_section( $this )
			 ->set_section_template_path( $this->get_path( 'lib/backend/tpl/tools.php' ) );

		// Loads Settings
		$this->check_first_load()->register_scripts();
		
		// Action Hooks
		add_action( 'wp_ajax_' . $this->get_prefix( 'export' ) , array( $this, 'settings_export' ) );
	}
	
	protected function check_first_load(): sv_settings {
		if ( $this->is_first_load() ) {
			$this->settings_import( file_get_contents( $this->get_path( 'lib/backend/settings/default.json' ) ) );
		}

		return $this;
	}
	
	protected function register_scripts(): sv_settings {
		// Register Styles
		$this->scripts_queue['tools']			= static::$scripts
			->create( $this )
			->set_ID( 'tools' )
			->set_path( 'lib/backend/css/tools.css' )
			->set_inline( true )
			->set_is_backend()
			->set_is_enqueued();
		
		return $this;
	}
	
	public function settings_export() {
		$file['name']	= $this->get_prefix( 'export_' . current_time( 'YmdHis' ) . '.json' );
		$file['data']	= wp_json_encode( $this->get_modules_settings() );
		
		header('Content-Type: application/json');
		header('Content-Disposition: attachment;filename="' . $file['name'] . '"');
		
		echo $file['data'];
		
		wp_die();
	}
	
	protected function settings_import( string $json_data ) {
		$data = json_decode( $json_data, true );
		
		foreach ( $data as $prefix => $settings ) {
			if ( ! empty( $settings ) ) {
				foreach ( $settings as $setting => $value ) {
					if ( ! empty( $value ) ) {
						$option = $prefix . '_settings_' . $setting;
						
						update_option( $option, $value, true );
					}
				}
			}
		}
	}
}
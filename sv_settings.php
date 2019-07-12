<?php
	namespace sv100;
	
	/**
	 * @version         4.000
	 * @author			straightvisions GmbH
	 * @package			sv100
	 * @copyright		2019 straightvisions GmbH
	 * @link			https://straightvisions.com
	 * @since			1.000
	 * @license			See license.txt or https://straightvisions.com
	 */
	
	class sv_settings extends init {
		public function init() {
			// Module Info
			$this->set_module_title( 'SV Settings' );
			$this->set_module_desc( __( 'Import and export your settings.', 'sv100' ) );
	
			// Section Info
			$this->set_section_title( __( 'Settings Import/Export', 'sv100' ) );
			$this->set_section_desc( __( 'Import and export your settings', 'sv100' ) );
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
			$this->scripts_queue['tools'] =
				static::$scripts
					->create( $this )
					->set_ID( 'tools' )
					->set_path( 'lib/backend/css/tools.css' )
					->set_inline( true )
					->set_is_backend()
					->set_is_enqueued();
			
			return $this;
		}
		
		public function settings_export() {
			$filename								= $this->get_prefix( 'export_' . current_time( 'YmdHis' ) . '.json' );
			$settings								= $this->get_modules_settings();
			$settings[ 'sv100_scripts_settings' ]	= $this->get_scripts_settings();
			
			foreach ( $settings as $name => $value ) {
				$settings[ $name ] = array_filter( $value );
			}
			
			header( 'Content-Type: application/json');
			header( 'Content-Disposition: attachment;filename="' . $filename . '"' );
			
			echo wp_json_encode( $settings );
			
			wp_die();
		}
		
		protected function settings_import( string $json_data ) {
			$data = json_decode( $json_data, true );
			
			// Deletes all options that starts with "sv100_sv_" or "sv100_scripts_settings_"
			// @todo: check if this can be removed
			foreach ( wp_load_alloptions() as $option => $value ) {
				if ( strpos( $option, 'sv100_sv_' ) === 0 || strpos( $option, '0_settings_sv100' ) === 0 ) {
					delete_option( $option );
				}
			}
			
			// @todo: implement nonce-check
			
			// Sets all new options
			foreach ( $data as $name => $settings ) {
				// Module Settings
				
				foreach ( $settings as $setting => $value ) {
					$option = $setting;
					
					if ( $name !== 'sv100_scripts_settings' ) {
						$option = $name . '_settings_' . $setting;
					}

					update_option( $option, $value, true );
				}
			}
		}
	}
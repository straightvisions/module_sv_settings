<?php
	namespace sv100_companion;
	
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
			$this
				 ->set_section_title( __( 'SV100 Settings Import/Export', 'sv100_companion' ) )
				 ->set_section_desc( __( 'Import and export SV100 Theme settings', 'sv100_companion' ) )
				 ->set_section_type( 'tools' )
				->set_section_template_path( $this->get_path( 'lib/backend/tpl/tools.php' ) )
				->register_scripts()
				 ->get_root()
				 ->add_section( $this );
			
			// Action Hooks
			add_action( 'wp_ajax_' . $this->get_prefix( 'export' ) , array( $this, 'settings_export' ) );
			add_action( 'wp_ajax_' . $this->get_prefix( 'reset' ), array( $this, 'settings_reset' ) );
		}
		
		protected function register_scripts(): sv_settings {
			// Register Styles
			$this->get_script( 'tools' )
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

			header( 'Content-Type: application/json' );
			header( 'Content-Disposition: attachment;filename="' . $filename . '"' );
			
			echo wp_json_encode( $settings );
			
			wp_die();
		}
		
		public function settings_reset() {
			if ( ! check_ajax_referer( $this->get_prefix( 'reset' ), 'nonce' ) ) return false;

			$this->delete_options();
			
			echo json_encode( array(
				'notice'	=> true,
				'msg' 		=> __( 'Successfully reseted all settings.', 'sv100_companion' ),
				'type'		=> 'success',
			));

			wp_die();
		}
		
		protected function settings_import( string $json_data ) {
			$data = json_decode( $json_data, true );
			
			$this->delete_options();
			
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
		
		private function delete_options() {
			foreach ( wp_load_alloptions() as $option => $value ) {
				if ( strpos( $option, 'sv100_sv_' ) === 0 || strpos( $option, '0_settings_sv100' ) === 0 ) {
					delete_option( $option );
				}
			}
		}
	}
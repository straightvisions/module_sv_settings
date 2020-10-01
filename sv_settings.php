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

			add_action('admin_init', array($this, 'settings_import'));

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
			$settings								= $this->get_instance('sv100')->get_modules_settings();
			$settings[ 'sv100_scripts_settings' ]	= $this->get_instance('sv100')->get_scripts_settings();

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

		public function settings_import(): sv_settings {
			global $wp_filesystem;

			if (!isset( $_GET[ $this->get_prefix( 'import' ) ] )){
				return $this;
			}
			if (!\wp_verify_nonce( $_GET[ $this->get_prefix( 'import' ) ], $this->get_prefix( 'import' ))){
				echo '<div class="notice notice-error is-dismissible">'.__('Invalid Nonce', 'sv100_companion').'</div>';
				return $this;
			}

			if (!isset( $_FILES[ $this->get_prefix( 'import_file' ) ] )) {
				echo '<div class="notice notice-error is-dismissible">'.__('No File', 'sv100_companion').'</div>';
				return $this;
			}


			require_once ( ABSPATH . '/wp-admin/includes/file.php' );
			\WP_Filesystem();

			// settings uploaded?
			if ( !$wp_filesystem->exists( $_FILES[ $this->get_prefix( 'import_file' ) ]['tmp_name'] ) ) {
				echo '<div class="notice notice-error is-dismissible">'.__('No File', 'sv100_companion').'</div>';
				return $this;
			}

			$data = json_decode( $wp_filesystem->get_contents( $_FILES[ $this->get_prefix( 'import_file' ) ]['tmp_name'] ), true );

			if(!$data){
				echo '<div class="notice notice-error is-dismissible">'.__('Settings File corrupt', 'sv100_companion').'</div>';
				return $this;
			}

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

			echo '<div class="notice notice-success is-dismissible">'.__('Settings imported.', 'sv100_companion').'</div>';
			return $this;
		}

		private function delete_options() {
			foreach ( wp_load_alloptions() as $option => $value ) {
				if ( strpos( $option, 'sv100_sv_' ) === 0) {
					delete_option( $option );
				}
			}
		}
	}
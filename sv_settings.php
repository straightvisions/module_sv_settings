<?php
	namespace sv100_companion;

	class sv_settings extends init {
		public function init() {
			$this
				->set_section_title( __( 'SV Settings Import/Export', 'sv100_companion' ) )
				->set_section_desc( __( 'Import and export settings from SV theme and plugins', 'sv100_companion' ) )
				->set_section_type( 'tools' )
				->set_section_template_path( $this->get_path( 'lib/backend/tpl/tools.php' ) )
				->register_scripts()
				->get_root()
				->add_section( $this );

			// Action Hooks
			add_action( 'wp_ajax_' . $this->get_prefix( 'export' ) , array( $this, 'settings_export' ) );
			add_action( 'wp_ajax_' . $this->get_prefix( 'reset' ), array( $this, 'settings_reset' ) );
			add_action( 'wp_ajax_' . $this->get_prefix( 'import' ), array( $this, 'settings_import' ) );
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

		public function settings_import() {
			// Verify the AJAX nonce
			if ( ! check_ajax_referer( $this->get_prefix( 'import' ), 'nonce', false ) ) {
				wp_send_json_error( array(
					'notice' => true,
					'msg'    => __( 'Invalid nonce. Request not allowed.', 'sv100_companion' ),
					'type'   => 'error',
				) );
				wp_die();
			}

			// Check if the 'data' key exists in the POST request
			if ( ! isset( $_POST['data'] ) ) {
				wp_send_json_error( array(
					'notice' => true,
					'msg'    => __( 'No settings data provided.', 'sv100_companion' ),
					'type'   => 'error',
				) );
				wp_die();
			}

			// Decode the JSON data from the 'data' key
			$data = json_decode( stripslashes_deep( $_POST['data'] ), true );

			if ( ! $data ) {
				wp_send_json_error( array(
					'notice' => true,
					'msg'    => __( 'Settings JSON corrupt.', 'sv100_companion' ),
					'type'   => 'error',
				) );
				wp_die();
			}

			// Optionally delete all current options (uncomment if required)
			// $this->delete_options();

			// Iterate over the settings data and update options
			foreach ( $data as $option_id => $option_value ) {
				update_option( $option_id, $option_value, true );
			}

			// Clear cache or any related processes
			$this->get_script()->clear_cache();

			// Respond with success message
			wp_send_json_success( array(
				'notice' => true,
				'msg'    => __( 'Settings imported successfully.', 'sv100_companion' ),
				'type'   => 'success',
			) );

			wp_die();
		}

		private function delete_options() {
			foreach ( wp_load_alloptions() as $option => $value ) {
				if ( strpos( $option, 'sv100_sv_' ) === 0) {
					delete_option( $option );
				}
			}
		}
	}
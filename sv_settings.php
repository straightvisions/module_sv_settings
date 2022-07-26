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
			if(!isset($_POST[ $this->get_prefix( 'all' ) ])){
				return $this;
			}

			$data = json_decode( stripslashes_deep($_POST[ $this->get_prefix( 'all' ) ]), true );

			if(!$data){
				echo '<div class="notice notice-error is-dismissible">'.__('Settings JSON corrupt', 'sv100_companion').'</div>';
				return $this;
			}

			//$this->delete_options();

			// Sets all new options
			foreach ( $data as $option_id => $option_value ) {
				update_option( $option_id, $option_value, true );
			}

			$this->get_script()->clear_cache();

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
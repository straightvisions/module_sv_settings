<?php
	if (
		isset( $_GET[ $this->get_module( 'sv_settings' )->get_prefix( 'import' ) ] )
		&& wp_verify_nonce( $_GET[ $this->get_module( 'sv_settings' )->get_prefix( 'import' ) ], $this->get_module( 'sv_settings' )->get_prefix( 'import' ) )
	) {
		$file = $_FILES[ $this->get_module( 'sv_settings' )->get_prefix( 'import_file' ) ];
		
		if ( $file['size'] > 0 ) {
			$data = file_get_contents( $file['tmp_name'] );
			
			$this->get_module( 'sv_settings' )->settings_import( $data );
		}
	}
	
	$import_url = wp_nonce_url(
		admin_url() . 'themes.php?page=sv100#section_sv100_sv_settings',
		$this->get_module( 'sv_settings' )->get_prefix( 'import' ),
		$this->get_module( 'sv_settings' )->get_prefix( 'import' ) );
	$export_url = admin_url( 'admin-ajax.php' ) . '?action=' . $this->get_module( 'sv_settings' )->get_prefix( 'export' )
				  . '&' . $this->get_module( 'sv_settings' )->get_prefix( 'export_nonce' ) . '=' . wp_create_nonce( $this->get_module( 'sv_settings' )->get_prefix( 'export' ) );
	
	$import_modal = array(
		'title' => __( 'Import Settings', 'sv100' ),
		'desc'	=> __( 'All your settings will be removed and replaced with the new settings.', 'sv100' ) . '<br>' .
					 __( 'Do you want to proceed?', 'sv100' ),
		'type'	=> 'confirm'
	);
	
	$reset_modal = array(
		'title' => __( 'Reset Settings', 'sv100' ),
		'desc'	=> __( 'All your settings will be removed and replaced with the default settings.', 'sv100' ) . '<br>' .
					 __( 'Do you want to proceed?', 'sv100' ),
		'type'	=> 'confirm'
	);
?>

<div class="<?php echo $this->get_module( 'sv_settings' )->get_prefix(); ?>">
	<form method="post" action="<?php echo $import_url; ?>" enctype="multipart/form-data" id="<?php echo $this->get_module( 'sv_settings' )->get_prefix( 'import' ); ?>">
		<?php wp_nonce_field( $this->get_module( 'sv_settings' )->get_prefix( 'import' ), $this->get_module( 'sv_settings' )->get_prefix( 'import' ) ); ?>
		<div class="sv_setting <?php echo $this->get_module( 'sv_settings' )->get_prefix( 'import' ); ?>">
			<h4><?php _e( 'Import', 'sv100' ); ?></h4>
			<div class="description">
				<?php
					echo __( 'Select your settings file and click on "Import Settings".', 'sv100' ) . '<br>' .
						 __( 'Allowed filetypes: .json', 'sv100' );
				?>
			</div>
			<label for="<?php echo $this->get_module( 'sv_settings' )->get_prefix( 'import_file' ); ?>">
				<input data-sv_type="sv_form_field"
					   class="sv_file"
					   name="<?php echo $this->get_module( 'sv_settings' )->get_prefix( 'import_file' ); ?>"
					   type="file"
					   accept=".json"
					   placeholder="<?php _e( 'Settings file', 'sv100' ); ?>"
				>
				<button class="button"
						type="button"
						data-sv_admin_modal='[{"title":"<?php echo $import_modal['title']; ?>","desc":"<?php echo $import_modal['desc']; ?>","type":"<?php echo $import_modal['type']; ?>","args":{"form":"<?php echo $this->get_module( 'sv_settings' )->get_prefix( 'import' ); ?>"}}]'
				>
					<?php _e( 'Import Settings', 'sv100' ); ?>
				</button>
			</label>
		</div>
	</form>

	<div class="sv_setting <?php echo $this->get_module( 'sv_settings' )->get_prefix( 'export' ); ?>">
		<h4><?php _e( 'Export', 'sv100' ); ?></h4>
		<label for="<?php echo $this->get_module( 'sv_settings' )->get_prefix( 'export' ); ?>">
			<button
					class="button"
					data-sv_admin_ajax='[{"action":"<?php echo $this->get_module( 'sv_settings' )->get_prefix( 'export' ); ?>"}]'
			>
				<?php _e( 'Export Settings', 'sv100' ); ?>
			</button>
		</label>
	</div>
	<div class="sv_setting <?php echo $this->get_module( 'sv_settings' )->get_prefix( 'reset' ); ?>">
		<h4><?php _e( 'Reset to factory settings', 'sv100' ); ?></h4>
		<div class="description">
			<?php _e( 'All your settings will be removed and replaced with the default settings.', 'sv100' ); ?>
		</div>
		<label for="<?php echo $this->get_module( 'sv_settings' )->get_prefix( 'reset' ); ?>">
			<button
					class="button"
					data-sv_admin_modal='[{"title":"<?php echo $reset_modal['title']; ?>","desc":"<?php echo $reset_modal['desc']; ?>","type":"<?php echo $reset_modal['type']; ?>"}]'
					data-sv_admin_ajax='[{"action":"<?php echo $this->get_module( 'sv_settings' )->get_prefix( 'reset' ); ?>"}]'
			>
				<?php _e( 'Reset Settings', 'sv100' ); ?>
			</button>
		</label>
	</div>
</div>
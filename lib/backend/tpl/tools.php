<?php
	if ( isset( $_POST[ $this->get_module( 'sv_settings' )->get_prefix( 'import' ) ] ) ) {
		$file = $_FILES[ $this->get_module( 'sv_settings' )->get_prefix( 'import_file' ) ];

		if ( $file['size'] > 0 ) {
			$data = file_get_contents( $file['tmp_name'] );
			
			$this->get_module( 'sv_settings' )->settings_import( $data );
		}
	}
?>

<div class="<?php echo $this->get_module( 'sv_settings' )->get_prefix(); ?>">
	<form method="post" action="" enctype="multipart/form-data">
		<div class="sv_setting <?php echo $this->get_module( 'sv_settings' )->get_prefix( 'import' ); ?>">
			<h4><?php _e( 'Import', 'sv100' ); ?></h4>
			<div class="description"><?php _e( 'Select your settings file and click on "Import Settings".<br>Allowed filetypes: .json', 'sv100' ); ?></div>
			<label for="<?php echo $this->get_module( 'sv_settings' )->get_prefix( 'import_file' ); ?>">
				<input data-sv_type="sv_form_field"
					   class="sv_file"
					   name="<?php echo $this->get_module( 'sv_settings' )->get_prefix( 'import_file' ); ?>"
					   type="file"
					   accept=".json"
					   placeholder="<?php _e( 'Settings file', 'sv100' ); ?>"
				>
				<button name="<?php echo $this->get_module( 'sv_settings' )->get_prefix( 'import' ); ?>" class="button">
					<?php _e( 'Import Settings', 'sv100' ); ?>
				</button>
			</label>
		</div>
	</form>

	<div class="sv_setting <?php echo $this->get_module( 'sv_settings' )->get_prefix( 'export' ); ?>">
		<h4><?php _e( 'Export', 'sv100' ); ?></h4>
		<label for="<?php echo $this->get_module( 'sv_settings' )->get_prefix( 'export' ); ?>">
			<a href="<?php echo admin_url( 'admin-ajax.php' ); ?>?action=sv100_sv_settings_export"
			   class="button">
				<?php _e( 'Export Settings', 'sv100' ); ?>
			</a>
		</label>
	</div>
</div>
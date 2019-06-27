<?php
	$sv_settings = $this->get_root()->sv_settings;
	
	if ( isset( $_POST[ $sv_settings->get_prefix( 'import' ) ] ) ) {
		$file = $_FILES[ $sv_settings->get_prefix( 'import_file' ) ];
		$data = file_get_contents( $file['tmp_name'] );
		
		$sv_settings->settings_import( $data );
	}
?>

<div class="<?php echo $sv_settings->get_prefix(); ?>">
	<form method="post" action="" enctype="multipart/form-data">
		<div class="sv_setting <?php echo $sv_settings->get_prefix( 'import' ); ?>">
			<h4><?php _e( 'Import', 'straightvisions-100' ); ?></h4>
			<div class="description"><?php _e( 'Select your settings file and click on "Import Settings".<br>Allowed filetypes: .json', 'straightvisions-100' ); ?></div>
			<label for="<?php echo $sv_settings->get_prefix( 'import_file' ); ?>">
				<input data-sv_type="sv_form_field"
					   class="sv_file"
					   name="<?php echo $sv_settings->get_prefix( 'import_file' ); ?>"
					   type="file"
					   accept=".json"
					   placeholder="<?php _e( 'Settings file', 'straightvisions-100' ); ?>"
				>
				<button name="<?php echo $sv_settings->get_prefix( 'import' ); ?>" class="button">
					<?php _e( 'Import Settings', 'straightvisions-100' ); ?>
				</button>
			</label>
		</div>
	</form>

	<div class="sv_setting <?php echo $sv_settings->get_prefix( 'export' ); ?>">
		<h4><?php _e( 'Export', 'straightvisions-100' ); ?></h4>
		<label for="<?php echo $sv_settings->get_prefix( 'export' ); ?>">
			<a href="https://lab.straightvisions.com/wp-admin/admin-ajax.php?action=sv_100_sv_settings_export"
			   class="button">
				<?php _e( 'Export Settings', 'straightvisions-100' ); ?>
			</a>
		</label>
	</div>
</div>
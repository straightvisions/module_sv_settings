<?php
	$module = $this->get_root()->sv_settings;
	
	if ( isset( $_POST[ $module->get_prefix( 'import' ) ] ) ) {
		$file = $_FILES[ $module->get_prefix( 'import_file' ) ];
		$data = json_decode( file_get_contents( $file['tmp_name'] ), true );
		
		foreach ( $data as $prefix => $settings ) {
			if ( ! empty( $settings ) ) {
				foreach ( $settings as $setting => $value ) {
					if ( ! empty( $value ) ) {
						$option = $prefix . '_settings_' . $setting;
						error_log($option);
						
						update_option( $option, $value, true );
					}
				}
			}
		}
	}
?>

<h3 class="divider"><?php _e('Export', $module->get_module_name() ); ?></h3>
<p><?php _e( 'Click on the button, to export your settings.', $this->get_root()->get_prefix() ); ?></p>
<a href="https://lab.straightvisions.com/wp-admin/admin-ajax.php?action=sv_100_sv_settings_export">
	<?php _e( 'Export Settings', $module->get_module_name() ); ?>
</a>
<div>
	<form method="post" action="" enctype="multipart/form-data">
		<input id="<?php echo $module->get_prefix( 'import_file' ); ?>"
			   name="<?php echo $module->get_prefix( 'import_file' ); ?>"
			   type="file"
			   accept=".json"
			   placeholder="<?php _e( 'Import Settings', $module->get_module_name() ); ?>"
		>

		<button name="<?php echo $module->get_prefix( 'import' ); ?>">
			<?php _e( 'Import Settings', $module->get_module_name() ); ?>
		</button>
	</form>
</div>
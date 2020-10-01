<?php
	// Nonce for import
	$import_url = wp_nonce_url(
		admin_url() . 'admin.php?page=sv100_companion#section_sv100_companion_modules_sv_settings',
		$module->get_prefix( 'import' ),
		$module->get_prefix( 'import' ) );

	// Modal for import
    $import_modal = wp_json_encode(
        array(
            'title' => __( 'Import settings', 'sv100_companion' ),
            'desc'	=> __( 'All your settings will be removed and replaced with the new settings.', 'sv100_companion' ) . '<br>' .
                         __( 'Do you want to proceed?', 'sv100_companion' ),
            'type'	=> 'confirm',
            'args'  => array(
                'form' => $module->get_prefix( 'import' )
            )
        )
    );

    // Nonce for export
	$export_url = admin_url( 'admin-ajax.php' ) . '?action=' . $module->get_prefix( 'export' )
				  . '&' . $module->get_prefix( 'export' ) . '=' . wp_create_nonce( $module->get_prefix( 'export' ) );

	// Ajax for reset
    $reset_ajax = wp_json_encode(
        array(
            'action'    => $module->get_prefix( 'reset' ),
            'nonce'     => wp_create_nonce( $module->get_prefix( 'reset' ) )
        )
    );

    // Modal for reset
	$reset_modal = wp_json_encode(
		array(
			'title' => __( 'Reset settings', 'sv100_companion' ),
			'desc'	=> __( 'All your settings will be removed and replaced with the default settings.', 'sv100_companion' ) . '<br>' .
			             __( 'Do you want to proceed?', 'sv100_companion' ),
			'type'	=> 'confirm'
        )
    );

?>

<div class="<?php echo $module->get_prefix(); ?>">
	<form method="post" action="<?php echo $import_url; ?>" enctype="multipart/form-data" id="<?php echo $module->get_prefix( 'import' ); ?>">
		<?php wp_nonce_field( $module->get_prefix( 'import' ), $module->get_prefix( 'import' ) ); ?>
		<div class="sv_setting <?php echo $module->get_prefix( 'import' ); ?>">
			<h4><?php _e( 'Import', 'sv100_companion' ); ?></h4>
			<div class="description">
				<?php
					echo __( 'Select your settings file and click on "Import settings".', 'sv100_companion' ) . '<br>' .
						 __( 'Allowed filetypes: .json', 'sv100_companion' );
				?>
			</div>
			<label for="<?php echo $module->get_prefix( 'import_file' ); ?>">
				<input data-sv_type="sv_form_field"
					   class="sv_file"
					   name="<?php echo $module->get_prefix( 'import_file' ); ?>"
					   type="file"
					   accept=".json"
					   placeholder="<?php _e( 'Settings file', 'sv100_companion' ); ?>"
				>
				<button class="button"
						type="button"
						data-sv_admin_modal='[<?php echo $import_modal; ?>]'
				>
					<?php _e( 'Import settings', 'sv100_companion' ); ?>
				</button>
			</label>
		</div>
	</form>

	<div class="sv_setting <?php echo $module->get_prefix( 'export' ); ?>">
		<h4><?php _e( 'Export', 'sv100_companion' ); ?></h4>
		<label for="<?php echo $module->get_prefix( 'export' ); ?>">
			<a href="<?php echo $export_url; ?>" class="button">
				<?php _e( 'Export settings', 'sv100_companion' ); ?>
			</a>
		</label>
	</div>

	<div class="sv_setting <?php echo $module->get_prefix( 'reset' ); ?>">
		<h4><?php _e( 'Reset to factory settings', 'sv100_companion' ); ?></h4>
		<div class="description">
			<?php _e( 'All your settings will be removed and replaced with the default settings.', 'sv100_companion' ); ?>
		</div>
		<label for="<?php echo $module->get_prefix( 'reset' ); ?>">
			<button
					class="button"
					data-sv_admin_modal='[<?php echo $reset_modal; ?>]'
					data-sv_admin_ajax='[<?php echo $reset_ajax; ?>]'
			>
				<?php _e( 'Reset settings', 'sv100_companion' ); ?>
			</button>
		</label>
	</div>
</div>
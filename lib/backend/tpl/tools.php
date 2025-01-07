<?php
// Fetch current settings
$current_settings = array();
$instances = $this->get_instances(); // get all active instances

foreach ( $instances as $instance ) {
	$module_settings = $instance->get_modules_settings();
	foreach ( $module_settings as $module_name => $settings ) {
		$current_settings = array_merge( $current_settings, $settings );
	}
}

// Encode settings as JSON for display in the textarea
$current_settings_json = json_encode( $current_settings, JSON_PRETTY_PRINT );

// Nonce and AJAX setup for import
$import_ajax = wp_json_encode(
	array(
		'action' => $module->get_prefix( 'import' ),
		'nonce'  => wp_create_nonce( $module->get_prefix( 'import' ) )
	)
);

// Modal for import
$import_modal = wp_json_encode(
	array(
		'title' => __( 'Import settings', 'sv100_companion' ),
		'desc'  => __( 'All your settings will be removed and replaced with the new settings.', 'sv100_companion' ) . '<br>' .
		           __( 'Do you want to proceed?', 'sv100_companion' ),
		'type'  => 'confirm'
	)
);

// Ajax for reset (unchanged)
$reset_ajax = wp_json_encode(
	array(
		'action' => $module->get_prefix( 'reset' ),
		'nonce'  => wp_create_nonce( $module->get_prefix( 'reset' ) )
	)
);

// Modal for reset (unchanged)
$reset_modal = wp_json_encode(
	array(
		'title' => __( 'Reset settings', 'sv100_companion' ),
		'desc'  => __( 'All your settings will be removed and replaced with the default settings.', 'sv100_companion' ) . '<br>' .
		           __( 'Do you want to proceed?', 'sv100_companion' ),
		'type'  => 'confirm'
	)
);
?>

<div class="<?php echo $module->get_prefix(); ?>">
	<!-- Import Settings Section -->
	<div class="sv_setting <?php echo $module->get_prefix( 'import' ); ?>">
		<h4><?php _e( 'Import Settings', 'sv100_companion' ); ?></h4>
		<textarea
			class="sv_setting"
			id="<?php echo $module->get_prefix( 'import_data' ); ?>"
			name="<?php echo $module->get_prefix( 'all' ); ?>"
			style="min-height: 400px; width: 100%;"
		><?php echo esc_textarea( $current_settings_json ); ?></textarea>
		<button
			class="button"
			data-sv_admin_modal='[<?php echo $import_modal; ?>]'
			data-sv_admin_ajax='[<?php echo $import_ajax; ?>]'
		>
			<?php _e( 'Import settings', 'sv100_companion' ); ?>
		</button>
	</div>

	<!-- Reset to Factory Settings Section (unchanged) -->
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

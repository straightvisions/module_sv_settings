<?php
	// Nonce for import
	$import_url = wp_nonce_url(
		admin_url() . 'admin.php?page=sv100_companion&section=sv100_companion_sv_settings',
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
		<div class="sv_setting <?php echo $module->get_prefix( 'export' ); ?>">
			<form method="post" action="<?php echo $import_url; ?>" enctype="multipart/form-data" id="<?php echo $module->get_prefix( 'import' ); ?>">
				<?php wp_nonce_field( $module->get_prefix( 'import' ), $module->get_prefix( 'import' ) ); ?>
				<?php
				$instances					= $this->get_instances(); // get all activate instances
				$output_se					= array();
				$setting_array				= array();
				$m							= 0;

				foreach($instances as $instance){
					$i						= 0;
					$output_se_m			= array();
					$module_settings		= $instance->get_modules_settings();

					foreach($module_settings as $module_name => $settings){
						$output_se_m[]		= '<li><h4>'.$module_name.'</h4> ('.count($settings).' '.__('Settings','sv100_companion').')</li>';

						if(count($settings) > 0){
							$output_se_m[]		= '<textarea class="sv_setting" name="instances['.$instance->get_name().']">'.json_encode($settings).'</textarea>';
							$setting_array		= $setting_array+$settings;

							$output_se_m[]		= '<ul style="margin:20px;display:none;">';
							foreach($settings as $setting_id => $data) {
								$output_se_m[]	= '<li>' . $setting_id . '</li>';
								$i++;
								$m++;
							}
							$output_se_m[]		= '</ul>';
						}
					}

					$output_se[]			= '<h3>'.$instance->get_section_title().' ('.count($module_settings).' '.__('modules', 'sv100_companion').')</h3>';

					$output_se[]			= '<ul style="display:none;">';

					$output_se[]			= implode('', $output_se_m);

					$output_se[]			= '</ul>';
				}

				echo '<h4>'.number_format_i18n($m).' '.__('Settings','sv100_companion').'</h4>';
				echo '<textarea class="sv_setting" id="sv_copy_target_to_clipboard_source" name="'.$module->get_prefix( 'all' ).'" style="min-height:400px;">'.json_encode($setting_array).'</textarea>';

				//echo implode('', $output_se);
			?>
				<button class="button" type="button" data-sv_admin_modal='[<?php echo $import_modal; ?>]'><?php _e( 'Import settings', 'sv100_companion' ); ?></button>
				<button class="button sv_copy_target_to_clipboard" type="button" data-source="sv_copy_target_to_clipboard_source"><?php _e('Copy to clipboard', 'sv100_companion'); ?></button>
			</form>
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
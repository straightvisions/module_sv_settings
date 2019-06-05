<?php
namespace sv_100;

/**
 * @version         1.00
 * @author			straightvisions
 * @package			sv_100
 * @copyright		2019 straightvisions GmbH
 * @link			https://straightvisions.com
 * @since			1.0
 * @license			See license.txt or https://straightvisions.com
 */

class sv_settings extends init {
	public function __construct() {
	
	}

	public function init() {
		// Translates the module
		load_theme_textdomain( $this->get_module_name(), $this->get_path( 'languages' ) );

		// Module Info
		$this->set_module_title( 'SV Settings' );
		$this->set_module_desc( __( 'Import and export your settings.', $this->get_module_name() ) );

		// Section Info
		$this->set_section_title( __( 'Settings Import/Export', $this->get_module_name() ) );
		$this->set_section_desc( __( 'Import and export your settings', $this->get_module_name() ) );
		$this->set_section_type( 'tools' );
		$this->get_root()->add_section( $this )
			 ->set_section_template_path( $this->get_path( 'lib/backend/tpl/tools.php' ) );

		// Loads Settings
		$this->load_settings();
		
		// Action Hooks
		add_action( 'wp_ajax_' . $this->get_prefix( 'export' ) , array( $this, 'settings_export' ) );
	}

	protected function load_settings(): sv_settings {
		$this->s['export'] = static::$settings->create( $this )
			->set_ID( 'export' )
			->set_title( __( 'Export', $this->get_module_name() ) )
			->set_description( __( 'Check the checkbox and click on "Save settings", to export your module settings.', $this->get_module_name() ) )
			->load_type( 'checkbox' );

		$this->s['import'] = static::$settings->create( $this )
			->set_ID( 'import' )
			->set_title( __( 'Import your module settings', $this->get_module_name() ) )
			->load_type( 'upload' );
		
		$this->s['import']->run_type()->set_allowed_filetypes( array( '.json' ) );
		
		return $this;
	}
	
	public function settings_export() {
		$file['name']	= $this->get_prefix( 'export_' . current_time( 'YmdHis' ) . '.json' );
		$file['data']	= wp_json_encode( $this->get_modules_settings() );
		
		header('Content-Type: application/json');
		header('Content-Disposition: attachment;filename="' . $file['name'] . '"');
		
		echo $file['data'];
		
		wp_die();
	}
}
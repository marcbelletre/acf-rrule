<?php

/*
Plugin Name: Advanced Custom Fields: RRule
Plugin URI: https://github.com/marcbelletre/acf-rrule
Description: Create recurring rules with a single ACF field
Version: 1.0.1
Author: Marc Bellêtre
Author URI: https://pixelparfait.fr
License: MIT
*/

require_once(__DIR__ . '/vendor/autoload.php');

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('acf_plugin_rrule') ) :

class acf_plugin_rrule {

	// vars
	var $settings;


	/*
	*  __construct
	*
	*  This function will setup the class functionality
	*
	*  @type	function
	*  @date	17/02/2016
	*  @since	1.0.0
	*
	*  @param	void
	*  @return	void
	*/

	function __construct() {

		// settings
		// - these will be passed into the field class.
		$this->settings = array(
			'version'	=> '1.0.0',
			'url'		=> plugin_dir_url( __FILE__ ),
			'path'		=> plugin_dir_path( __FILE__ )
		);

		// include field
		add_action('acf/include_field_types', 	array($this, 'include_field'));
	}


	/*
	*  include_field
	*
	*  This function will include the field type class
	*
	*  @type	function
	*  @date	17/02/2016
	*  @since	1.0.0
	*
	*  @param	$version (int) major ACF version. Defaults to false
	*  @return	void
	*/

	function include_field( $version = false ) {

		// load acf-rrule
		load_plugin_textdomain( 'acf-rrule', false, basename( dirname(__FILE__) ) . '/lang' );
        load_muplugin_textdomain( 'acf-rrule', basename( dirname(__FILE__) ) . '/lang' );

		// include
		include_once('fields/class-acf-field-rrule.php');
	}

}


// initialize
new acf_plugin_rrule();


// class_exists check
endif;

?>

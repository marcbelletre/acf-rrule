<?php

/*
Plugin Name: ACF RRule Field
Plugin URI: https://github.com/marcbelletre/acf-rrule
Description: Create recurring rules with a single ACF field
Version: 1.5.1
Author: Marc Bellêtre
Author URI: https://pixelparfait.fr
License: MIT
Text Domain: acf-rrule-field
Domain Path: /lang
*/

require_once __DIR__ . '/vendor/autoload.php';

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Check if class already exists
if (!class_exists('acf_plugin_rrule')) :

    class acf_plugin_rrule
    {
        public $settings;

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

        public function __construct()
        {
            // Settings
            // - these will be passed into the field class.
            $this->settings = [
                'version' => '1.5.1',
                'url' => plugin_dir_url(__FILE__),
                'path' => plugin_dir_path(__FILE__),
            ];

            // Include field
            add_action('acf/include_field_types', [$this, 'include_field']);
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

        public function include_field($version = false)
        {
            // Load ACF RRule
            load_plugin_textdomain('acf-rrule-field', false, basename(dirname(__FILE__)) . '/lang');
            load_muplugin_textdomain('acf-rrule-field', basename(dirname(__FILE__)) . '/lang');

            // Include
            include_once 'fields/class-acf-field-rrule.php';
        }
    }

    // Initialize
    new acf_plugin_rrule();

// class_exists check
endif;

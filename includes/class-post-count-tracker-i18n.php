<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 *
 * @package    PCTA_Post_Count_Tracker
 * @subpackage PCTA_Post_Count_Tracker/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.1.0
 * @package    PCTA_Post_Count_Tracker
 * @subpackage PCTA_Post_Count_Tracker/includes
 * @author     Amir <amirnafees88@gmail.com>
 */
class PCTA_Post_Count_Tracker_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.1.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'post-count-tracker',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}

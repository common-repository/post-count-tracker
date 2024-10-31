<?php

/**
 * Fired during plugin activation
 *
 *
 * @package    PCTA_Post_Count_Tracker
 * @subpackage PCTA_Post_Count_Tracker/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.1.0
 * @package    PCTA_Post_Count_Tracker
 * @subpackage PCTA_Post_Count_Tracker/includes
 * @author     Amir <amirnafees88@gmail.com>
 */
class PCTA_Post_Count_Tracker_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.1.0
	 */
	public static function activate() {
		// Set default option for shortcode usage (unchecked by default).
        if ( false === get_option( 'pct_shortcode_option' ) ) {
            update_option( 'pct_shortcode_option', '' );
        }
	}

}

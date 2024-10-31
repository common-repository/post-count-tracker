<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 *
 * @wordpress-plugin
 * Plugin Name:       Post Count Tracker
 * Description:       Monitor and track the number of posts on your platform with ease. This plugin provides real-time insights and detailed analytics, helping you manage and optimize your content strategy effectively
 * Version:           1.1.0
 * Author:            Amir Nafees
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       post-count-tracker
 * Domain Path:       /languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Currently plugin version.
 */
define( 'PCTA_POST_COUNT_TRACKER_VERSION', '1.1.0' );
define( 'PCTA_POST_COUNT_TRACKER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-post-count-tracker-activator.php
 */
function pcta_activate_post_count_tracker() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-post-count-tracker-activator.php';
	PCTA_Post_Count_Tracker_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-post-count-tracker-deactivator.php
 */
function pcta_deactivate_post_count_tracker() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-post-count-tracker-deactivator.php';
	PCTA_Post_Count_Tracker_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'pcta_activate_post_count_tracker' );
register_deactivation_hook( __FILE__, 'pcta_deactivate_post_count_tracker' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-post-count-tracker.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.1.0
 */
function pcta_run_post_count_tracker() {

	$plugin = new PCTA_Post_Count_Tracker();
	$plugin->run();

}
pcta_run_post_count_tracker();

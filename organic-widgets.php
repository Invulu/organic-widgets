<?php

/**
 *
 * @link              https://organicthemes.com
 * @since             1.0.0
 * @package           Organic_Widgets
 *
 * @wordpress-plugin
 * Plugin Name:       Organic Customizer Widgets
 * Plugin URI:        https://organicthemes.com/organic-customizer-widgets
 * Description:       Transform the core WordPress Customizer into a page builder. Display and arrange widgets on any page as beautiful content sections, such as featured content slideshows, testimonials, team members, portfolios, feature lists, pricing tables and more. Whoa, cool.
 * Version:           1.0.0
 * Author:            Organic Themes
 * Author URI:        https://organicthemes.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       organic-widgets
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-organic-widgets-activator.php
 */
function activate_organic_widgets() {

	require_once plugin_dir_path( __FILE__ ) . 'includes/class-organic-widgets-activator.php';
	Organic_Widgets_Activator::activate();

}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-organic-widgets-deactivator.php
 */
function deactivate_organic_widgets() {

	require_once plugin_dir_path( __FILE__ ) . 'includes/class-organic-widgets-deactivator.php';
	Organic_Widgets_Deactivator::deactivate();

}

register_activation_hook( __FILE__, 'activate_organic_widgets' );
register_deactivation_hook( __FILE__, 'deactivate_organic_widgets' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-organic-widgets.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_organic_widgets() {

	$plugin = new Organic_Widgets();

	// Define Constants
	define( 'ORGANIC_WIDGETS_18N', $plugin->get_plugin_name() );
	define( 'ORGANIC_WIDGETS_BASE_DIR', plugin_dir_url( __FILE__ ) );
	define( 'ORGANIC_WIDGETS_BLOCKS_DIR', plugin_dir_url( __FILE__ ) . 'admin/blocks/' );
	define( 'ORGANIC_WIDGETS_ADMIN_IMG_DIR', plugin_dir_url( __FILE__ ) . 'admin/img/' );
	define( 'ORGANIC_WIDGETS_ADMIN_JS_DIR', plugin_dir_url( __FILE__ ) . 'admin/js/' );
	define( 'ORGANIC_WIDGETS_ADMIN_CSS_DIR', plugin_dir_url( __FILE__ ) . 'admin/css/' );

	// Keep false until blocks are ready
	define( 'ORGANIC_WIDGETS_BLOCKS_ACTIVE', false );

	$plugin->run();

}
run_organic_widgets();

/**
 * Register hidden welcome screen page.
 *
 * @since    1.0.0
 */
function organic_widgets_welcome_screen() {

	add_submenu_page(
		'options-general.php',
		esc_html__( 'Organic Widgets', ORGANIC_WIDGETS_18N ),
		esc_html__( 'Organic Widgets', ORGANIC_WIDGETS_18N ),
		'manage_options',
		'organic-widgets-welcome',
		'organic_widgets_welcome_screen_content'
	);

}
add_action( 'admin_menu', 'organic_widgets_welcome_screen' );

/**
 * Include welcome screen content.
 *
 * @since    1.0.0
 */
function organic_widgets_welcome_screen_content() {

	include_once plugin_dir_path( __FILE__ ) . '/admin/partials/organic-widgets-welcome-page.php';

}

/**
 * Redirect to welcome screen upon plugin activation.
 *
 * @since    1.0.0
 */
function organic_widgets_activation_redirect( $plugin ) {

	if ( $plugin == plugin_basename( __FILE__ ) ) {
		exit( wp_redirect( add_query_arg( array( 'page' => 'organic-widgets-welcome' ), admin_url( 'admin.php' ) ) ) );
	}

}
add_action( 'activated_plugin', 'organic_widgets_activation_redirect' );

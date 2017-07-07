<?php

/**
 *
 * @link              https://organicthemes.com
 * @since             1.0.0
 * @package           Organic_Widgets
 *
 * @wordpress-plugin
 * Plugin Name:       Organic Customizer Widgets And Blocks
 * Plugin URI:        https://organicthemes.com
 * Description:       Adds a page template with a new widget areas and widgets to a site for more display flexibility
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
	define( 'ORGANIC_WIDGETS_18N', $plugin->get_plugin_name() );
	define( 'ORGANIC_WIDGETS_BASE_DIR', plugin_dir_url( __FILE__ ) );
	define( 'ORGANIC_WIDGETS_BLOCKS_DIR', plugin_dir_url( __FILE__ ) . 'admin/blocks/' );
	// define( 'ORGANIC_WIDGETS_BLOCKS_DIR', plugin_dir_url( __FILE__ ) . 'admin/blocks/library/' );
	define( 'ORGANIC_WIDGETS_ADMIN_JS_DIR', plugin_dir_url( __FILE__ ) . 'admin/js/' );
	define( 'ORGANIC_WIDGETS_ADMIN_CSS_DIR', plugin_dir_url( __FILE__ ) . 'admin/css/' );

	// Change to false before blocks are ready
	define( 'ORGANIC_WIDGETS_BLOCKS_ACTIVE', true );
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
		null,
		esc_html__( 'Welcome', ORGANIC_WIDGETS_18N ),
		esc_html__( 'Welcome', ORGANIC_WIDGETS_18N ),
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
	include_once plugin_dir_path( __FILE__ ) . '/admin/organic-widgets-welcome.php';
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

/**
 * Returns true if widget is first in group, false if not
 *
 * @since    1.0.0
 */
function organic_widgets_is_first_groupable_widget( $args = false, $instance = false ) {

	if ( $instance && $args ) {

		$widget_area_id = $args['id'];
		$widget_name = $args['widget_name'];
		$widget_id = $args['widget_id'];
		$widget_id_base = _get_widget_id_base( $widget_id );

		// Get widget before and check to see if it is same type
		$widget_areas = wp_get_sidebars_widgets();
		$this_widget_area = $widget_areas[$widget_area_id];

		// If widget is first in area, return true
		if ( $this_widget_area[0] == $widget_id ) { return true; }

		$this_widget_key = array_search( $widget_id, $this_widget_area );

		// Get previous widget
		$prev_widget_id = $this_widget_area[ $this_widget_key - 1 ];
		$prev_widget_id_base = _get_widget_id_base( $prev_widget_id );

		// If previous widget is of same type, return false
		if ( $prev_widget_id_base == $widget_id_base ) {
			return false;
		}

		return true;

	} else {

		return true;

	}

}

/**
 * Returns true if widget is last in group, false if there are more
 *
 * @since    1.0.0
 */
function organic_widgets_is_last_groupable_widget( $args = false, $instance = false ) {

	if ( $instance && $args ) {

		$widget_area_id = $args['id'];
		$widget_name = $args['widget_name'];
		$widget_id = $args['widget_id'];
		$widget_id_base = _get_widget_id_base( $widget_id );

		// Get widget before and check to see if it is same type
		$widget_areas = wp_get_sidebars_widgets();
		$this_widget_area = $widget_areas[$widget_area_id];

		// If widget is last in area, return true
		$length = count($this_widget_area);
		if ( $this_widget_area[ $length -1 ] == $widget_id ) { return true; }

		$this_widget_key = array_search( $widget_id, $this_widget_area );

		// Get next widget
		$next_widget_id = $this_widget_area[ $this_widget_key + 1 ];
		$next_widget_id_base = _get_widget_id_base( $next_widget_id );

		// If previous widget is of same type, return false
		if ( $next_widget_id_base == $widget_id_base ) {
			return false;
		}

		return true;

	} else {

		return true;

	}

}

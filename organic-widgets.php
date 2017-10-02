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
 * Version:           1.1.2
 * Author:            Organic Themes
 * Author URI:        https://organicthemes.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       organic-widgets
 * Domain Path:       /languages
 */

// Current Version (Keep in sync with Version # above)
define ( 'ORGANIC_WIDGETS_CURRENT_VERSION', '1.1.2' );

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-organic-widgets-activator.php
 */
function activate_organic_widgets() {

	global $wp_version;
	$wp = '4.8';
	$php = '5.3.29';

	// Compare PHP and WP versions and make sure the plugin can run
  if ( version_compare( PHP_VERSION, $php, '<' ) ) {
		$flag = 'PHP';
	} elseif ( version_compare( $wp_version, $wp, '<' ) ) {
		$flag = 'WordPress';
	} else {
		// Activate
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-organic-widgets-activator.php';
		Organic_Widgets_Activator::activate();
		return;
	}

	// Notify User that versions are too old, and deactivate plugin
  $version = 'PHP' == $flag ? $php : $wp;
  deactivate_plugins( basename( __FILE__ ) );
	wp_die('<p>The <strong>Organic Customizer Widgets</strong> plugin requires'.$flag.'  version '.$version.' or greater.</p>','Plugin Activation Error',  array( 'response'=>200, 'back_link'=>TRUE ) );

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

	// Add Menu Item
	add_menu_page(
		esc_html__( 'Organic Widgets', ORGANIC_WIDGETS_18N ),
		esc_html__( 'Organic Widgets', ORGANIC_WIDGETS_18N ),
		'manage_options',
		'organic-widgets',
		'organic_widgets_welcome_screen_content',
		'dashicons-screenoptions',
		110
	);

	// Add Settings Page
	add_submenu_page(
		'organic-widgets',
		'Organic Widgets Settings',
		esc_html__( 'Settings', ORGANIC_WIDGETS_18N ),
		'manage_options',
		'organic-widgets-settings',
		'organic_widgets_settings_screen_content'
	);

	add_settings_section(
    'organic_widgets_settings_section',
    'Organic Widgets Settings',
    'organic_widgets_settings_callback',
    'organic-widgets-settings'
	);

	register_setting( 'organic-widgets-settings', 'organic_widgets_settings', array('sanitize_callback'=>'organic_widgets_settings_sanitize_callback'));

}
add_action( 'admin_menu', 'organic_widgets_welcome_screen' );

function organic_widgets_settings_sanitize_callback($options) {
	
	$organic_widgets = organic_widgets_get_organic_widgets();

	foreach($organic_widgets as $key => $organic_widget) {
		if ( !array_key_exists($organic_widget['settings-activate-slug'], $options) ){
			$options[$organic_widget['settings-activate-slug']] = 0;
		}
	}
	
	// Sanitize Additional stylesheets
	if ( !array_key_exists('additional_stylesheets', $options) ){
		$options['additional_stylesheets'] = 0;
	}

	error_log('organic_widgets_settings_sanitize_callback');
	error_log(print_r($organic_widgets,1));
	return $options;
}

function organic_widgets_settings_callback() {

    $options = get_option( 'organic_widgets_settings' ) ? get_option( 'organic_widgets_settings' ) : array();
	if ( !array_key_exists('additional_stylesheets', $options) ){
		$options['additional_stylesheets'] = 0;
	}	
	$organic_widgets = organic_widgets_get_organic_widgets();
	?>
	<table class="form-table">

      <!-- BEGIN Active Widgets Settings -->
      <tr>
        <td><h3><?php _e( 'Active Widgets', ORGANIC_WIDGETS_18N ); ?></h3></td>
      </tr>

      <?php foreach( $organic_widgets as $organic_widget ) {

				$slug = $organic_widget['settings-activate-slug'];
				$name = $organic_widget['settings-name'];
				if ( !array_key_exists( $slug, $options) ) {
					$options[$slug] = 1;
				} ?>

        <tr valign="top">
          <th scope="row"><?php esc_html_e( $name, ORGANIC_WIDGETS_18N ); ?></th>
          <td>
            <input type="checkbox" name="organic_widgets_settings[<?php echo esc_attr($slug); ?>]" value="1" <?php checked($options[$slug],1,1); ?> />
          </td>
        </tr>

      <?php } ?>
      <!-- END Active Widgets Settings -->

      <!-- BEGIN Stylsheet Selector Setting -->
      <tr>
        <td><h3><?php _e( 'Style Selector', ORGANIC_WIDGETS_18N ); ?></h3></td>
      </tr>

      <tr valign="top">
        <th scope="row"><?php _e( 'Additional Stylesheet', ORGANIC_WIDGETS_18N ); ?></th>
        <td>
          <select id="organic_widgets_settings[additional_stylesheets]" name="organic_widgets_settings[additional_stylesheets]">
            <option value="0" <?php selected( $options['additional_stylesheets'], 0 ); ?>>Default</option>
            <option value="2" <?php selected( $options['additional_stylesheets'], 2 ); ?>>Style 2</option>
            <option value="3" <?php selected( $options['additional_stylesheets'], 3 ); ?>>Style 3</option>
          </select>
        </td>
      </tr>
      <!-- Stylsheet Selector Setting -->

    </table>

<?php
}

/**
 * Get All Organic Custom Widgets
 *
 * @since    1.1.2
 */
function organic_widgets_get_organic_widgets() {
	
	$organic_widgets = array();

  	foreach(get_declared_classes() as $widget) {
    	if(is_subclass_of($widget, 'Organic_Widgets_Custom_Widget') ) {
    		$settings_name = str_replace( '_', ' ', str_replace('Organic_Widgets_', '', $widget) );
			$settings_activate_slug = $widget . '_activate';
			$organic_widgets[$widget] = array(
				'settings-activate-slug' => $settings_activate_slug,
				'settings-name' => $settings_name
			);
    	}
    }

	return $organic_widgets;

}

/**
 * Include welcome screen content.
 *
 * @since    1.0.0
 */
function organic_widgets_welcome_screen_content() {

	include_once plugin_dir_path( __FILE__ ) . '/admin/partials/organic-widgets-welcome-page.php';

}

/**
 * Include settings screen content.
 *
 * @since    1.0.0
 */
function organic_widgets_settings_screen_content() {

	include_once plugin_dir_path( __FILE__ ) . '/admin/partials/organic-widgets-settings-page.php';

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

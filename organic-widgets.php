<?php
/**
 *
 * @link              https://organicthemes.com
 * @since             1.0.0
 * @package           Organic_Widgets
 *
 * @wordpress-plugin
 * Plugin Name:       Organic Builder Widgets
 * Plugin URI:        https://organicthemes.com/organic-customizer-widgets
 * Description:       Transform the core WordPress Customizer into a page builder. Display and arrange widgets on any page as beautiful content sections, such as featured content slideshows, testimonials, team members, portfolios, feature lists, pricing tables and more. Whoa, cool.
 * Version:           1.3.1
 * Author:            Organic Themes
 * Author URI:        https://organicthemes.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       organic-widgets
 * Domain Path:       /languages
 */

// Current Version (Keep in sync with Version # above).
define( 'ORGANIC_WIDGETS_CURRENT_VERSION', '1.3.1' );

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

register_activation_hook( __FILE__, 'activate_organic_widgets' );
register_deactivation_hook( __FILE__, 'deactivate_organic_widgets' );
add_action( 'admin_init', 'organic_widgets_welcome_redirect' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-organic-widgets-activator.php
 */
function activate_organic_widgets() {

	global $wp_version;
	$wp  = '4.8';
	$php = '5.3.29';

	// Compare PHP and WP versions and make sure the plugin can run.
	if ( version_compare( PHP_VERSION, $php, '<' ) ) {
		$flag = 'PHP';
	} elseif ( version_compare( $wp_version, $wp, '<' ) ) {
		$flag = 'WordPress';
	} else {
		// Activate.
		if ( is_plugin_active( 'organic-widgets-pro/organic-widgets.php' ) ) {
			add_action( 'update_option_active_plugins', 'deactivate_organic_widgets_pro_version' );
		}
		add_option( 'organic_widgets_install_date', date( 'Y-m-d h:i:s' ) );
		add_option( 'organic_widgets_activation_redirect', true );
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-organic-widgets-activator.php';
		Organic_Widgets_Activator::activate();
		return;
	}

	// Notify User that versions are too old, and deactivate plugin.
	$version = 'PHP' == $flag ? $php : $wp;
	deactivate_plugins( basename( __FILE__ ) );
	wp_die( '<p>The <strong>Organic Builder Widgets</strong> plugin requires' . $flag . '  version ' . $version . ' or greater.</p>', 'Plugin Activation Error', array( 'response' => 200, 'back_link' => true ) );

}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-organic-widgets-deactivator.php
 */
function deactivate_organic_widgets() {

	require_once plugin_dir_path( __FILE__ ) . 'includes/class-organic-widgets-deactivator.php';
	Organic_Widgets_Deactivator::deactivate();

}

/**
 * This function deactivates the premium plugin version upon activation.
 */
function deactivate_organic_widgets_pro_version() {
	deactivate_plugins( 'organic-widgets-pro/organic-widgets.php' );
}

/**
 * This function redirects users to the welcome page upon activation.
 */
function organic_widgets_welcome_redirect() {
	if ( get_option( 'organic_widgets_activation_redirect', false ) ) {
		delete_option( 'organic_widgets_activation_redirect' );
		if ( ! isset( $_GET['activate-multi'] ) ) {
			wp_redirect( add_query_arg( array( 'page' => 'organic-widgets' ), admin_url( 'admin.php' ) ) );
		}
	}
}

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

	// Define Constants.
	define( 'ORGANIC_WIDGETS_18N', $plugin->get_plugin_name() );
	define( 'ORGANIC_WIDGETS_BASE_DIR', plugin_dir_url( __FILE__ ) );
	define( 'ORGANIC_WIDGETS_BLOCKS_DIR', plugin_dir_url( __FILE__ ) . 'admin/blocks/' );
	define( 'ORGANIC_WIDGETS_ADMIN_IMG_DIR', plugin_dir_url( __FILE__ ) . 'admin/img/' );
	define( 'ORGANIC_WIDGETS_ADMIN_JS_DIR', plugin_dir_url( __FILE__ ) . 'admin/js/' );
	define( 'ORGANIC_WIDGETS_ADMIN_CSS_DIR', plugin_dir_url( __FILE__ ) . 'admin/css/' );

	// Keep false until blocks are ready.
	define( 'ORGANIC_WIDGETS_BLOCKS_ACTIVE', false );

	$plugin->run();

}
run_organic_widgets();

/**
 * Add plugin action links.
 *
 * Add a link to the settings page on the plugins.php page.
 *
 * @since 1.0.0
 *
 * @param array $links List of existing plugin action links.
 * @return array List of modified plugin action links.
 */
function organic_widgets_action_links( $links ) {

	$links = array_merge( array(
		'<a class="plugin-upgrade-link" href="' . esc_url( 'https://organicthemes.com/builder/' ) . '" target="_blank"><i class="fa fa-arrow-circle-up"></i> ' . __( 'Upgrade Available', ORGANIC_WIDGETS_18N ) . '</a>'
	), $links );

	return $links;

}
add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'organic_widgets_action_links' );

/**
 * Register Organic Widgets menu pages.
 *
 * @since    1.0.0
 */
function organic_widgets_welcome_screen() {

	$icon_svg = 'data:image/svg+xml;base64,' . base64_encode(
		'<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="40px" height="40px" viewBox="120 10 40 40" xml:space="preserve">
		<g>
			<path fill="#a0a5aa" d="M144.128,11.221c-7.733,0-13.455,1.824-17.002,5.421c-8.137,8.252-5.41,17.112-4.38,19.634
				c0.906,2.217,2.021,3.613,2.875,4.35l2.957-2.609l-0.278-13.13l2.999,10.728l4.374-3.86l0.438-10.677l1.894,8.617l10.528-8.433
				l-8.292,10.76l8.57,1.933l-10.595,0.444l-3.776,4.422l10.614,3.049l-12.974-0.278l-2.522,2.956c0.092,0.11,0.194,0.228,0.315,0.344
				c1.9,1.938,5.897,3.889,10.54,3.889c3.257,0,8.112-0.991,12.775-5.72c8.079-8.19,4.882-25.648,3.841-30.338
				C154.816,12.222,149.721,11.221,144.128,11.221L144.128,11.221L144.128,11.221z"/>
		</g>
		</svg>'
	);

	// Add Menu Item.
	add_menu_page(
		esc_html__( 'Organic Widgets', ORGANIC_WIDGETS_18N ),
		esc_html__( 'Organic Widgets', ORGANIC_WIDGETS_18N ),
		'manage_options',
		'organic-widgets',
		'organic_widgets_welcome_screen_content',
		$icon_svg,
		110
	);

	// Add Settings Page.
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

	// Add Upgrade Link.
	add_submenu_page(
		'organic-widgets',
		'Upgrade',
		esc_html__( 'Upgrade', ORGANIC_WIDGETS_18N ),
		'manage_options',
		'https://organicthemes.com/builder/'
	);

	register_setting( 'organic-widgets-settings', 'organic_widgets_settings', array( 'sanitize_callback' => 'organic_widgets_settings_sanitize_callback' ) );

}
add_action( 'admin_menu', 'organic_widgets_welcome_screen' );

function organic_widgets_settings_sanitize_callback( $options ) {

	$organic_widgets = organic_widgets_get_organic_widgets();

	foreach($organic_widgets as $key => $organic_widget) {
		if ( ! array_key_exists( $organic_widget['settings-activate-slug'], $options ) ) {
			$options[$organic_widget['settings-activate-slug']] = 0;
		}
	}

	// Sanitize Additional stylesheets.
	if ( ! array_key_exists( 'additional_stylesheets', $options ) ) {
		$options['additional_stylesheets'] = 0;
	}

	return $options;
}

function organic_widgets_settings_callback() {

	$options = get_option( 'organic_widgets_settings' ) ? get_option( 'organic_widgets_settings' ) : array();
	if ( !array_key_exists('additional_stylesheets', $options) ){
		$options['additional_stylesheets'] = 0;
	}
	$organic_widgets = organic_widgets_get_organic_widgets();
	?>

	<!-- BEGIN Active Widgets Settings -->

	<div class="organic-widgets-display-settings">

		<h3><?php _e( 'Active Widgets', ORGANIC_WIDGETS_18N ); ?></h3>

		<?php
		foreach ( $organic_widgets as $organic_widget ) {
			$slug = $organic_widget['settings-activate-slug'];
			$name = $organic_widget['settings-name'];
			if ( ! array_key_exists( $slug, $options ) ) {
				$options[$slug] = 1;
			}
			?>

			<div class="organic-widgets-display-toggle"><label><input type="checkbox" name="organic_widgets_settings[<?php echo esc_attr( $slug ); ?>]" value="1" <?php checked( $options[$slug], 1, 1 ); ?> /> <?php esc_html_e( $name, ORGANIC_WIDGETS_18N ); ?></label></div>

		<?php } ?>

	</div>

	<!-- END Active Widgets Settings -->

	<?php
}

/**
 * Get All Organic Custom Widgets
 *
 * @since    1.1.2
 */
function organic_widgets_get_organic_widgets() {

	$organic_widgets = array();

	foreach ( get_declared_classes() as $widget ) {
		if ( is_subclass_of( $widget, 'Organic_Widgets_Custom_Widget' ) ) {
			$settings_name            = str_replace( '_', ' ', str_replace( 'Organic_Widgets_', '', $widget ) );
			$settings_activate_slug   = $widget . '_activate';
			$organic_widgets[$widget] = array(
				'settings-activate-slug' => $settings_activate_slug,
				'settings-name'          => $settings_name,
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

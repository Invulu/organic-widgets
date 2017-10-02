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

	// Register Settings to Show/Hide Widgets
	// register_setting( 'organic-widgets-settings', 'organic_widgets_blog_posts_section_activate' );
	// register_setting( 'organic-widgets-settings', 'organic_widgets_content_slideshow_section_activate' );
	// register_setting( 'organic-widgets-settings', 'organic_widgets_feature_list_section_activate' );
	// register_setting( 'organic-widgets-settings', 'organic_widgets_featured_content_activate' );
	// register_setting( 'organic-widgets-settings', 'organic_widgets_featured_product_section_activate' );
	// register_setting( 'organic-widgets-settings', 'organic_widgets_hero_section_activate' );
	// register_setting( 'organic-widgets-settings', 'organic_widgets_portfolio_section_activate' );
	// register_setting( 'organic-widgets-settings', 'organic_widgets_pricing_table_activate' );
	// register_setting( 'organic-widgets-settings', 'organic_widgets_profile_section_activate' );
	// register_setting( 'organic-widgets-settings', 'organic_widgets_subpage_section_activate' );
	// register_setting( 'organic-widgets-settings', 'organic_widgets_team_section_activate' );
	// register_setting( 'organic-widgets-settings', 'organic_widgets_testimonial_section_activate' );

	add_settings_section(
    'organic_widgets_settings_section',
    'Organic Widgets Settings',
    'organic_widgets_settings_callback',
    'organic-widgets-settings'
	);

	// add_settings_field(
	// 	'organic_widgets_settings',
  //   'Organic Widgets Settings',
  //   'organic_widgets_settings_callback',
  //   'organic-widgets-settings',
  //   'organic_widgets_settings_section'
	// );

	register_setting( 'organic-widgets-settings', 'organic_widgets_settings');

}
add_action( 'admin_menu', 'organic_widgets_welcome_screen' );

function organic_widgets_settings_callback() {

    $options = get_option( 'organic_widgets_settings' ) ? get_option( 'organic_widgets_settings' ) : array();
		error_log(print_r($options,1));
		?>
		<table class="form-table">

      <?php $ocw_settings = array(
        array(
          'name' => 'organic_widgets_blog_posts_section_activate',
          'text' => 'Blog Posts Widget'
        ),array(
          'name' => 'organic_widgets_content_slideshow_section_activate',
          'text' => 'Content Slideshow Widget'
        ),array(
          'name' => 'organic_widgets_feature_list_section_activate',
          'text' => 'Feature List Widget'
        ),
        array(
          'name' => 'organic_widgets_featured_content_activate',
          'text' => 'Featured Content Widget'
        ),array(
          'name' => 'organic_widgets_featured_product_section_activate',
          'text' => 'Featured Product Widget'
        ),array(
          'name' => 'organic_widgets_hero_section_activate',
          'text' => 'Hero Section Widget'
        ),
        array(
          'name' => 'organic_widgets_portfolio_section_activate',
          'text' => 'Portfolio Widget'
        ),array(
          'name' => 'organic_widgets_pricing_table_activate',
          'text' => 'Pricing Table Widget'
        ),array(
          'name' => 'organic_widgets_profile_section_activate',
          'text' => 'Profile Widget'
        ),
        array(
          'name' => 'organic_widgets_subpage_section_activate',
          'text' => 'Subpage Widget'
        ),array(
          'name' => 'organic_widgets_team_section_activate',
          'text' => 'Team Widget'
        ),array(
          'name' => 'organic_widgets_testimonial_section_activate',
          'text' => 'Testimonials Widget'
        ),
      );?>

      <!-- BEGIN Active Widgets Settings -->
      <tr>
        <td><h3><?php _e( 'Active Widgets', ORGANIC_WIDGETS_18N ); ?></h3></td>
      </tr>

      <?php foreach( $ocw_settings as $ocw_setting ) { ?>

        <?php $name = $ocw_setting['name']; ?>
        <?php $text = $ocw_setting['text'];?>
				<?php if ( !array_key_exists( $name, $options) ) {
					$options[$name] = 1;
				}
				error_log('checked: '.checked($options[$name],1,1));
				?>

        <tr valign="top">
          <th scope="row"><?php esc_html_e( $text, ORGANIC_WIDGETS_18N ); ?></th>
          <td>
            <input type="checkbox" name="organic_widgets_settings[<?php echo esc_attr($name); ?>]" value="1" <?php checked($options[$name],1,1); ?> />
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

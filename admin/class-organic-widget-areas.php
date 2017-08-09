<?php

/**
 * The Custom Widget Area Code
 *
 * @link       https://organicthemes.com
 * @since      1.0.0
 *
 * @package    Organic_Widgets
 * @subpackage Organic_Widgets/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Organic_Widgets
 * @subpackage Organic_Widgets/admin
 * @author     Organic Themes <info@organicthemes.com>
 */
class Organic_Widget_Areas {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The prefix for widget areas
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The prefix for widget areas
	 */
	private $widget_prefix;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $widget_prefix ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->widget_prefix = $widget_prefix;
		define( 'ORGANIC_WIDGET_PREFIX', $widget_prefix );

	}

	/**
	 * Register The Widget Areas
	 *
	 * @since    1.0.0
	 */
	public function register_widget_areas() {

		$pages = get_pages( array( 'post_status' =>  array('publish', 'pending', 'private', 'draft') ) );

		foreach ( $pages as $page ) {

			$page_template = get_page_template_slug($page->ID);

	    if ( $page_template == 'templates/organic-custom-template.php' ) {
				register_sidebar( array(
            'name' => $page->post_title .' (Widgets)',
            'id' => $this->widget_prefix . 'page-'.$page->ID . '-widget-area',
            'description' => 'This is the ' . $page->post_title . ' sidebar.',
            'before_widget' => '<div id="%1$s" class="organic-widget organic-widget_%2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h4 class="organic-widgets-default-title">',
          	'after_title' => '</h4>'
        ) );
			}

		}// End Foreach

	}

	/**
	 * Sync Organic Widget Configurations between themes
	 *
	 * @since    1.0.0
	 */
	public function sync_widget_areas() {

		// Check if two themes are related
		$old_theme = wp_get_theme( get_option('theme_switched') );
		$new_theme = wp_get_theme();

		// Sync Widget Areas
		$old_theme_mods = get_option('theme_mods_' . $old_theme->get_stylesheet() );
		$old_theme_custom_widget_areas = array();

		if ( array_key_exists( 'sidebars_widgets', $old_theme_mods ) ) {

			foreach( $old_theme_mods['sidebars_widgets']['data'] as $widget_area_name => $widget_area ) {

				// Check for Organic Widgets
				$length = strlen( $this->widget_prefix );
				if ( $this->widget_prefix == substr( $widget_area_name, 0, $length ) ) {
					$old_theme_custom_widget_areas[$widget_area_name] = $widget_area;
				}

			}

			// Loop Through New Theme's Widget Areas
			$new_theme_mods = get_theme_mods();

			if ( ! array_key_exists( 'sidebars_widgets', $new_theme_mods['sidebars_widgets']['data'] ) ) {
				$new_theme_mods['sidebars_widgets']['data'] = array();
				$new_theme_mods['sidebars_widgets']['data']['wp_inactive_widgets'] = array();
				error_log(' no sidebars found in new theme');
			} else {
				error_log('sidebars found');
				error_log(print_r($new_theme_mods['sidebars_widgets']['data'],1));
			}

			foreach ( $old_theme_custom_widget_areas as $widget_area_name => $widget_area ) {
				error_log($widget_area_name);
				$new_theme_mods['sidebars_widgets']['data'][$widget_area_name] = $widget_area;
			}
			update_option('theme_mods_' . $new_theme->get_stylesheet(), $new_theme_mods );

			// foreach( $new_theme_mods['sidebars_widgets']['data'] as $widget_area_name => $widget_area ) {
			//
			// 	// Check for Organic Widgets
			// 	$length = strlen( $this->widget_prefix );
			// 	if ( $this->widget_prefix == substr( $widget_area_name, 0, $length ) ) {
			//
			// 		//If entry exists in both, sync widgets
			// 		if ( array_key_exists($widget_area_name, $old_theme_custom_widget_areas) ) {
			//
			// 			$new_theme_mods['sidebars_widgets']['data'][$widget_area_name] = $old_theme_custom_widget_areas[$widget_area_name];
			// 			update_option('theme_mods_' . $new_theme->get_stylesheet(), $new_theme_mods );
			//
			// 		}
			//
			// 	}
			//
			// }

		}
		// Check for conflicts between double assigned widgets?

	}

}

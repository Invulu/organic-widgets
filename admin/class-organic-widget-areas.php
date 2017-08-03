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
	 * Store Data of Widgets and Widget Areas to Be Imported
	 *
	 * @since    1.0.3
	 */
	public function before_content_import_action() {

		//Get Widget Data Before Import, store info of which widgets are assigned to which areas
		error_log('before_content_import_action');

		//Get array of pages associated with the custom widget areas in the import

	}

	/**
	 * Assign Inactive Widgets to Correct Widget Areas After Content Import
	 *
	 * @since    1.0.3
	 */
	public function after_all_import_action() {

		// Determine if any of the pages with custom widget areas from import changed ids
		// If so, go through and change the references in the widget import info to make sure widgets go to correct locations

		// Loop through all widgets in inactive area, match them with the widget are they should be in, according to info stored from pre import action
		error_log('after_all_import_action');

	}

	/**
	 * Sync Organic Widget Configurations between Child/Parent Themes
	 *
	 * @since    1.0.0
	 */
	public function sync_widget_areas() {

		// Check if two themes are related
		$old_theme = wp_get_theme( get_option('theme_switched') );
		$old_theme_parent = $old_theme->parent();
		$new_theme = wp_get_theme();
		$new_theme_parent = $new_theme->parent();

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
			$new_theme_custom_widget_areas = array();
			foreach( $new_theme_mods['sidebars_widgets']['data'] as $widget_area_name => $widget_area ) {

				// Check for Organic Widgets
				$length = strlen( $this->widget_prefix );
				if ( $this->widget_prefix == substr( $widget_area_name, 0, $length ) ) {

					//If entry exists in both, sync widgets
					if ( array_key_exists($widget_area_name, $old_theme_custom_widget_areas) ) {

						$new_theme_mods['sidebars_widgets']['data'][$widget_area_name] = $old_theme_custom_widget_areas[$widget_area_name];
						update_option('theme_mods_' . $new_theme->get_stylesheet(), $new_theme_mods );

					}

				}

			}

		}
		// Check for conflicts between double assigned widgets?

	}

}

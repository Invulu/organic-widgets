<?php

/**
 * The admin-specific functionality of the plugin.
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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

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
            'id' => 'page-'.$page->ID . '-widget-area',
            'description' => 'This is the ' . $page->post_title . ' sidebar',
            'before_widget' => '<div id="%1$s" class="organic-widget organic-widget_%2$s">',
            'after_widget' => '</div>',
            'before_title' => '<span style="display:none;">',
          	'after_title' => '</span>'
        ) );

			}

		}

	}

}

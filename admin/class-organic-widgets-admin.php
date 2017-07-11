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
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Organic_Widgets
 * @subpackage Organic_Widgets/admin
 * @author     Organic Themes <info@organicthemes.com>
 */
class Organic_Widgets_Admin {

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
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/organic-widgets-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/organic-widgets-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the JavaScript for the page editor admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_editor_scripts( $hook_suffix ) {
		
		global $post;

		if( 'page' == $post->post_type && ( 'post.php' == $hook_suffix || 'post-new.php' == $hook_suffix ) ) {
    	wp_enqueue_script( $this->plugin_name . '-editor-script', plugin_dir_url( __FILE__ ) . 'js/organic-widgets-editor-admin.js', array( 'jquery' ), $this->version, false );
  	}


	}

}

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
	 * Register the JavaScript for the page editor admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_editor_scripts( $hook_suffix ) {

		global $post;

		if( ( 'post.php' == $hook_suffix || 'post-new.php' == $hook_suffix ) && ( is_object( $post ) && 'page' == $post->post_type ) ) {

			// Register Script
			wp_register_script( $this->plugin_name . '-editor-script', plugin_dir_url( __FILE__ ) . 'js/organic-widgets-editor-admin.js', array( 'jquery' ), $this->version, false );

			// Construct customizer link and localize to script
			if ( current_user_can( 'customize' ) ) {

				// Set variable if template is custom
				$page_template_slug =  get_page_template_slug( $post->ID );
				if ( strpos( $page_template_slug, 'organic-custom-template.php' ) !== false ) {
					$is_custom_template = true;
				} else {
					$is_custom_template = false;
				}

				$customize_url = $this->get_customize_url();

				$leaf_icon_url = ORGANIC_WIDGETS_ADMIN_IMG_DIR . 'leaf-icon.png';

				$postEditorVariables = array(
					'customizeURL' => $customize_url,
					'isCustomTemplate' => $is_custom_template,
					'leafIcon' => $leaf_icon_url
				);

				wp_localize_script( $this->plugin_name . '-editor-script', 'organicWidgets', $postEditorVariables );

			}

			wp_enqueue_script( $this->plugin_name . '-editor-script' );

		}


	}

	/**
	 * Get Customizer Url
	 *
	 * @since    1.0.3
	 */
	public static function get_customize_url() {

		global $post;

		// Construct Link URL
		$base_url_string = admin_url( 'customize.php?');
		$page_url = get_permalink( $post->ID );
		$page_url_string = 'url=' . urlencode($page_url);
		$widget_section = 'sidebar-widgets-' . ORGANIC_WIDGET_PREFIX . 'page-'.$post->ID . '-widget-area';
		$autofocus_string = '&autofocus[section]=' . $widget_section;
		$customize_url = $base_url_string . $page_url_string . $autofocus_string;

		return $customize_url;

	}

}

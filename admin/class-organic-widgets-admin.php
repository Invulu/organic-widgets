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

	/**
	 * Store Data of Widgets and Widget Areas to Be Imported
	 *
	 * @since    1.0.3
	 */
	public function before_widgets_import_action() {

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

		error_log('after_all_import_action');

		// Get the import files used
		//$ocdi_importer_data = get_transient( 'ocdi_importer_data' );
		//TEMP files
		$ocdi_importer_data = array(
			'frontend_error_messages' => array(),
			'log_file_path' => '/app/public/wp-content/uploads/2017/08/log_file_2017-08-04__09-58-09.txt',
			'selected_index' => 0,
			'selected_import_files' => array(
				'content' => '/app/public/wp-content/themes/organic-startup/demo/default-demo-content.xml',
				'widgets' => '/app/public/wp-content/themes/organic-startup/demo/default-demo-widgets.json',
				'customizer' => '/app/public/wp-content/themes/organic-startup/demo/default-demo-customizer.dat',
				'redux' => ''
			),
			'before_import_executed' => 1
		);

		// Get page ids for pages with custom template applied
		$custom_page_ids = $this->get_organic_custom_pages();

		// Get changed page id info
		$changed_page_ids = $this->get_id_changes( $custom_page_ids, $ocdi_importer_data );

		error_log('$changed_page_ids');
		error_log(print_r($changed_page_ids,1));
		// error_log(print_r($ocdi_importer_data,1));

	}

	/**
	 * Get Array of items whose IDs changed on import
	 *
	 * @since    1.0.3
	 *
	 * @param   array   $custom_page_ids 			An array of the IDs of the pages to compare against import file
	 * @param   string  $ocdi_importer_data 	The info from the OCDI Import transient
	 *
	 * @return	array 	$id_changes   	Array of the changes in IDs
	 */
	public function get_id_changes( $custom_page_ids, $ocdi_importer_data ) {

		$id_changes = array(
			'pages' => array(),
			'attachments' => array(),
			'widgets' => array()
		);

		$log_file_path = $ocdi_importer_data['log_file_path'];

		// Get pages and attachments
		$content_file_path = $ocdi_importer_data['selected_import_files']['content'];
		$import_items = $this->get_items_from_import_file( $content_file_path );

		// Get widgets
		$widgets_file_path = $ocdi_importer_data['selected_import_files']['widgets'];
		//tbd


		// Pages
		foreach( $custom_page_ids as $id ) {

			$title = get_the_title( $id );
			$post_date_gmt = get_post_time( 'U', true, $id );

			foreach ( $import_items['pages'] as $import_page ) {
				if ( get_the_title( $id ) == $import_page['title'] && $post_date_gmt == strtotime( $import_page['post_date_gmt'] ) ) {
					if ( $id != $import_page['post_id'] ) {
						$id_changes['pages'][] = array(
							'title' => $title,
							'new_id' => $id,
							'old_id' => $import_page['post_id'],
						);
					}
				}
			}

		}// End foreach for pages

		// Attachments

		// Widgets

		return $id_changes;

	}

	/**
	 * Get pages and attachments from import file
	 *
	 * @since    1.0.3
	 *
	 */
	public function get_items_from_import_file( $import_file ) {

		$xml = simplexml_load_file( $import_file, 'SimpleXMLElement', LIBXML_NOCDATA );
		$pages = array();
		$attachments = array();

		// Process each item
		foreach($xml->channel->item as $item) {

			$post_type = $item->children('wp', true)->post_type;

			// Get page template
			$page_template = '';
			$meta_array = $item->children('wp', true)->postmeta;
			foreach( $meta_array as $meta_item ) {
				if ( '_wp_page_template' == $meta_item->meta_key ) {
					$page_template = (string) $meta_item->meta_value;
				}
			}

			// If page or attachment, get values and add to corresponding array
			if ( 'page' == $post_type || 'attachment' == $post_type ) {

				$item = array(
					'title' => (string) $item->title,
					'pubDate' => (string) $item->pubDate,
					'post_id' => (int) $item->children('wp', true)->post_id,
					'post_type' => (string) $post_type,
					'page_template' => $page_template,
					'post_date_gmt' => (string) $item->children('wp', true)->post_date_gmt,
					'attachment_url' => (string) $item->children('wp', true)->attachment_url
 				);

				// Add to corresponding array
				if ( 'page' == $post_type ) {
					$pages[] = $item;
				} elseif ( 'attachment' == $post_type ) {
					$attachments[] = $item;
				}

			}

		}

		// Return pages and attachments
		return array(
			'pages' => $pages,
			'attachments' => $attachments
		);

	}

	/**
	 * Get Array of pages using the Organic Custom template
	 *
	 * @since    1.0.3
	 *
	 * @return	array 	$custom_page_ids   Array of all the pages on the site using the custom template
	 */
	public function get_organic_custom_pages() {

		$custom_page_ids = array();

		$pages = get_pages( array( 'post_status' =>  array('publish', 'pending', 'private', 'draft') ) );

		foreach ( $pages as $page ) {

			$page_template = get_page_template_slug($page->ID);

			if ( $page_template == 'templates/organic-custom-template.php' ) {
				array_push( $custom_page_ids, $page->ID );
			}

		}// End Foreach

		return $custom_page_ids;

	}

}

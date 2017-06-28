<?php
/**
 * The blocks functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Organic_Widgets
 * @subpackage Organic_Widgets/blocks
 * @author     Organic Themes <info@organicthemes.com>
 */
class Organic_Widgets_Blocks {

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

		// wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/organic-widgets-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

    if( is_plugin_active( 'gutenberg/gutenberg.php' ) && ORGANIC_WIDGETS_BLOCKS_ACTIVE ) {

			//Get React Scripts
			wp_enqueue_script( 'wp-react-boilerplate-admin',  ORGANIC_WIDGETS_BASE_DIR . '/build/admin.js', array(), $this->version, true );
			wp_enqueue_style( 'wp-react-boilerplate-admin', ORGANIC_WIDGETS_BASE_DIR . '/build/admin.css', array(), $this->version);

			// Find Blocks
			$blocks = array(
        'content-slideshow'
      );
      foreach ( $blocks as $block_name );
      wp_enqueue_script( 'organic-widgets-block-' . $block_name, plugin_dir_url( __FILE__ ) . 'library/'.$block_name.'/index.js', array( 'jquery' ), $this->version, false );
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'blocks/library/'.$block_name.'/'.$block_name.'.php';
			// include( plugin_dir_url( __FILE__ ) . 'library/'.$block_name.'/'.$block_name.'.php' );
		}


	}

  /**
   * Check if Gutenberg is active and include block classes if it is
   *
   * @since      1.0.0
   */
	public function include_block_classes() {




	}

}

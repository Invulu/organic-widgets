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
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		add_action( 'init', [ $this, 'enqueue_blocks' ], 0 );
		add_action( 'widgets_init', [ $this, 'register_widget_sidebar' ], 0 );
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_scripts' ], 10 );
		add_action( 'save_post', [ $this, 'update_widgets_log' ], 10, 3 );
		add_action( 'delete_post', [ $this, 'delete_widgets_log' ], 10 );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_blocks() {

		/**
		 * BLOCK: Widget Area Block.
		 */
		include_once plugin_dir_path( __FILE__ ) . '/widget-area/index.php';

	}

	/**
	 * Load JS and CSS scripts for Block
	 */
	public function enqueue_scripts() {
		// Scripts.
		wp_enqueue_script(
			'organic-widget-area-block-admin-js', // Handle.
			plugins_url( 'widget-area/block/blocks.build.js', __FILE__ ), // Block.js: We register the block here.
			array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n' ), // Dependencies, defined above.
			'1.0',
			true // Load script in footer.
		);

		// Styles.
		wp_enqueue_style(
			'organic-widget-area-block-admin-editor', // Handle.
			plugins_url( 'widget-area/block/blocks.editor.build.css', __FILE__ ), // Block editor CSS.
			array( 'wp-edit-blocks' ), // Dependency to include the CSS after it.
			'1.0'
		);
		wp_enqueue_style(
			'organic-widget-area-block-admin-style', // Handle.
			plugins_url( 'widget-area/block/blocks.style.build.css', __FILE__ ), // Block editor CSS.
			array( 'wp-edit-blocks' ), // Dependency to include the CSS after it.
			'1.0'
		);
	}

	/**
	 * Callback for block render.
	 *
	 * @param array $attribute Render widget area based on title.
	 */
	public function render_widget_area( $attribute ) {

		ob_start();

		$widget_area_title = $attribute['widget_area_title'];

		dynamic_sidebar( sanitize_title( $widget_area_title ) );

		$output = ob_get_clean();

		return $output;
	}

	/**
	 * Load widgets area for all pages/posts.
	 */
	public function register_widget_sidebar() {

		$saved_widgets = get_option( 'organic_widget-area' );

		if ( ! empty( $saved_widgets ) ) {
			foreach ( $saved_widgets as $post_id => $widgets ) {
				if ( false !== get_post_status( $post_id ) ) {
					foreach ( $widgets as $widget_name ) {
						if ( '' !== trim( $widget_name ) ) {
							$side_bar_id = register_sidebar(
								array(
									'name'          => __( $widget_name, 'organic-widget-area' ),
									'id'            => sanitize_title( $widget_name ),
									'description'   => __( 'Add widgets here to appear in your sidebar on blog posts and archive pages.', 'organic-widget-area' ),
									'before_widget' => '<div id="%1$s" class="organic-widget widget %2$s">',
									'after_widget'  => '</div>',
									'before_title'  => '<h2 class="widget-title">',
									'after_title'   => '</h2>',
								)
							);
						}
					}
				}
			}
		}

	}

	/**
	 * Save newly added widgets in options on post save.
	 *
	 * @param int    $post_id post id.
	 * @param object $post
	 * @param string $update
	 */
	public function update_widgets_log( $post_id, $post, $update ) {
		// Check if user has permissions to save data.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Check if not an autosave.
		if ( wp_is_post_autosave( $post_id ) ) {
			return;
		}

		// Check if not a revision.
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		$saved_widgets = get_option( 'organic_widget-area' );

		$blocks = parse_blocks( $post->post_content );
		if ( isset( $saved_widgets[ $post_id ] ) ) {
			unset( $saved_widgets[ $post_id ] );
		}

		if ( ! empty( $blocks ) ) {
			foreach ( $blocks as $block ) {
				if ( isset( $block['blockName'] ) && 'organic/widget-area' === $block['blockName'] ) {
					if ( '' !== trim( $block['attrs']['widget_area_title'] ) ) {
						$saved_widgets[ $post_id ][] = $block['attrs']['widget_area_title'];
					}
				}
			}
		}

		$saved_widgets = self::find_saved_widgets_recursive( $saved_widgets, $post_id, $blocks );

		update_option( 'organic_widget-area', $saved_widgets, true );

	}

	/**
	 * Walk the post content blocks recursively and find widget area blocks.
	 *
	 * @param array $saved_widgets
	 * @param int $post_id post id.
	 * @param array $blocks
	 * @return array $saved_widgets
	 */
	protected static function find_saved_widgets_recursive( &$saved_widgets, $post_id, $blocks ) {

		if ( ! empty( $blocks ) ) {
			foreach ( $blocks as $block ) {
				if ( isset( $block['blockName'] ) && 'organic/widget-area' === $block['blockName'] ) {
					if ( '' !== trim( $block['attrs']['widget_area_title'] ) ) {
						$saved_widgets[ $post_id ][] = $block['attrs']['widget_area_title'];
					}
				} elseif ( isset( $block['innerBlocks'] ) && ! empty( $block['innerBlocks'] ) ) {
					self::find_saved_widgets_recursive( $saved_widgets, $post_id, $block['innerBlocks'] );
				}
			}
		}

		return $saved_widgets;
	}

	/**
	 * Update when a post deleted for widgets in options.
	 *
	 * @param int $post_id post id.
	 */
	public function delete_widgets_log( $post_id ) {
		$saved_widgets = get_option( 'organic_widget-area' );

		if ( isset( $saved_widgets[ $post_id ] ) ) {
			unset( $saved_widgets[ $post_id ] );
		}

		update_option( 'organic_widget-area', $saved_widgets, true );
	}

}

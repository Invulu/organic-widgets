<?php
/* Registers a widget to show a Team subsection on a page */

// Block direct requests.
if ( !defined('ABSPATH') )
	die('-1');


add_action( 'widgets_init', function(){
	register_widget( 'Organic_Widgets_Blog_Posts_Section_Widget' );
});
/**
 * Adds Organic_Widgets_Blog_Posts_Section_Widget widget.
 */
class Organic_Widgets_Blog_Posts_Section_Widget extends Organic_Widgets_Custom_Widget {

	const CUSTOM_IMAGE_SIZE_SLUG = 'organic_widgets_widget_image_upload';

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'organic_widgets_blog_posts_section', // Base ID
			__( 'Blog Posts Section', ORGANIC_WIDGETS_18N ), // Name
			array(
				'description' => __( 'A section displaying recent blog posts.', ORGANIC_WIDGETS_18N ),
				'customize_selective_refresh' => true,
			) // Args
		);

		$this->id_prefix = $this->get_field_id('');

		// Bg options
		$this->bg_options = array(
			'color' => true,
			'image' => true,
			'video' => true
		);

		// Admin Scripts
		add_action( 'sidebar_admin_setup', array( $this, 'admin_setup' ) );
	}
	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {

		$attr = array();
		$attr = apply_filters( 'image_widget_image_attributes', $attr, $instance );
		$bg_image_id = isset( $instance['bg_image_id'] ) ? $instance['bg_image_id'] : false;
		$bg_image = ( isset( $instance['bg_image'] ) && '' != $instance['bg_image'] ) ? $instance['bg_image'] : false;
		$bg_color = ( isset( $instance['bg_color'] ) && '' != $instance['bg_color'] ) ? $instance['bg_color'] : false;
		$bg_video  = ( isset( $instance['bg_video'] ) && $instance['bg_video'] ) ? $instance['bg_video'] : false;
		$title = ( isset( $instance['title'] ) ) ? $instance['title'] : false;
		$summary = ( isset( $instance['summary'] ) ) ? $instance['summary'] : false;
		$category = ( isset( $instance['category'] ) ) ? $instance['category'] : false;

		echo $args['before_widget'];
		?>
		<!-- BEGIN .organic_widgets-section -->
		<div class="organic_widgets-section organic_widgets-blog-posts-section<?php if ( 0 < $bg_image_id ) { ?> has-thumb text-white<?php } ?>" <?php if ( 0 < $bg_image_id ) { ?>style="background-image:url(<?php echo $bg_image; ?>);"<?php } elseif ($bg_color) { ?>style="background-color:<?php echo $bg_color; ?>;"<?php } ?>>

			<!-- BEGIN .row -->
			<div class="row">

				<!-- BEGIN .content -->
				<div class="content">

					<?php if ( ! empty( $instance['title'] ) ) { ?>
						<h2 class="headline <?php if ( $bg_image_id > 0 ) { ?> text-white<?php } ?>"><?php echo apply_filters( 'widget_title', $instance['title'] ); ?></h2>
					<?php } ?>

					<?php if ( ! empty( $instance['summary'] ) ) { ?>
						<p class="summary"><?php echo $instance['summary'] ?></p>
					<?php } ?>

					<!-- BEGIN .post-area -->
					<div class="post-area wide">

						<?php get_template_part( 'content/loop', 'blog' ); ?>

					<!-- END .post-area -->
					</div>

				<!-- END .content -->
				</div>

			<!-- END .row -->
			</div>

		<!-- END .organic_widgets-section -->
		</div>

		<?php echo $args['after_widget'];



	}
	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	*/
	public function form( $instance ) {

		// Setup Variables.
		$this->id_prefix = $this->get_field_id('');
		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		} else { $title = false; }
		if ( isset( $instance[ 'summary' ] ) ) {
			$summary = $instance[ 'summary' ];
		} else { $summary = ''; }
		if ( isset( $instance['category'] ) ) {
			$category = $instance['category'];
		} else { $category = false; }
		if ( isset( $instance['bg_color'] ) ) {
			$bg_color = $instance['bg_color'];
		} else { $bg_color = false; }
		if ( isset( $instance['bg_image_id'] ) ) {
			$bg_image_id = $instance['bg_image_id'];
		} else { $bg_image_id = 0; }
		if ( isset( $instance['bg_image_id'] ) && isset( $instance['bg_image'] ) ) {
			$bg_image = $instance['bg_image'];
		} else { $bg_image = false; }

		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', ORGANIC_WIDGETS_18N) ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php if ( $title ) echo $title; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'summary' ); ?>"><?php _e('Section Content:', ORGANIC_WIDGETS_18N) ?></label>
			<textarea class="widefat" rows="6" cols="20" id="<?php echo $this->get_field_id( 'summary' ); ?>" name="<?php echo $this->get_field_name( 'summary' ); ?>"><?php echo $summary; ?></textarea>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e('Post Category:', ORGANIC_WIDGETS_18N) ?></label>
			<?php wp_dropdown_categories( array(
				'selected' => $category,
				'id' => $this->get_field_id( 'category' ),
				'name' => $this->get_field_name( 'category' )
			)); ?>
		</p>

		<?php $this->section_background_input_markup( $instance, $this->bg_options ); ?>

  <?php
	}
	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		if (isset( $new_instance['title'] ) )
			$instance['title'] = strip_tags( $new_instance['title'] );
		if (isset( $new_instance['bg_image_id'] ) )
			$instance['bg_image_id'] = strip_tags( $new_instance['bg_image_id'] );
		if (isset( $new_instance['bg_image'] ) )
			$instance['bg_image'] = strip_tags( $new_instance['bg_image'] );
		if ( isset( $new_instance['bg_color'] ) && $this->check_hex_color( $new_instance['bg_color'] ) ) {
			$instance['bg_color'] = strip_tags( $new_instance['bg_color'] );
		} else {
			$instance['bg_color'] = false;
		}
		if ( isset( $new_instance['summary'] ) )
			$instance['summary'] = strip_tags( $new_instance['summary'] );
		if ( isset( $new_instance['category'] ) )
			$instance['category'] = $new_instance['category'];

		return $instance;
	}

	/**
	 * Enqueue all the javascript.
	 */
	public function admin_setup() {

		wp_enqueue_media();
		wp_enqueue_script( 'blog-posts-section-widget-js', plugin_dir_url( __FILE__ ) . 'js/blog-posts-section-widget.js', array( 'jquery', 'media-upload', 'media-views' ) );
		wp_enqueue_style( 'organic_widgets-blog-posts-section-widget-css', plugin_dir_url( __FILE__ ) . 'css/blog-posts-section-widget.css' );

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_script( 'organic-widgets-module-color-picker', ORGANIC_WIDGETS_ADMIN_JS_DIR . 'organic-widgets-module-color-picker.js', array( 'jquery', 'media-upload', 'media-views', 'wp-color-picker' ) );

		wp_enqueue_script( 'organic-widgets-module-image-background', ORGANIC_WIDGETS_ADMIN_JS_DIR . 'organic-widgets-module-image-background.js', array( 'jquery', 'media-upload', 'media-views', 'wp-color-picker' ) );
		wp_localize_script( 'organic-widgets-module-image-background', 'SubpageWidget', array(
			'frame_title' => __( 'Select an Image', ORGANIC_WIDGETS_18N ),
			'button_title' => __( 'Insert Into Widget', ORGANIC_WIDGETS_18N ),
		) );

	}

} // class Organic_Widgets_Blog_Posts_Section_Widget

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
		if ( isset( $instance['organic_widgets_blog_posts_section_bg_image_id'] ) && '' != $instance['organic_widgets_blog_posts_section_bg_image_id'] ) {
			$organic_widgets_blog_posts_section_bg_image_id = $instance['organic_widgets_blog_posts_section_bg_image_id'];
			$img_array = wp_get_attachment_image_src($organic_widgets_blog_posts_section_bg_image_id, 'organic_widgets-featured-large', false, $attr);
			$organic_widgets_blog_posts_section_bg_image = $img_array[0];
		} else { $organic_widgets_blog_posts_section_bg_image_id = 0; }
		$organic_widgets_blog_posts_section_title = isset( $instance['organic_widgets_blog_posts_section_title'] ) ? $instance['organic_widgets_blog_posts_section_title'] : false;

		if ( $organic_widgets_blog_posts_section_bg_image ) {
			$organic_widgets_blog_posts_section_class = ' no-bg';
		} else {
			$organic_widgets_blog_posts_section_class = '';
		}

		echo $args['before_widget'];
		?>
		<div class="organic_widgets-blog-posts-section<?php if ( $organic_widgets_blog_posts_section_bg_image_id > 0 ) { ?> has-thumb<?php } ?>" <?php if ( $organic_widgets_blog_posts_section_bg_image_id > 0 ) { ?>style="background-image:url(<?php echo $organic_widgets_blog_posts_section_bg_image; ?>);"<?php } ?>>

			<!-- BEGIN .row -->
			<div class="row">

				<!-- BEGIN .content -->
				<div class="content no-bg<?php echo $organic_widgets_blog_posts_section_class;?>">

					<?php if ( ! empty( $instance['organic_widgets_blog_posts_section_title'] ) ) { ?>
						<h2 class="headline <?php if ( $organic_widgets_blog_posts_section_bg_image_id > 0 ) { ?> text-white<?php } ?>"><?php echo apply_filters( 'widget_title', $instance['organic_widgets_blog_posts_section_title'] ); ?></h2>
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
		if ( isset( $instance['section_title'] ) ) {
			$section_title = $instance['section_title'];
		} else { $section_title = false; }
		if ( isset( $instance['bg_image_id'] ) ) {
			$bg_image_id = $instance['bg_image_id'];
		} else { $bg_image_id = 0; }
		if ( isset( $instance['bg_image_id'] ) && isset( $instance['bg_image'] ) ) {
			$bg_image = $instance['bg_image'];
		} else { $bg_image = false; }

		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'section_title' ); ?>"><?php _e('Title:', ORGANIC_WIDGETS_18N) ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'section_title' ); ?>" name="<?php echo $this->get_field_name( 'section_title' ); ?>" value="<?php if ( $section_title ) echo $section_title; ?>" />
		</p>

		<?php $this->section_background_input_markup( $instance ); ?>

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

		if (isset( $new_instance['organic_widgets_blog_posts_section_title'] ) )
			$instance['organic_widgets_blog_posts_section_title'] = strip_tags( $new_instance['organic_widgets_blog_posts_section_title'] );
		if (isset( $new_instance['organic_widgets_blog_posts_section_bg_image_id'] ) )
			$instance['organic_widgets_blog_posts_section_bg_image_id'] = strip_tags( $new_instance['organic_widgets_blog_posts_section_bg_image_id'] );
		if (isset( $new_instance['organic_widgets_blog_posts_section_bg_image'] ) )
			$instance['organic_widgets_blog_posts_section_bg_image'] = strip_tags( $new_instance['organic_widgets_blog_posts_section_bg_image'] );

		return $instance;
	}

	/**
	 * Render the image html output.
	 *
	 * @param array $instance
	 * @param bool $include_link will only render the link if this is set to true. Otherwise link is ignored.
	 * @return string image html
	 */
	protected function get_image_HTML( $instance ) {

		if ( isset( $instance['organic_widgets_blog_posts_section_bg_image_id'] ) && '' != $instance['organic_widgets_blog_posts_section_bg_image_id'] ) {
			$organic_widgets_blog_posts_section_bg_image_id = $instance['organic_widgets_blog_posts_section_bg_image_id'];
		} else { $organic_widgets_blog_posts_section_bg_image_id = 0; }

		$output = '';

		$size = 'medium_large';

		$attr = array();
		$attr = apply_filters( 'image_widget_image_attributes', $attr, $instance );

		$img_array = wp_get_attachment_image_src($organic_widgets_blog_posts_section_bg_image_id, $size, false, $attr);

		// If there is an organic_widgets_blog_posts_section_bg_image, use it to render the image. Eventually we should kill this and simply rely on organic_widgets_blog_posts_section_bg_image_ids.
		if ( 0 < $organic_widgets_blog_posts_section_bg_image_id ) {

			$attr['src'] = $img_array[0];
			$attr = array_map( 'esc_attr', $attr );
			$output .= "<img ";
			foreach ( $attr as $name => $value ) {
				$output .= sprintf( ' %s="%s"', $name, $value );
			}
			$output .= ' />';
		} elseif( abs( $organic_widgets_blog_posts_section_bg_image_id ) > 0 ) {
			$output .= $img_array[0];
		}

		return $output;
	}

	/**
	 * Enqueue all the javascript.
	 */
	public function admin_setup() {
		wp_enqueue_media();
		wp_enqueue_script( 'blog-posts-section-widget-js', plugin_dir_url( __FILE__ ) . 'js/blog-posts-section-widget.js', array( 'jquery', 'media-upload', 'media-views' ) );

		wp_localize_script( 'blog-posts-section-widget-js', 'BlogPostsSectionWidget', array(
			'frame_title' => __( 'Select an Image', ORGANIC_WIDGETS_18N ),
			'button_title' => __( 'Insert Into Widget', ORGANIC_WIDGETS_18N ),
		) );

		wp_enqueue_style( 'organic_widgets-blog-posts-section-widget-css', plugin_dir_url( __FILE__ ) . 'css/blog-posts-section-widget.css' );
	}

} // class Organic_Widgets_Blog_Posts_Section_Widget

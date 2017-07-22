<?php
/* Registers a widget to show a Team subsection on a page */

// Block direct requests.
if ( !defined('ABSPATH') )
	die('-1');


add_action( 'widgets_init', function(){
	register_widget( 'Organic_Widgets_Testimonial_Section_Widget' );
});
/**
 * Adds Organic_Widgets_Testimonial_Section_Widget widget.
 */
class Organic_Widgets_Testimonial_Section_Widget extends Organic_Widgets_Custom_Widget {

	const CUSTOM_IMAGE_SIZE_SLUG = 'organic_widgets_widget_image_upload';

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'organic_widgets_testimonial_section', // Base ID
			__( 'Organic Testimonials', ORGANIC_WIDGETS_18N ), // Name
			array(
				'description' => __( 'A section displaying testimonial posts.', ORGANIC_WIDGETS_18N ),
				'customize_selective_refresh' => true,
			) // Args
		);

		$this->id_prefix = $this->get_field_id('');

		// Bg options
		$this->bg_options = array(
			'color' => true,
			'image' => true
		);

		// Admin Scripts
		add_action( 'sidebar_admin_setup', array( $this, 'admin_setup' ) );
		add_action( 'admin_footer-widgets.php', array( $this, 'render_control_template_scripts' ) );

		// Public scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'public_scripts') );
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
		$category = ( isset( $instance['category'] ) ) ? $instance['category'] : 0;
		$max_posts = ( isset( $instance['max_posts'] ) ) ? $instance['max_posts'] : 12;
		$posts_per_slide = ( isset( $instance['posts_per_slide'] ) ) ? $instance['posts_per_slide'] : 3;

		echo $args['before_widget'];
		?>

		<!-- BEGIN .organic-widgets-section -->
		<div class="organic-widgets-section organic-widgets-testimonial-section" <?php if ( 0 < $bg_image_id ) { ?>style="background-image:url(<?php echo $bg_image; ?>);"<?php } elseif ($bg_color) { ?>style="background-color:<?php echo $bg_color; ?>;"<?php } ?>>

			<?php
			if ( post_type_exists( 'jetpack-testimonial' ) ) {

				$post_type = 'jetpack-testimonial';
				$tax_query = array();

			} else {

				$post_type = 'post';
				$tax_query = array(
					array(
						'taxonomy' => 'category',
						'field'    => 'id',
						'terms'    => $category
					),
				);

			}

			$slideshow_query = new WP_Query( array(
				'posts_per_page' => $max_posts,
				'post_type' => $post_type,
				'suppress_filters' => 0,
				'tax_query' => $tax_query
			) );
			?>

			<?php if ( $slideshow_query->have_posts() ) { ?>

				<!-- BEGIN .organic-widgets-content -->
				<div class="organic-widgets-content">

					<?php if ( ! empty( $instance['title'] ) ) { ?>
						<h2 class="organic-widgets-title"><?php echo apply_filters( 'widget_title', $instance['title'] ); ?></h2>
					<?php } ?>

					<?php if ( ! empty( $instance['text'] ) ) { ?>
						<div class="organic-widgets-text"><?php echo apply_filters( 'the_content', $instance['text'] ); ?></div>
					<?php } ?>

					<!-- BEGIN .flexslider -->
					<div class="organic-widgets-flexslider loading" data-per-slide="<?php echo $posts_per_slide ?>">

					<div class="preloader"></div>

						<!-- BEGIN .slides -->
						<ul class="slides">

							<?php	while ( $slideshow_query->have_posts() ) {

							$slideshow_query->the_post();
							$thumb = ( get_the_post_thumbnail() ) ? wp_get_attachment_image_src( get_post_thumbnail_id(), 'organic-widgets-featured-large' ) : false; ?>

							<li <?php post_class(); ?> id="post-<?php the_ID(); ?>">

								<article>

								<!-- BEGIN .organic-widgets-testimonial-slide-content -->
								<div class="organic-widgets-testimonial-slide-content">

									<!-- BEGIN .organic-widgets-card -->
									<div class="organic-widgets-card">

										<?php if ( has_post_thumbnail() ) { ?>

											<div class="organic-widgets-testimonial-avatar" style="background-image:url(<?php echo $thumb[0]; ?>);">
												<div class="organic-widgets-aspect-ratio-spacer"></div>
											</div>

										<?php } ?>

										<div class="organic-widgets-excerpt"><?php the_excerpt(); ?></div>

									<!-- END .organic-widgets-card -->
									</div>

									<p class="organic-widgets-testimonial-author"><?php the_title(); ?></p>

								<!-- END .organic-widgets-testimonial-slide-content -->
								</div>

								</article>

							</li>

							<?php } ?>

						<!-- END .slides -->
						</ul>

					<!-- END .flexslider -->
					</div>

				<!-- END .organic-widgets-content -->
				</div>

			<?php } ?>
			<?php wp_reset_postdata(); ?>

		<!-- END .organic-widgets-section -->
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

		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title' => '',
				'text' => '',
			)
		);

		// Setup Variables.
		$this->id_prefix = $this->get_field_id('');
		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		} else { $title = false; }
		if ( isset( $instance[ 'text' ] ) ) {
			$text = $instance[ 'text' ];
		} else { $text = ''; }
		if ( isset( $instance['category'] ) ) {
			$category = $instance['category'];
		} else { $category = false; }
		if ( isset( $instance['max_posts'] ) ) {
			$max_posts = $instance['max_posts'];
		} else { $max_posts = 12; }
		if ( isset( $instance['posts_per_slide'] ) ) {
			$posts_per_slide = $instance['posts_per_slide'];
		} else { $posts_per_slide = 3; }
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

		<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" class="title" type="hidden" value="<?php echo $title; ?>">
		<input id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" class="text organic-widgets-wysiwyg-anchor" type="hidden" value="<?php echo $text; ?>">

		<?php if ( ! post_type_exists( 'jetpack-testimonial' ) ) { ?>
			<p>
				<label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e('Testimonial Category:', ORGANIC_WIDGETS_18N) ?></label>
				<?php wp_dropdown_categories( array(
					'selected' => $category,
					'id' => $this->get_field_id( 'category' ),
					'name' => $this->get_field_name( 'category' )
				)); ?>
			</p>
		<?php } ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'max_posts' ); ?>"><?php _e('Max Number of Posts:', ORGANIC_WIDGETS_18N) ?></label>
			<input type="number" min="1" max="12" value="<?php echo $max_posts; ?>" id="<?php echo $this->get_field_id('max_posts'); ?>" name="<?php echo $this->get_field_name('max_posts'); ?>" class="widefat" style="width:100%;"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'posts_per_slide' ); ?>"><?php _e('Posts Per Slide:', ORGANIC_WIDGETS_18N) ?></label>
			<select id="<?php echo $this->get_field_id( 'posts_per_slide' ); ?>" name="<?php echo $this->get_field_name( 'posts_per_slide' ); ?>" class="widefat" style="width:100%;">
				<option <?php selected( $posts_per_slide, '1'); ?> value="1">1</option>
				<option <?php selected( $posts_per_slide, '2'); ?> value="2">2</option>
		    <option <?php selected( $posts_per_slide, '3'); ?> value="3">3</option>
			</select>
		</p>

		<?php $this->section_background_input_markup( $instance, $this->bg_options ); ?>

  <?php
	}

	/**
	 * Render form template scripts.
	 *
	 *
	 * @access public
	 */
	public function render_control_template_scripts() {

		?>
		<script type="text/html" id="tmpl-widget-organic_widgets_testimonial_section-control-fields">

			<# var elementIdPrefix = 'el' + String( Math.random() ).replace( /\D/g, '' ) + '_' #>

			<p><b><?php _e('Add Custom Content:', ORGANIC_WIDGETS_18N) ?></b></p>

			<p>
				<label for="{{ elementIdPrefix }}title"><?php esc_html_e( 'Title:' ); ?></label>
				<input id="{{ elementIdPrefix }}title" type="text" class="widefat title">
			</p>
			<p>
				<label for="{{ elementIdPrefix }}text" class="screen-reader-text"><?php esc_html_e( 'Content:' ); ?></label>
				<textarea id="{{ elementIdPrefix }}text" class="widefat text wp-editor-area" style="height: 200px" rows="16" cols="20"></textarea>
			</p>
		</script>
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
		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['text'] = $new_instance['text'];
		} else {
			$instance['text'] = wp_kses_post( $new_instance['text'] );
		}
		if ( isset( $new_instance['category'] ) )
			$instance['category'] = strip_tags( $new_instance['category'] );
		if ( isset( $new_instance['max_posts'] ) )
			$instance['max_posts'] = strip_tags( $new_instance['max_posts'] );
		if ( isset( $new_instance['posts_per_slide'] ) )
			$instance['posts_per_slide'] = strip_tags( $new_instance['posts_per_slide'] );

		return $instance;
	}

	/**
	 * Enqueue admin javascript.
	 */
	public function admin_setup() {

		// Text Editor
		wp_enqueue_editor();
		wp_enqueue_script( 'organic-testimonial-section-widgets', plugin_dir_url( __FILE__ ) . 'js/testimonial-section-widgets.js', array( 'jquery' ) );
		wp_add_inline_script( 'organic-testimonial-section-widgets', 'wp.organicTestimonialSectionWidgets.init();', 'after' );

		wp_enqueue_media();

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_script( 'organic-widgets-module-color-picker', ORGANIC_WIDGETS_ADMIN_JS_DIR . 'organic-widgets-module-color-picker.js', array( 'jquery', 'media-upload', 'media-views', 'wp-color-picker' ) );

		wp_enqueue_script( 'organic-widgets-module-image-background', ORGANIC_WIDGETS_ADMIN_JS_DIR . 'organic-widgets-module-image-background.js', array( 'jquery', 'media-upload', 'media-views', 'wp-color-picker' ) );
		wp_localize_script( 'organic-widgets-module-image-background', 'SubpageWidget', array(
			'frame_title' => __( 'Select an Image', ORGANIC_WIDGETS_18N ),
			'button_title' => __( 'Insert Into Widget', ORGANIC_WIDGETS_18N ),
		) );

	}

	/**
	 * Enqueue public javascript.
	 */
	public function public_scripts() {
		wp_enqueue_script( 'organic-widgets-masonry', ORGANIC_WIDGETS_BASE_DIR . 'public/js/masonry.js', array( 'jquery', 'media-upload', 'media-views', 'masonry' ) );
		wp_enqueue_script( 'organic-widgets-flexslider', ORGANIC_WIDGETS_BASE_DIR . 'public/js/jquery.flexslider.js', array( 'jquery', 'media-upload', 'media-views', 'masonry' ) );
		wp_enqueue_script( 'organic-widgets-flexslider-initialize', ORGANIC_WIDGETS_BASE_DIR . 'public/js/flexslider.js', array( 'jquery', 'media-upload', 'media-views', 'masonry', 'organic-widgets-flexslider' ) );
		if ( ! wp_script_is('organic-widgets-backgroundimagebrightness-js') ) { wp_enqueue_script( 'organic-widgets-backgroundimagebrightness-js', ORGANIC_WIDGETS_BASE_DIR . 'public/js/jquery.backgroundbrightness.js', array( 'jquery' ) ); }
	}

} // class Organic_Widgets_Testimonial_Section_Widget

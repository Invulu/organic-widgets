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
			__( 'Testimonial Section', ORGANIC_WIDGETS_18N ), // Name
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
		$title = ( isset( $instance['title'] ) ) ? $instance['title'] : false;
		$summary = ( isset( $instance['summary'] ) ) ? $instance['summary'] : false;
		$category = ( isset( $instance['category'] ) ) ? $instance['category'] : 0;
		$max_posts = ( isset( $instance['max_posts'] ) ) ? $instance['max_posts'] : 10;

		echo $args['before_widget'];
		?>
		<!-- BEGIN .organic-widgets-section -->
		<div class="organic-widgets-section organic-widgets-testimonial-section<?php if ( 0 < $bg_image_id ) { ?> has-thumb text-white<?php } ?>" <?php if ( 0 < $bg_image_id ) { ?>style="background-image:url(<?php echo $bg_image; ?>);"<?php } elseif ($bg_color) { ?>style="background-color:<?php echo $bg_color; ?>;"<?php } ?>>

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

						<!-- BEGIN .flexslider -->
						<div class="organic-widgets-flexslider loading" data-speed="<?php echo get_theme_mod( 'gpp_transition_interval', '12000' ); ?>" data-transition="<?php echo get_theme_mod( 'gpp_transition_style', 'fade' ); ?>">

							<div class="preloader"></div>

							<!-- BEGIN .slides -->
							<ul class="slides">

								<?php	while ( $slideshow_query->have_posts() ) {

									$slideshow_query->the_post();
									$thumb = ( get_the_post_thumbnail() ) ? wp_get_attachment_image_src( get_post_thumbnail_id(), 'organic-widgets-featured-large' ) : false; ?>

									<li <?php post_class(); ?> id="post-<?php the_ID(); ?>">

										<!-- BEGIN .organic-widgets-testimonial-slide-content -->
										<div class="organic-widgets-testimonial-slide-content">

											<h3><?php echo $title; ?></h3>

											<?php if ( has_post_thumbnail() ) { ?>

												<div class="organic-widgets-testimonial-avatar" style="background-image:url(<?php echo $thumb[0]; ?>);">
													<div class="organic-widgets-aspect-ratio-spacer"></div>
												</div>

											<?php } ?>

											<!-- BEGIN .excerpt -->
											<div class="excerpt">

												<?php the_excerpt(); ?>

											<!-- END .excerpt -->
											</div>

											<h4>
												<a href="<?php echo get_the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
											</h4>

										<!-- END .organic-widgets-testimonial-slide-content -->
										</div>

									</li>

								<?php } ?>

							<!-- END .slides -->
							</ul>

						<!-- END .flexslider -->
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
		if ( isset( $instance['num_columns'] ) ) {
			$num_columns = $instance['num_columns'];
		} else { $num_columns = 3; }
		if ( isset( $instance['max_posts'] ) ) {
			$max_posts = $instance['max_posts'];
		} else { $max_posts = 10; }
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
			<label for="<?php echo $this->get_field_id( 'summary' ); ?>"><?php _e('Summary:', ORGANIC_WIDGETS_18N) ?></label>
			<textarea class="widefat" rows="6" cols="20" id="<?php echo $this->get_field_id( 'summary' ); ?>" name="<?php echo $this->get_field_name( 'summary' ); ?>"><?php echo $summary; ?></textarea>
		</p>
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
			<input type="number" min="1" max="10" value="<?php echo $max_posts; ?>" id="<?php echo $this->get_field_id('max_posts'); ?>" name="<?php echo $this->get_field_name('max_posts'); ?>" class="widefat" style="width:100%;"/>
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
			$instance['category'] = strip_tags( $new_instance['category'] );
		if ( isset( $new_instance['max_posts'] ) )
			$instance['max_posts'] = strip_tags( $new_instance['max_posts'] );

		return $instance;
	}

	/**
	 * Enqueue admin javascript.
	 */
	public function admin_setup() {

		wp_enqueue_media();
		wp_enqueue_script( 'testimonial-section-widget-js', plugin_dir_url( __FILE__ ) . 'js/testimonial-section-widget.js', array( 'jquery', 'media-upload', 'media-views' ) );
		wp_enqueue_style( 'organic-widgets-testimonial-section-widget-css', plugin_dir_url( __FILE__ ) . 'css/testimonial-section-widget.css' );

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
		// wp_enqueue_script( 'testimonial-section-widget-public-js', ORGANIC_WIDGETS_BASE_DIR . 'public/js/testimonial-section.js', array( 'jquery', 'media-upload', 'media-views', 'masonry' ) );
		wp_enqueue_script( 'organic-widgets-flexslider', ORGANIC_WIDGETS_BASE_DIR . 'public/js/jquery.flexslider.js', array( 'jquery', 'media-upload', 'media-views', 'masonry' ) );
		wp_enqueue_script( 'organic-widgets-flexslider-initialize', ORGANIC_WIDGETS_BASE_DIR . 'public/js/flexslider.js', array( 'jquery', 'media-upload', 'media-views', 'masonry', 'organic-widgets-flexslider' ) );
		if ( ! wp_script_is('organic-widgets-backgroundimagebrightness-js') ) { wp_enqueue_script( 'organic-widgets-backgroundimagebrightness-js', ORGANIC_WIDGETS_BASE_DIR . 'public/js/jquery.backgroundimagebrightness.js', array( 'jquery' ) ); }

	}



} // class Organic_Widgets_Testimonial_Section_Widget

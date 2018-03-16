<?php
/* Registers a widget to show a Team subsection on a page */

// Block direct requests.
if ( !defined('ABSPATH') )
	die('-1');

/**
 * Adds Organic_Widgets_Content_Slideshow_Section_Widget widget.
 */
class Organic_Widgets_Content_Slideshow_Section_Widget extends Organic_Widgets_Custom_Widget {

	const CUSTOM_IMAGE_SIZE_SLUG = 'organic_widgets_widget_image_upload';

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'organic_widgets_content_slideshow_section', // Base ID
			__( 'Organic Content Slideshow', ORGANIC_WIDGETS_18N ), // Name
			array(
				'description' => __( 'A content slideshow.', ORGANIC_WIDGETS_18N ),
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
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_setup' ) );

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
		$max_posts = ( isset( $instance['max_posts'] ) ) ? $instance['max_posts'] : 5;
		$slideshow_transition_style = ( isset( $instance['slideshow_transition_style'] ) ) ? $instance['slideshow_transition_style'] : 'fade';
		$slideshow_interval = ( isset( $instance['slideshow_interval'] ) ) ? $instance['slideshow_interval'] : 12000;
		if ( isset( $instance['fixed_slide_height'] ) ) {
			$fixed_slide_height = $instance['fixed_slide_height'];
		} else { $fixed_slide_height = 0; }

		echo $args['before_widget'];
		?>

		<!-- BEGIN .organic-widgets-section -->
		<div class="organic-widgets-section organic-widgets-content-slideshow-section" <?php if ( 0 < $bg_image_id ) { ?>style="background-image:url(<?php echo $bg_image; ?>);"<?php } elseif ($bg_color) { ?>style="background-color:<?php echo $bg_color; ?>;"<?php } ?>>

			<?php
				$post_type = 'post';
				$tax_query = array(
					array(
						'taxonomy' => 'category',
						'field'    => 'id',
						'terms'    => $category
					),
				);

			$slideshow_query = new WP_Query( array(
				'posts_per_page' => $max_posts,
				'post_type' => $post_type,
				'suppress_filters' => 0,
				'tax_query' => $tax_query
			) );
			?>

			<?php if ( $slideshow_query->have_posts() ) { ?>

				<!-- BEGIN .flexslider -->
				<div class="organic-widgets-flexslider loading" data-speed="<?php echo esc_attr($slideshow_interval); ?>" data-transition="<?php echo esc_attr($slideshow_transition_style); ?>" data-height="<?php echo esc_attr($fixed_slide_height); ?>">

					<div class="preloader"></div>

					<!-- BEGIN .slides -->
					<ul class="slides <?php if ( ! empty($fixed_slide_height) ) { echo 'organic-widgets-fixed-slide-height'; } ?>">

						<?php	while ( $slideshow_query->have_posts() ) {

							$slideshow_query->the_post();
							$thumb = ( get_the_post_thumbnail() ) ? wp_get_attachment_image_src( get_post_thumbnail_id(), 'organic-widgets-featured-large' ) : false; ?>

							<li <?php post_class(); ?> id="post-<?php the_ID(); ?>" <?php if ( has_post_thumbnail() ) { echo 'style="background-image:url(' . $thumb[0] . ')"'; } ?>>

								<!-- BEGIN .organic-widgets-aligner -->
								<div class="organic-widgets-aligner <?php if ( ! empty( $instance['alignment'] ) ) { echo 'organic-widgets-aligner-'.esc_attr( $instance['alignment'] ); } else { echo 'organic-widgets-aligner-middle-center'; } ?>">

									<!-- BEGIN .organic-widgets-content -->
									<div class="organic-widgets-content">

										<!-- BEGIN .organic-widgets-content-slideshow-slide-content -->
										<div class="organic-widgets-content-slideshow-slide-content organic-widgets-card">

											<h3><a href="<?php echo get_the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h3>

											<!-- BEGIN .organic-widgets-post-meta -->
											<div class="organic-widgets-post-meta">
												<p class="organic-widgets-post-date">
													<?php echo get_the_modified_date(); ?>
												</p>
												<p class="organic-widgets-post-author">
													<?php esc_html_e( 'By ', ORGANIC_WIDGETS_18N ); ?><?php esc_url( the_author_posts_link() ); ?>
												</p>
											<!-- END .organic-widgets-post-meta -->
											</div>

											<!-- BEGIN .excerpt -->
											<div class="organic-widgets-excerpt">

												<?php the_excerpt(); ?>

												<?php edit_post_link( esc_html__( '(Edit)', ORGANIC_WIDGETS_18N ), '<p>', '</p>' ); ?>

											<!-- END .organic-widgets-excerpt -->
											</div>

										<!-- END .organic-widgets-content-slideshow-slide-content -->
										</div>

									<!-- END .organic-widgets-content -->
									</div>

								<!-- END .organic-widgets-aligner -->
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

		<?php wp_enqueue_script( 'organic-widgets-flexslider', ORGANIC_WIDGETS_BASE_DIR . 'public/js/jquery.flexslider.js', array( 'jquery' ) ); ?>
		<?php wp_enqueue_script( 'organic-widgets-flexslider-initialize', ORGANIC_WIDGETS_BASE_DIR . 'public/js/flexslider.js', array( 'jquery', 'organic-widgets-flexslider' ) ); ?>

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

		if ( isset( $instance['category'] ) ) {
			$category = $instance['category'];
		} else { $category = false; }

		if ( isset( $instance['num_columns'] ) ) {
			$num_columns = $instance['num_columns'];
		} else { $num_columns = 3; }

		if ( isset( $instance['max_posts'] ) ) {
			$max_posts = $instance['max_posts'];
		} else { $max_posts = 5; }

		if ( isset( $instance['bg_color'] ) ) {
			$bg_color = $instance['bg_color'];
		} else { $bg_color = false; }

		if ( isset( $instance['bg_image_id'] ) ) {
			$bg_image_id = $instance['bg_image_id'];
		} else { $bg_image_id = 0; }

		if ( isset( $instance['bg_image_id'] ) && isset( $instance['bg_image'] ) ) {
			$bg_image = $instance['bg_image'];
		} else { $bg_image = false; }

		$slideshow_transition_style = ( isset( $instance['slideshow_transition_style'] ) ) ? $instance['slideshow_transition_style'] : 'fade';
		$slideshow_interval = ( isset( $instance['slideshow_interval'] ) ) ? $instance['slideshow_interval'] : 12000;
		$fixed_slide_height = ( isset( $instance['fixed_slide_height'] ) ) ? $instance['fixed_slide_height'] : 0;

		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e('Post Category:', ORGANIC_WIDGETS_18N) ?></label>
			<?php wp_dropdown_categories( array(
				'selected' => $category,
				'id' => $this->get_field_id( 'category' ),
				'name' => $this->get_field_name( 'category' )
			)); ?>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'slideshow_transition_style' ); ?>"><?php _e('Slide Transition Style:', ORGANIC_WIDGETS_18N) ?></label>
				<select id="<?php echo $this->get_field_id( 'slideshow_transition_style' ); ?>" name="<?php echo $this->get_field_name( 'slideshow_transition_style' ); ?>" class="widefat" style="width:100%;">
					<option <?php selected( $slideshow_transition_style, 'fade'); ?> value="fade"><?php _e('Fade', ORGANIC_WIDGETS_18N) ?></option>
					<option <?php selected( $slideshow_transition_style, 'slide'); ?> value="slide"><?php _e('Slide', ORGANIC_WIDGETS_18N) ?></option>
				</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'slideshow_interval' ); ?>"><?php _e('Slide Interval:', ORGANIC_WIDGETS_18N) ?></label>
				<select id="<?php echo $this->get_field_id( 'slideshow_interval' ); ?>" name="<?php echo $this->get_field_name( 'slideshow_interval' ); ?>" class="widefat" style="width:100%;">
					<option <?php selected( $slideshow_interval, '2000'); ?> value="2000"><?php _e('2 Seconds', ORGANIC_WIDGETS_18N) ?></option>
					<option <?php selected( $slideshow_interval, '4000'); ?> value="4000"><?php _e('4 Seconds', ORGANIC_WIDGETS_18N) ?></option>
					<option <?php selected( $slideshow_interval, '6000'); ?> value="6000"><?php _e('6 Seconds', ORGANIC_WIDGETS_18N) ?></option>
					<option <?php selected( $slideshow_interval, '8000'); ?> value="8000"><?php _e('8 Seconds', ORGANIC_WIDGETS_18N) ?></option>
					<option <?php selected( $slideshow_interval, '10000'); ?> value="10000"><?php _e('10 Seconds', ORGANIC_WIDGETS_18N) ?></option>
					<option <?php selected( $slideshow_interval, '12000'); ?> value="12000"><?php _e('12 Seconds', ORGANIC_WIDGETS_18N) ?></option>
					<option <?php selected( $slideshow_interval, '20000'); ?> value="20000"><?php _e('20 Seconds', ORGANIC_WIDGETS_18N) ?></option>
					<option <?php selected( $slideshow_interval, '30000'); ?> value="30000"><?php _e('30 Seconds', ORGANIC_WIDGETS_18N) ?></option>
					<option <?php selected( $slideshow_interval, '60000'); ?> value="60000"><?php _e('1 Minute', ORGANIC_WIDGETS_18N) ?></option>
					<option <?php selected( $slideshow_interval, '999999999'); ?> value="999999999"><?php _e('Hold Frame', ORGANIC_WIDGETS_18N) ?></option>
				</select>
		</p>

		<?php $this->content_aligner_input_markup( $instance ); ?>

		<p>
			<input class="checkbox" type="checkbox" value="1" <?php checked( $fixed_slide_height, '1' ); ?> id="<?php echo $this->get_field_id( 'fixed_slide_height' ); ?>" name="<?php echo $this->get_field_name( 'fixed_slide_height' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'fixed_slide_height' ); ?>"><?php _e('Fixed Height Slideshow', ORGANIC_WIDGETS_18N); ?></label>
		</p>

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
		if ( ! isset( $old_instance['created'] ) )
			$instance['created'] = time();
		if ( isset( $new_instance['bg_image_id'] ) )
			$instance['bg_image_id'] = strip_tags( $new_instance['bg_image_id'] );
		if ( isset( $new_instance['bg_image'] ) )
			$instance['bg_image'] = strip_tags( $new_instance['bg_image'] );
		if ( isset( $new_instance['bg_color'] ) && $this->check_hex_color( $new_instance['bg_color'] ) ) {
			$instance['bg_color'] = strip_tags( $new_instance['bg_color'] );
		} else {
			$instance['bg_color'] = false;
		}
		if ( isset( $new_instance['category'] ) )
			$instance['category'] = strip_tags( $new_instance['category'] );
		if ( isset( $new_instance['max_posts'] ) )
			$instance['max_posts'] = strip_tags( $new_instance['max_posts'] );
		if ( isset( $new_instance['alignment'] ) )
			$instance['alignment'] = strip_tags( $new_instance['alignment'] );
		if ( isset( $new_instance['slideshow_transition_style'] ) )
			$instance['slideshow_transition_style'] = $this->organic_widgets_sanitize_transition_style( strip_tags( $new_instance['slideshow_transition_style'] ) );
		if ( isset( $new_instance['slideshow_interval'] ) )
			$instance['slideshow_interval'] = $this->organic_widgets_sanitize_transition_interval( (int) strip_tags( $new_instance['slideshow_interval'] ) );
		if ( isset( $new_instance['fixed_slide_height'] ) && ! empty( $new_instance['fixed_slide_height'] ) ) {
			$instance['fixed_slide_height'] = 1;
		} else {
			$instance['fixed_slide_height'] = 0;
		}

		return $instance;
	}

	/**
	 * Enqueue admin javascript.
	 */
	public function admin_setup() {

		wp_enqueue_media();

		// Content Aligner
		wp_enqueue_script( 'organic-widgets-module-content-aligner', ORGANIC_WIDGETS_ADMIN_JS_DIR . 'organic-widgets-module-content-aligner.js', array( 'jquery' ) );

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_script( 'organic-widgets-module-color-picker', ORGANIC_WIDGETS_ADMIN_JS_DIR . 'organic-widgets-module-color-picker.js', array( 'jquery', 'wp-color-picker' ) );

		wp_enqueue_script( 'organic-widgets-module-image-background', ORGANIC_WIDGETS_ADMIN_JS_DIR . 'organic-widgets-module-image-background.js', array( 'jquery', 'media-upload', 'media-views', 'wp-color-picker' ) );
		wp_localize_script( 'organic-widgets-module-image-background', 'OrganicWidgetBG', array(
			'frame_title' => __( 'Select an Image', ORGANIC_WIDGETS_18N ),
			'button_title' => __( 'Insert Into Widget', ORGANIC_WIDGETS_18N ),
		) );

	}

	/**
	 * Enqueue public javascript.
	 */
	public function public_scripts() {

		// wp_enqueue_script( 'organic-widgets-flexslider', ORGANIC_WIDGETS_BASE_DIR . 'public/js/jquery.flexslider.js', array( 'jquery' ) );
		// wp_enqueue_script( 'organic-widgets-flexslider-initialize', ORGANIC_WIDGETS_BASE_DIR . 'public/js/flexslider.js', array( 'jquery', 'organic-widgets-flexslider' ) );
		if ( ! wp_script_is('organic-widgets-backgroundimagebrightness-js') ) { wp_enqueue_script( 'organic-widgets-backgroundimagebrightness-js', ORGANIC_WIDGETS_BASE_DIR . 'public/js/jquery.backgroundbrightness.js', array( 'jquery' ) ); }

	}

} // class Organic_Widgets_Content_Slideshow_Section_Widget

<?php
/* Registers a widget to show a Featured Content subsection on a page */

// Block direct requests.
if ( !defined('ABSPATH') )
	die( '-1' );

add_action( 'widgets_init', function() {
	register_widget( 'Organic_Widgets_Content_Widget' );
});

/**
 * Adds Organic_Widgets_Content_Widget widget.
 */
class Organic_Widgets_Content_Widget extends Organic_Widgets_Custom_Widget {

	const CUSTOM_IMAGE_SIZE_SLUG = 'organic_widgets_widget_image_upload';

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'organic_widgets_featured_content', // Base ID
			__( 'Featured Content', ORGANIC_WIDGETS_18N ), // Name
			array(
				'description' => __( 'A featured content widget for displaying a page summary or custom content.', ORGANIC_WIDGETS_18N ),
				'customize_selective_refresh' => true,
			) // Args
		);

		$this->id_prefix = $this->get_field_id('');

		// Bg options
		$this->bg_options = array(
			'color' => true,
			'image' => true
		);

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

		if ( ! empty( $instance['page_id'] ) ) {

			echo $args['before_widget'];
			$bg_color = isset( $instance['bg_color'] ) ? $instance['bg_color'] : false;
			$bg_image_id = isset( $instance['bg_image_id'] ) ? $instance['bg_image_id'] : false;
			$bg_image = ( isset( $instance['bg_image'] ) && '' != $instance['bg_image'] ) ? $instance['bg_image'] : false;
			$page_id = $instance['page_id'];
			$page_excerpt = $this->organic_widgets_get_the_excerpt($page_id);
			$page_title = get_the_title( $page_id );

			?>

			<!-- BEGIN .organic-widgets-section -->
			<div class="organic-widgets-section organic-widgets-featured-content-section<?php if ( 0 < $bg_image_id ) { ?> has-thumb text-white<?php } ?>" <?php if ( 0 < $bg_image_id ) { ?>style="background-image:url(<?php echo $bg_image; ?>);"<?php } elseif ($bg_color) { ?>style="background-color:<?php echo $bg_color; ?>;"<?php } ?>>

				<div class="holder">
					<div class="feature-img"><?php echo get_the_post_thumbnail( $page_id, 'organic-widgets-featured-medium' )?></div>
					<div class="information">
						<?php if ( ! empty( $page_title ) ) { ?>
							<h3><?php echo apply_filters( 'widget_title', $page_title ); ?></h3>
						<?php } ?>
						<?php if ( ! empty( $page_excerpt ) ) { ?>
							<div class="excerpt"><?php echo $page_excerpt; ?></div>
						<?php } ?>
						<a class="button" href="<?php echo get_the_permalink( $page_id );?>"><?php esc_html_e( 'Read More', ORGANIC_WIDGETS_18N ); ?></a>
					</div>
				</div>

			<!-- END .organic-widgets-section -->
			</div>

			<?php

			echo $args['after_widget'];

		} elseif ( ! empty( $instance['title'] ) || ! empty( $instance['summary'] ) ) {

			echo $args['before_widget'];

			$attr = array();
			$attr = apply_filters( 'image_widget_image_attributes', $attr, $instance );
			$bg_color = isset( $instance['bg_color'] ) ? $instance['bg_color'] : false;
			$bg_image_id = isset( $instance['bg_image_id'] ) ? $instance['bg_image_id'] : false;
			$bg_image = ( isset( $instance['bg_image'] ) && '' != $instance['bg_image'] ) ? $instance['bg_image'] : false;
			$title = $instance['title'];
			$summary = $instance['summary'];
			$link_url = $instance['link_url'];
			$link_title = $instance['link_title'];

			?>

			<!-- BEGIN .organic-widgets-section -->
			<div class="organic-widgets-section organic-widgets-featured-content-section<?php if ( 0 < $bg_image_id ) { ?> has-thumb text-white<?php } ?>" <?php if ( 0 < $bg_image_id ) { ?>style="background-image:url(<?php echo $bg_image; ?>);"<?php } elseif ($bg_color) { ?>style="background-color:<?php echo $bg_color; ?>;"<?php } ?>>

				<!-- BEGIN .organic-widgets-content -->
				<div class="organic-widgets-content">

					<div class="holder">
						<?php if ( 0 < $bg_image_id ) { ?>
							<div class="feature-img"><img src="<?php echo $bg_image; ?>" /></div>
						<?php } elseif ( '1' == get_option( 'fresh_site' ) ) { ?>
							<div class="feature-img"><img src="<?php echo get_template_directory_uri(); ?>/images/image-about.jpg" /></div>
						<?php } ?>
						<div class="information">
							<?php if ( ! empty( $title ) ) { ?>
								<h3><?php echo esc_html( $title ); ?></h3>
							<?php } ?>
							<?php if ( ! empty( $summary ) ) { ?>
								<div class="excerpt"><?php echo $summary ?></div>
							<?php } ?>
							<?php if ( ! empty( $link_url ) ) { ?>
								<a class="button" href="<?php echo esc_url( $link_url ); ?>">
									<?php if ( ! empty( $link_title ) ) { ?>
										<?php echo $link_title ?>
									<?php } else { ?>
										<?php esc_html_e( 'Read More', ORGANIC_WIDGETS_18N ); ?>
									<?php } ?>
								</a>
							<?php } ?>
						</div>
					</div>

				<!-- END .organic-widgets-content -->
				</div>

			<!-- END .organic-widgets-section -->
			</div>

			<?php

			echo $args['after_widget'];

		}

	}
	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		// Set up variables
		$this->id_prefix = $this->get_field_id('');
		$bg_color = ( isset( $instance['bg_color'] ) && '' != $instance['bg_color'] ) ? $instance['bg_color'] : false;
		if ( isset( $instance['bg_image_id'] ) && '' != $instance['bg_image_id'] ) {
			$bg_image_id = $instance['bg_image_id'];
		} else { $bg_image_id = 0; }
		if ( isset( $instance['bg_image'] ) && '' != $instance['bg_image'] ) {
			$bg_image = $instance['bg_image'];
		} else { $bg_image = false; }
		if ( isset( $instance[ 'page_id' ] ) ) {
			$page_id = $instance[ 'page_id' ];
		} else { $page_id = 0; }
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else { $title = ''; }
		if ( isset( $instance[ 'summary' ] ) ) {
			$summary = $instance[ 'summary' ];
		} else { $summary = ''; }
		if ( isset( $instance[ 'link_url' ] ) ) {
			$link_url = $instance[ 'link_url' ];
		} else { $link_url = ''; }
		if ( isset( $instance[ 'link_title' ] ) ) {
			$link_title = $instance[ 'link_title' ];
		} else { $link_title = ''; }
		?>

		<p><b><?php _e('Choose Existing Page:', ORGANIC_WIDGETS_18N) ?></b></p>

		<p>
			<?php wp_dropdown_pages( array(
				'class' => 'widefat',
				'selected' => $page_id,
				'id' => $this->get_field_id( 'page_id' ),
				'name' => $this->get_field_name( 'page_id' ),
				'show_option_none' => __( '— Select Existing Page —', ORGANIC_WIDGETS_18N ),
				'option_none_value' => '0',
			) ); ?>
		</p>

		<hr />

		<p><b><?php _e('Or Add Custom Content:', ORGANIC_WIDGETS_18N) ?></b></p>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', ORGANIC_WIDGETS_18N) ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'summary' ); ?>"><?php _e('Summary:', ORGANIC_WIDGETS_18N) ?></label>
			<textarea class="widefat" rows="6" cols="20" id="<?php echo $this->get_field_id( 'summary' ); ?>" name="<?php echo $this->get_field_name( 'summary' ); ?>"><?php echo $summary; ?></textarea>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'link_url' ); ?>"><?php _e('Link URL:', ORGANIC_WIDGETS_18N) ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'link_url' ); ?>" name="<?php echo $this->get_field_name( 'link_url' ); ?>" value="<?php echo $link_url; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'link_title' ); ?>"><?php _e('Link Text:', ORGANIC_WIDGETS_18N) ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'link_title' ); ?>" name="<?php echo $this->get_field_name( 'link_title' ); ?>" value="<?php echo $link_title; ?>" />
		</p>

		<hr/>

		<?php $this->section_background_input_markup( $instance, $this->bg_options );

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

		$instance = array();
		$instance['page_id'] = ( ! empty( $new_instance['page_id'] ) ) ? strip_tags( $new_instance['page_id'] ) : '';
		if ( isset( $new_instance['bg_color'] ) && $this->check_hex_color( $new_instance['bg_color'] ) ) {
			$instance['bg_color'] = strip_tags( $new_instance['bg_color'] );
		} else {
			$instance['bg_color'] = false;
		}
		$instance['bg_image_id'] = strip_tags( $new_instance['bg_image_id'] );
		$instance['bg_image'] = strip_tags( $new_instance['bg_image'] );
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['summary'] = strip_tags( $new_instance['summary'] );
		$instance['link_url'] = strip_tags( $new_instance['link_url'] );
		$instance['link_title'] = strip_tags( $new_instance['link_title'] );


		return $instance;

	}

	/**
	 * Get The Excerpt By Id
	 */
	private function organic_widgets_get_the_excerpt( $post_id ) {
		global $post;
		$save_post = $post;
		$post = get_post( $post_id );
		$output = $post->post_content;
		$post = $save_post;
		return $output;
	}

	/**
	 * Enqueue all the javascript.
	 */
	public function admin_setup() {

		wp_enqueue_media();
		wp_enqueue_script( 'organic-widgets-featured-content-widget-js', plugin_dir_url( __FILE__ ) . 'js/featured-content-widget.js', array( 'jquery', 'media-upload', 'media-views' ) );
		wp_enqueue_style( 'organic-widgets-featured-content-widget-css', plugin_dir_url( __FILE__ ) . 'css/featured-content-widget.css' );

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

		if ( ! wp_script_is('organic-widgets-backgroundimagebrightness-js') ) { wp_enqueue_script( 'organic-widgets-backgroundimagebrightness-js', ORGANIC_WIDGETS_BASE_DIR . 'public/js/jquery.backgroundbrightness.js', array( 'jquery' ) ); }

	}


} // class Organic_Widgets_Content_Widget

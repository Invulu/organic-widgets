<?php
/* Registers a widget to show a subsection on a page */

// Block direct requests.
if ( !defined('ABSPATH') )
	die( '-1' );

add_action( 'widgets_init', function(){
	register_widget( 'Organic_Widgets_Hero_Section_Widget' );
});

/**
 * Adds Organic_Widgets_Hero_Section_Widget widget.
 */
class Organic_Widgets_Hero_Section_Widget extends Organic_Widgets_Custom_Widget {

	const CUSTOM_IMAGE_SIZE_SLUG = 'organic_widgets_widget_image_upload';

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'organic_widgets_hero_section', // Base ID
			__( 'Hero Section', ORGANIC_WIDGETS_18N ), // Name
			array(
				'description' => __( 'A hero section with a background image and call to action.', ORGANIC_WIDGETS_18N ),
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

		$bg_image_id = isset( $instance['bg_image_id'] ) ? $instance['bg_image_id'] : false;
		$bg_image = ( isset( $instance['bg_image'] ) && '' != $instance['bg_image'] ) ? $instance['bg_image'] : false;
		$bg_color = ( isset( $instance['bg_color'] ) && '' != $instance['bg_color'] ) ? $instance['bg_color'] : false;
		$bg_video  = ( isset( $instance['bg_video'] ) && $instance['bg_video'] ) ? $instance['bg_video'] : false;
		$title = ( isset( $instance['title'] ) ) ? $instance['title'] : false;
		$summary = ( isset( $instance['summary'] ) ) ? $instance['summary'] : false;
		$button_text = ( isset( $instance['button_text'] ) && '' != $instance['button_text'] ) ? $instance['button_text'] : false;
		$button_url = ( isset( $instance['button_url'] ) && '' != $instance['button_url'] ) ? $instance['button_url'] : false;

		echo $args['before_widget'];

		?>

		<!-- BEGIN .organic-widgets-section -->
		<div class="organic-widgets-section organic-widgets-hero-section" <?php if ( 0 < $bg_image_id ) { ?>style="background-image:url(<?php echo $bg_image; ?>);"<?php } elseif ($bg_color) { ?>style="background-color:<?php echo $bg_color; ?>;"<?php } ?>>

			<?php
			// Video Background Section.
			if ( $bg_video ) {

				$this->video_bg_script( $bg_video, $this->id );

				$video_type = $this->get_video_type( $bg_video );
				if ( 'youtube' == $video_type ) {

					$video_id = $this->youtube_id_from_url( $bg_video );
				?>
					<div class="organic-widgets-video-bg-wrapper">
						<?php if ( 'youtube' == $video_type ) { ?><div id="organic-widgets-player<?php the_id(); ?>"></div><?php } ?>
					</div>

				<?php }
			} ?>

			<!-- BEGIN .organic-widgets-content -->
			<div class="organic-widgets-content">

				<?php if ( ! empty( $title ) || ! empty( $summary ) || ! empty( $button_url ) ) { ?>

				<!-- BEGIN .organic-widget-hero-information -->
				<div class="organic-widget-hero-information">

				<?php if ( ! empty( $title ) ) { ?>
					<h2 class="organic-widget-title"><?php echo esc_html( $title ); ?></h2>
				<?php } ?>
				<?php if ( ! empty( $summary ) ) { ?>
					<p class="organic-widget-summary"><?php echo $summary ?></p>
				<?php } ?>

				<?php if ( ! empty( $button_url ) ) { ?>
					<div class="organic-widgets-button-holder">
						<a class="organic-widgets-button button" href="<?php echo esc_url( $button_url );?>"><?php if ( ! empty( $button_text ) ) { echo esc_html( $button_text ); } else { _e( 'See More', ORGANIC_WIDGETS_18N); } ?></a>
					</div>
				<?php } ?>

				<!-- END .organic-widget-hero-information -->
				</div>

				<?php } ?>

			<!-- END .organic-widgets-content -->
			</div>

		<!-- END .organic-widgets-section -->
		</div>

		<?php

		echo $args['after_widget'];

	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		$this->id_prefix = $this->get_field_id('');

		if ( isset( $instance['bg_image_id'] ) ) {
			$bg_image_id = $instance['bg_image_id'];
		} else { $bg_image_id = 0; }

		if ( isset( $instance['bg_image_id'] ) && isset( $instance['bg_image'] ) ) {
			$bg_image = $instance['bg_image'];
		} else { $bg_image = false; }

		if ( isset( $instance['bg_video'] ) ) {
			$bg_video = $instance['bg_video'];
		} else { $bg_video = false; }

		if ( isset( $instance['bg_color'] ) ) {
			$bg_color = $instance['bg_color'];
		} else { $bg_color = false; }

		if ( isset( $instance['bg_color'] ) ) {
			$bg_color = $instance['bg_color'];
		} else { $bg_color = false; }

		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else { $title = ''; }

		if ( isset( $instance[ 'summary' ] ) ) {
			$summary = $instance[ 'summary' ];
		} else { $summary = ''; }

		if ( isset( $instance[ 'button_text' ] ) ) {
			$button_text = $instance[ 'button_text' ];
		} else { $button_text = false; }

		if ( isset( $instance[ 'button_url' ] ) ) {
			$button_url = $instance[ 'button_url' ];
		} else { $button_url = false; }

		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Section Title:', ORGANIC_WIDGETS_18N) ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'summary' ); ?>"><?php _e('Section Content:', ORGANIC_WIDGETS_18N) ?></label>
			<textarea class="widefat" rows="6" cols="20" id="<?php echo $this->get_field_id( 'summary' ); ?>" name="<?php echo $this->get_field_name( 'summary' ); ?>"><?php echo $summary; ?></textarea>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'button_text' ); ?>"><?php _e('Button Text:', ORGANIC_WIDGETS_18N); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'button_text' ); ?>" name="<?php echo $this->get_field_name( 'button_text' ); ?>" value="<?php echo $button_text; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'button_url' ); ?>"><?php _e('Button URL', ORGANIC_WIDGETS_18N); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'button_url' ); ?>" name="<?php echo $this->get_field_name( 'button_url' ); ?>" value="<?php echo $button_url; ?>" />
		</p>

		<hr />

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

		$instance = $old_instance;

		if ( isset( $new_instance['bg_image_id'] ) )
			$instance['bg_image_id'] = strip_tags( $new_instance['bg_image_id'] );
		if ( isset( $new_instance['bg_image'] ) )
			$instance['bg_image'] = strip_tags( $new_instance['bg_image'] );
		if ( isset( $new_instance['bg_video'] ) && $this->check_video_url( $new_instance['bg_video'] ) ) {
			$instance['bg_video'] = strip_tags( $new_instance['bg_video'] );
		} else {
			$instance['bg_video'] = false;
		}
		if ( isset( $new_instance['bg_color'] ) && $this->check_hex_color( $new_instance['bg_color'] ) ) {
			$instance['bg_color'] = strip_tags( $new_instance['bg_color'] );
		} else {
			$instance['bg_color'] = false;
		}
		if ( isset( $new_instance['title'] ) )
			$instance['title'] = strip_tags( $new_instance['title'] );
		if ( isset( $new_instance['summary'] ) )
			$instance['summary'] = strip_tags( $new_instance['summary'] );
		if ( isset( $new_instance['button_text'] ) )
			$instance['button_text'] = strip_tags( $new_instance['button_text'] );
		if ( isset( $new_instance['button_url'] ) )
			$instance['button_url'] = strip_tags( $new_instance['button_url'] );

		// Widget Title
		if ( isset( $new_instance['title'] )  && '' != $new_instance['title'] ) {
			$instance['title'] = strip_tags( $new_instance['title'] );
		} else {
			$instance['title'] = '';
		}

		return $instance;
	}

	/**
	 * Enqueue all the javascript.
	 */
	public function admin_setup() {

		wp_enqueue_media();
		wp_enqueue_script( 'organic-widgets-hero-widget-js', plugin_dir_url( __FILE__ ) . 'js/hero-widget.js', array( 'jquery', 'media-upload', 'media-views' ) );
		wp_enqueue_style( 'organic-widgets-hero-widget-css', plugin_dir_url( __FILE__ ) . 'css/hero-widget.css' );

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_script( 'organic-widgets-module-color-picker', ORGANIC_WIDGETS_ADMIN_JS_DIR . 'organic-widgets-module-color-picker.js', array( 'jquery', 'media-upload', 'media-views', 'wp-color-picker' ) );

		wp_enqueue_script( 'organic-widgets-module-image-background', ORGANIC_WIDGETS_ADMIN_JS_DIR . 'organic-widgets-module-image-background.js', array( 'jquery', 'media-upload', 'media-views', 'wp-color-picker' ) );
		wp_localize_script( 'organic-widgets-module-image-background', 'HeroWidget', array(
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

} // class Organic_Widgets_Hero_Section_Widget

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
			__( 'Organic Hero', ORGANIC_WIDGETS_18N ), // Name
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

		$bg_image_id = isset( $instance['bg_image_id'] ) ? $instance['bg_image_id'] : false;
		$bg_image = ( isset( $instance['bg_image'] ) && '' != $instance['bg_image'] ) ? $instance['bg_image'] : false;
		$bg_color = ( isset( $instance['bg_color'] ) && '' != $instance['bg_color'] ) ? $instance['bg_color'] : false;
		$bg_video  = ( isset( $instance['bg_video'] ) && $instance['bg_video'] ) ? $instance['bg_video'] : false;

		echo $args['before_widget'];

		?>

		<!-- BEGIN .organic-widgets-section -->
		<div class="organic-widgets-section organic-widgets-hero-section" <?php if ( 0 < $bg_image_id ) { ?>style="background-image:url(<?php echo $bg_image; ?>);"<?php } elseif ($bg_color) { ?>style="background-color:<?php echo $bg_color; ?>;"<?php } ?>>

			<?php
			// Video Background Section.
			if ( $bg_video ) {

				//Prep arguments
				$video_info = array(
					'video' => $bg_video,
					'video_type' => $this->get_video_type( $bg_video ),
					'video_id' => $this->youtube_id_from_url( $bg_video ),
					'widget_id' => $this->id,
					'clean_widget_id' => $this->sanitize_js_variable( $this->id )
				);

				// Add video bg to global var
				$this->add_video_bg( $video_info );

				// Output video HTML
				$this->video_bg_html($video_info);

			}?>

			<!-- BEGIN .organic-widgets-aligner -->
			<div class="organic-widgets-aligner <?php if ( ! empty( $instance['alignment'] ) ) { echo 'organic-widgets-aligner-'.esc_attr( $instance['alignment'] ); } else { echo 'organic-widgets-aligner-middle-center'; } ?>">

				<!-- BEGIN .organic-widgets-content -->
				<div class="organic-widgets-content">

					<?php if ( ! empty( $instance['title'] ) || ! empty( $instance['text'] ) || ! empty( $instance['button_url'] ) ) { ?>

						<!-- BEGIN .organic-widgets-hero-information -->
						<div class="organic-widgets-hero-information">

						<?php if ( ! empty( $instance['title'] ) ) { ?>
							<h2 class="organic-widgets-title"><?php echo apply_filters( 'widget_title', $instance['title'] ); ?></h2>
						<?php } ?>

						<?php if ( ! empty( $instance['text'] ) ) { ?>
							<div class="organic-widgets-text"><?php echo apply_filters( 'the_content', $instance['text'] ); ?></div>
						<?php } ?>

						<?php if ( ! empty( $instance['button_url'] ) ) { ?>
							<div class="organic-widgets-button-holder">
								<a class="organic-widgets-button button" href="<?php echo esc_url( $instance['button_url'] );?>"><?php if ( ! empty( $instance['button_text'] ) ) { echo esc_html( $instance['button_text'] ); } else { _e( 'See More', ORGANIC_WIDGETS_18N); } ?></a>
							</div>
						<?php } ?>

					<!-- END .organic-widgets-hero-information -->
					</div>

					<?php } ?>

				<!-- END .organic-widgets-content -->
				</div>

			<!-- END .organic-widgets-aligner -->
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

		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title' => '',
				'text' => '',
			)
		);

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

		?>

		<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" class="title" type="hidden" value="<?php if ( ! empty( $instance['title'] ) ) echo $instance['title']; ?>">
		<input id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" class="text organic-widgets-wysiwyg-anchor" type="hidden" value="<?php if ( ! empty( $instance['text'] ) ) echo $instance['text']; ?>">
		<p>
			<label for="<?php echo $this->get_field_id( 'button_text' ); ?>"><?php _e('Button Text:', ORGANIC_WIDGETS_18N); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'button_text' ); ?>" name="<?php echo $this->get_field_name( 'button_text' ); ?>" value="<?php if ( ! empty( $instance['button_text'] ) ) echo $instance['button_text']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'button_url' ); ?>"><?php _e('Button URL', ORGANIC_WIDGETS_18N); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'button_url' ); ?>" name="<?php echo $this->get_field_name( 'button_url' ); ?>" value="<?php if ( ! empty( $instance['button_url'] ) ) echo $instance['button_url']; ?>" />
		</p>

		<?php $this->content_aligner_input_markup( $instance ); ?>

		<?php $this->section_background_input_markup( $instance, $this->bg_options );

	}

	/**
	 * Render form template scripts.
	 *
	 *
	 * @access public
	 */
	public function render_control_template_scripts() {

		?>
		<script type="text/html" id="tmpl-widget-organic_widgets_hero_section-control-fields">

			<# var elementIdPrefix = 'el' + String( Math.random() ).replace( /\D/g, '' ) + '_' #>

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

		if ( isset( $new_instance['bg_image_id'] ) )
			$instance['bg_image_id'] = strip_tags( $new_instance['bg_image_id'] );
		if ( isset( $new_instance['bg_image'] ) )
			$instance['bg_image'] = strip_tags( $new_instance['bg_image'] );
		if ( isset( $new_instance['alignment'] ) )
			$instance['alignment'] = strip_tags( $new_instance['alignment'] );
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
		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['text'] = $new_instance['text'];
		} else {
			$instance['text'] = wp_kses_post( $new_instance['text'] );
		}
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

		// Text Editor
		wp_enqueue_editor();
		wp_enqueue_script( 'organic-widgets-hero-widgets', plugin_dir_url( __FILE__ ) . 'js/hero-widgets.js', array( 'jquery' ) );
		wp_add_inline_script( 'organic-widgets-hero-widgets', 'wp.organicHeroWidgets.init();', 'after' );

		// Content Aligner
		wp_enqueue_script( 'organic-widgets-module-content-aligner', ORGANIC_WIDGETS_ADMIN_JS_DIR . 'organic-widgets-module-content-aligner.js', array( 'jquery' ) );

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

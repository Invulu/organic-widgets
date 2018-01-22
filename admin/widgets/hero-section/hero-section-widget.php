<?php
/* Registers a widget to show a subsection on a page */

// Block direct requests.
if ( !defined('ABSPATH') )
	die( '-1' );

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
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_setup' ) );
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
		$featured_image_id = isset( $instance['featured_image_id'] ) ? $instance['featured_image_id'] : false;
		$featured_image = ( isset( $instance['featured_image'] ) && '' != $instance['featured_image'] ) ? $instance['featured_image'] : false;
		if ( isset( $instance['full_window_height'] ) ) {
			$full_window_height = $instance['full_window_height'];
		} else { $full_window_height = false; }
		if ( isset( $instance['bg_image_fixed'] ) ) {
			$bg_image_fixed = $instance['bg_image_fixed'];
		} else { $bg_image_fixed = false; }

		echo $args['before_widget'];

		?>

		<!-- BEGIN .organic-widgets-section -->
		<div class="organic-widgets-section organic-widgets-hero-section<?php if ($full_window_height) echo ' organic-widgets-full-height-section'; ?><?php if ($bg_image_fixed) echo ' organic-widgets-fixed-bg-img'; ?><?php if ($bg_video) echo ' ocw-bg-dark'; ?>" <?php if ( 0 < $bg_image_id ) { ?>style="background-image:url(<?php echo $bg_image; ?>);"<?php } elseif ($bg_color) { ?>style="background-color:<?php echo $bg_color; ?>;"<?php } ?>>

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

			}
			?>

			<!-- BEGIN .organic-widgets-aligner -->
			<div class="organic-widgets-aligner <?php if ( ! empty( $instance['alignment'] ) ) { echo 'organic-widgets-aligner-'.esc_attr( $instance['alignment'] ); } else { echo 'organic-widgets-aligner-middle-center'; } ?>">

				<!-- BEGIN .organic-widgets-content -->
				<div class="organic-widgets-content">

					<?php if ( ! empty( $instance['title'] ) || ! empty( $instance['text'] ) || ! empty( $instance['button_one_url'] ) ) { ?>

						<!-- BEGIN .organic-widgets-hero-information -->
						<div class="organic-widgets-hero-information">

						<?php if ( $featured_image_id > 0 ) { ?>
							<div class="organic-widgets-featured-image"><img src="<?php echo esc_url( $featured_image ); ?>" alt="<?php __( 'Featured Image', ORGANIC_WIDGETS_18N ) ?>" /></div>
						<?php } ?>

						<?php if ( ! empty( $instance['title'] ) ) { ?>
							<h1 class="organic-widgets-title"><?php echo apply_filters( 'organic_widget_title', $instance['title'] ); ?></h1>
						<?php } ?>

						<?php if ( ! empty( $instance['text'] ) ) { ?>
							<div class="organic-widgets-text"><?php echo apply_filters( 'the_content', $instance['text'] ); ?></div>
						<?php } ?>

						<?php if ( ! empty( $instance['button_one_url'] ) || ! empty( $instance['button_two_url'] ) ) { ?>
							<div class="organic-widgets-button-holder">
								<?php if ( ! empty( $instance['button_one_url'] ) ) { ?>
									<a class="organic-widgets-button button" href="<?php echo esc_url( $instance['button_one_url'] );?>"><?php if ( ! empty( $instance['button_one_text'] ) ) { echo esc_html( $instance['button_one_text'] ); } else { _e( 'See More', ORGANIC_WIDGETS_18N); } ?></a>
								<?php } ?>
								<?php if ( ! empty( $instance['button_two_url'] ) ) { ?>
									<a class="organic-widgets-button button alt" href="<?php echo esc_url( $instance['button_two_url'] );?>"><?php if ( ! empty( $instance['button_two_text'] ) ) { echo esc_html( $instance['button_two_text'] ); } else { _e( 'See More', ORGANIC_WIDGETS_18N); } ?></a>
								<?php } ?>
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

		if ( isset( $instance['featured_image_id'] ) ) {
			$featured_image_id = $instance['featured_image_id'];
		} else { $featured_image_id = 0; }

		if ( isset( $instance['featured_image_id'] ) && isset( $instance['featured_image'] ) ) {
			$featured_image = $instance['featured_image'];
		} else { $featured_image = false; }

		if ( isset( $instance['full_window_height'] ) ) {
			$full_window_height = $instance['full_window_height'];
		} else { $full_window_height = false; }

		if ( isset( $instance['bg_image_fixed'] ) ) {
			$bg_image_fixed = $instance['bg_image_fixed'];
		} else { $bg_image_fixed = false; }

		?>

		<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" class="title" type="hidden" value="<?php echo esc_attr( $instance['title'] ); ?>">
		<input id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" class="text organic-widgets-wysiwyg-anchor" type="hidden" value="<?php echo esc_attr( $instance['text'] ); ?>">
		<input id="<?php echo $this->get_field_id( 'filter' ); ?>" name="<?php echo $this->get_field_name( 'filter' ); ?>" class="filter" type="hidden" value="on">
		<input id="<?php echo $this->get_field_id( 'visual' ); ?>" name="<?php echo $this->get_field_name( 'visual' ); ?>" class="visual" type="hidden" value="on">

		<p>
			<label for="<?php echo $this->get_field_id( 'featured_image' ); ?>"><?php _e( 'Featured Image:', ORGANIC_WIDGETS_18N ); ?></label>
			<div class="uploader">
				<input type="submit" class="button" name="<?php echo $this->get_field_name('featured_image_uploader_button'); ?>" id="<?php echo $this->get_field_id('featured_image_uploader_button'); ?>" value="<?php if ( $featured_image_id ) { _e( 'Change Image', ORGANIC_WIDGETS_18N ); } else { _e( 'Select Image', ORGANIC_WIDGETS_18N ); }?>" onclick="organicWidgetFeaturedImage.uploader( '<?php echo $this->id; ?>', '<?php echo $this->id_prefix; ?>' ); return false;" />
				<input type="submit" class="organic-widgets-remove-image-button button" name="<?php echo $this->get_field_name('featured_image_remover_button'); ?>" id="<?php echo $this->get_field_id('featured_image_remover_button'); ?>" value="<?php _e('Remove Image', ORGANIC_WIDGETS_18N); ?>" onclick="organicWidgetFeaturedImage.remover( '<?php echo $this->id; ?>', '<?php echo $this->id_prefix; ?>', 'featured_image_remover_button' ); return false;" <?php if ( $featured_image_id < 1 ) { echo( 'style="display:none;"' ); } ?>/>
				<div class="organic-widgets-widget-image-preview" id="<?php echo $this->get_field_id('featured_image_preview'); ?>">
					<?php echo $this->get_featured_image_html($instance); ?>
				</div>
				<input type="hidden" id="<?php echo $this->get_field_id('featured_image_id'); ?>" name="<?php echo $this->get_field_name('featured_image_id'); ?>" value="<?php echo abs($featured_image_id); ?>" />
				<input type="hidden" id="<?php echo $this->get_field_id('featured_image'); ?>" name="<?php echo $this->get_field_name('featured_image'); ?>" value="<?php echo $featured_image; ?>" />
			</div>
		</p>

		<p class="organic-widgets-input-half">
			<label for="<?php echo $this->get_field_id( 'button_one_text' ); ?>"><?php _e('Featured Link Text:', ORGANIC_WIDGETS_18N); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'button_one_text' ); ?>" name="<?php echo $this->get_field_name( 'button_one_text' ); ?>" value="<?php if ( ! empty( $instance['button_one_text'] ) ) echo $instance['button_one_text']; ?>" />
		</p>
		<p class="organic-widgets-input-half last">
			<label for="<?php echo $this->get_field_id( 'button_one_url' ); ?>"><?php _e('Featured Link URL:', ORGANIC_WIDGETS_18N); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'button_one_url' ); ?>" name="<?php echo $this->get_field_name( 'button_one_url' ); ?>" value="<?php if ( ! empty( $instance['button_one_url'] ) ) echo $instance['button_one_url']; ?>" />
		</p>

		<p class="organic-widgets-input-half">
			<label for="<?php echo $this->get_field_id( 'button_two_text' ); ?>"><?php _e('Alternate Link Text:', ORGANIC_WIDGETS_18N); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'button_two_text' ); ?>" name="<?php echo $this->get_field_name( 'button_two_text' ); ?>" value="<?php if ( ! empty( $instance['button_two_text'] ) ) echo $instance['button_two_text']; ?>" />
		</p>
		<p class="organic-widgets-input-half last">
			<label for="<?php echo $this->get_field_id( 'button_two_url' ); ?>"><?php _e('Alternate Link URL:', ORGANIC_WIDGETS_18N); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'button_two_url' ); ?>" name="<?php echo $this->get_field_name( 'button_two_url' ); ?>" value="<?php if ( ! empty( $instance['button_two_url'] ) ) echo $instance['button_two_url']; ?>" />
		</p>

		<?php $this->content_aligner_input_markup( $instance ); ?>

		<p>
			<input class="checkbox" type="checkbox" value="1" <?php checked( $full_window_height, '1' ); ?> id="<?php echo $this->get_field_id( 'full_window_height' ); ?>" name="<?php echo $this->get_field_name( 'full_window_height' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'full_window_height' ); ?>"><?php _e('Full Window Height Section', ORGANIC_WIDGETS_18N); ?></label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" value="1" <?php checked( $bg_image_fixed, '1' ); ?> id="<?php echo $this->get_field_id( 'bg_image_fixed' ); ?>" name="<?php echo $this->get_field_name( 'bg_image_fixed' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'bg_image_fixed' ); ?>"><?php _e('Fixed Position Background Image', ORGANIC_WIDGETS_18N); ?></label>
		</p>

		<?php $this->section_background_input_markup( $instance, $this->bg_options );

	}

	/**
	 * Render the featured image html output.
	 *
	 * @since    	1.0.0
	 *
	 * @param 	array 	$instance 		Widget instance
	 * @return 	string 	image html
	 */
	protected function get_featured_image_html( $instance ) {

		if ( isset( $instance['featured_image_id'] ) ) {
			$image_id = $instance['featured_image_id'];
		} else { $image_id = 0; }

		// If there is an featured_image, use it to render the image. Eventually we should kill this and simply rely on featured_image_ids.
		if ( (int) $image_id > 0 ) {
			$size = 'organic-widgets-featured-large';
			$img_array = wp_get_attachment_image( $image_id, 'full', false );
			if ( is_array( $img_array ) ) {
				$output = '<img src="'.$img_array[0].'" />';
			} else {
				$output = $img_array;
			}
		} else {
			$output = '';
		}

		return $output;

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

		/*--- Text/Title ----*/
		if ( ! isset( $newinstance['filter'] ) )
			$instance['filter'] = false;
		if ( ! isset( $newinstance['visual'] ) )
			$instance['visual'] = null;
		// Upgrade 4.8.0 format.
		if ( isset( $old_instance['filter'] ) && 'content' === $old_instance['filter'] ) {
			$instance['visual'] = true;
		}
		if ( 'content' === $new_instance['filter'] ) {
			$instance['visual'] = true;
		}
		if ( isset( $new_instance['visual'] ) ) {
			$instance['visual'] = ! empty( $new_instance['visual'] );
		}
		// Filter is always true in visual mode.
		if ( ! empty( $instance['visual'] ) ) {
			$instance['filter'] = true;
		}
		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['title'] = $new_instance['title'];
			$instance['text'] = $new_instance['text'];
		} else {
			$instance['title'] = wp_kses_post( $new_instance['title'] );
			$instance['text'] = wp_kses_post( $new_instance['text'] );
		}
		/*--- END Text/Title ----*/

		if ( ! isset( $old_instance['created'] ) )
			$instance['created'] = time();
		if ( isset( $new_instance['bg_image_id'] ) )
			$instance['bg_image_id'] = strip_tags( $new_instance['bg_image_id'] );
		if ( isset( $new_instance['bg_image'] ) )
			$instance['bg_image'] = strip_tags( $new_instance['bg_image'] );
		if ( isset( $new_instance['alignment'] ) )
			$instance['alignment'] = strip_tags( $new_instance['alignment'] );
		if ( isset( $new_instance['full_window_height'] ) ) {
			$instance['full_window_height'] = true;
		} else {
			$instance['full_window_height'] = false;
		}
		if ( isset( $new_instance['bg_image_fixed'] ) ) {
			$instance['bg_image_fixed'] = true;
		} else {
			$instance['bg_image_fixed'] = false;
		}
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
		if ( isset( $new_instance['featured_image_id'] ) )
			$instance['featured_image_id'] = strip_tags( $new_instance['featured_image_id'] );
		if ( isset( $new_instance['featured_image'] ) )
			$instance['featured_image'] = strip_tags( $new_instance['featured_image'] );
		if ( isset( $new_instance['button_one_text'] ) )
			$instance['button_one_text'] = strip_tags( $new_instance['button_one_text'] );
		if ( isset( $new_instance['button_one_url'] ) )
			$instance['button_one_url'] = strip_tags( $new_instance['button_one_url'] );
		if ( isset( $new_instance['button_two_text'] ) )
			$instance['button_two_text'] = strip_tags( $new_instance['button_two_text'] );
		if ( isset( $new_instance['button_two_url'] ) )
			$instance['button_two_url'] = strip_tags( $new_instance['button_two_url'] );

		return $instance;
	}

	/**
	 * Enqueue all the javascript.
	 */
	public function admin_setup() {

		// Text Editor
		wp_enqueue_editor();
		wp_enqueue_script( 'organic-widgets-hero-widgets-text-title', plugin_dir_url( __FILE__ ) . 'js/hero-widgets.js', array( 'jquery' ) );
		wp_localize_script( 'organic-widgets-hero-widgets-text-title', 'OrganicHeroWidget', array(
			'id_base' => $this->id_base,
		) );
		wp_add_inline_script( 'organic-widgets-hero-widgets-text-title', 'wp.organicHeroWidget.init();', 'after' );

		// Content Aligner
		wp_enqueue_script( 'organic-widgets-module-content-aligner', ORGANIC_WIDGETS_ADMIN_JS_DIR . 'organic-widgets-module-content-aligner.js', array( 'jquery' ) );

		wp_enqueue_media();

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_script( 'organic-widgets-module-color-picker', ORGANIC_WIDGETS_ADMIN_JS_DIR . 'organic-widgets-module-color-picker.js', array( 'jquery', 'wp-color-picker' ) );

		wp_enqueue_script( 'organic-widgets-module-image-background', ORGANIC_WIDGETS_ADMIN_JS_DIR . 'organic-widgets-module-image-background.js', array( 'jquery', 'media-upload', 'media-views', 'wp-color-picker' ) );
		wp_localize_script( 'organic-widgets-module-image-background', 'HeroWidget', array(
			'frame_title' => __( 'Select an Image', ORGANIC_WIDGETS_18N ),
			'button_title' => __( 'Insert Into Widget', ORGANIC_WIDGETS_18N ),
		) );

		wp_enqueue_script( 'organic-widgets-module-image-featured', ORGANIC_WIDGETS_ADMIN_JS_DIR . 'organic-widgets-module-image-featured.js', array( 'jquery', 'media-upload', 'media-views' ) );
		wp_localize_script( 'organic-widgets-module-image-featured', 'OrganicWidgetIMG', array(
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

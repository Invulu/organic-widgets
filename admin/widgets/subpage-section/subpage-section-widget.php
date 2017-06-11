<?php
/* Registers a widget to show a subsection on a page */

// Block direct requests.
if ( !defined('ABSPATH') )
	die( '-1' );

add_action( 'widgets_init', function(){
	register_widget( 'Organic_Widgets_Subpage_Section_Widget' );
});

/**
 * Adds Organic_Widgets_Subpage_Section_Widget widget.
 */
class Organic_Widgets_Subpage_Section_Widget extends Organic_Widgets_Custom_Widget {

	const CUSTOM_IMAGE_SIZE_SLUG = 'organic_widgets_widget_image_upload';

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'organic_widgets_subpage_section', // Base ID
			__( 'Subpage Section', ORGANIC_WIDGETS_18N ), // Name
			array(
				'description' => __( 'A subpage\'s content displayed as a section of another page.', ORGANIC_WIDGETS_18N ),
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

		if ( ! empty( $instance['page_id'] ) ) {

			// Get Page Info
			$page_id = $instance['page_id'];
			$the_featured_image = get_the_post_thumbnail_url( $page_id, 'organic_widgets-featured-large' );

			$page_query = new WP_Query(array(
				'post_type'	 				=> 'page',
				'page_id' 					=> $page_id,
				'posts_per_page' 		=> 1,
			) );

			echo $args['before_widget'];

			?>

			<?php if ( $page_query->have_posts() ) : while ( $page_query->have_posts() ) : $page_query->the_post(); ?>

			<div class="organic_widgets-subpage-section<?php if ( has_post_thumbnail( $page_id ) ) { ?> has-thumb text-white<?php } ?>" <?php if ( has_post_thumbnail( $page_id ) ) { ?>style="background-image:url(<?php echo $the_featured_image; ?>);"<?php } ?>>

				<?php the_content( esc_html__( 'Read More', ORGANIC_WIDGETS_18N ) ); ?>

			</div>

			<?php endwhile; ?>
			<?php endif; ?>
			<?php wp_reset_postdata(); ?>

			<?php echo $args['after_widget'];

		} elseif ( ! empty( $instance['section_title'] ) || ! empty( $instance['subpage_summary'] ) ) {

			$bg_image_id = isset( $instance['bg_image_id'] ) ? $instance['bg_image_id'] : false;
			$bg_image = ( isset( $instance['bg_image'] ) && '' != $instance['bg_image'] ) ? $instance['bg_image'] : false;
			$bg_color = ( isset( $instance['bg_color'] ) && '' != $instance['bg_color'] ) ? $instance['bg_color'] : false;
			$bg_video  = ( isset( $instance['bg_video'] ) && $instance['bg_video'] ) ? $instance['bg_video'] : false;
			$section_title = $instance['section_title'];
			$subpage_summary = $instance['subpage_summary'];

			echo $args['before_widget'];

			?>

			<div class="organic_widgets-section organic_widgets-subpage-section<?php if ( 0 < $bg_image_id ) { ?> has-thumb text-white<?php } ?>" <?php if ( 0 < $bg_image_id ) { ?>style="background-image:url(<?php echo $bg_image; ?>);"<?php } elseif ($bg_color) { ?>style="background-color:<?php echo $bg_color; ?>;"<?php } ?>>

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

				<?php if ( ! empty( $section_title ) ) { ?>
					<h3 class="headline text-center"><?php echo esc_html( $section_title ); ?></h3>
				<?php } ?>
				<?php if ( ! empty( $subpage_summary ) ) { ?>
					<p class="summary"><?php echo $subpage_summary ?></p>
				<?php } ?>

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



		if ( isset( $instance[ 'page_id' ] ) ) {
			$page_id = $instance[ 'page_id' ];
		} else { $page_id = 0; }

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

		if ( isset( $instance[ 'section_title' ] ) ) {
			$section_title = $instance[ 'section_title' ];
		} else { $section_title = ''; }

		if ( isset( $instance[ 'subpage_summary' ] ) ) {
			$subpage_summary = $instance[ 'subpage_summary' ];
		} else { $subpage_summary = ''; }



		?>

		<h3><?php _e('Choose Existing Page:', ORGANIC_WIDGETS_18N) ?></h3>

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

		<h3><?php _e('Or Add Custom Content:', ORGANIC_WIDGETS_18N) ?></h3>

		<p>
			<label for="<?php echo $this->get_field_id( 'section_title' ); ?>"><?php _e('Section Title:', ORGANIC_WIDGETS_18N) ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'section_title' ); ?>" name="<?php echo $this->get_field_name( 'section_title' ); ?>" value="<?php echo $section_title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'subpage_summary' ); ?>"><?php _e('Section Content:', ORGANIC_WIDGETS_18N) ?></label>
			<textarea class="widefat" rows="6" cols="20" id="<?php echo $this->get_field_id( 'subpage_summary' ); ?>" name="<?php echo $this->get_field_name( 'subpage_summary' ); ?>"><?php echo $subpage_summary; ?></textarea>
		</p>

		<?php $this->section_background_input_markup( $instance ); ?>

		<hr />

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

		if ( isset( $new_instance['page_id'] ) && $new_instance['page_id'] > 0 );
			$instance['page_id'] = strip_tags( $new_instance['page_id'] );
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
		if ( isset( $new_instance['section_title'] ) )
			$instance['section_title'] = strip_tags( $new_instance['section_title'] );
		if ( isset( $new_instance['subpage_summary'] ) )
			$instance['subpage_summary'] = strip_tags( $new_instance['subpage_summary'] );


		//Widget Title
		if ( isset( $new_instance['page_id'] ) && $new_instance['page_id'] > 0 ) {
			$instance['title'] = strip_tags( get_the_title( $instance['page_id'] ) );
		}
		elseif ( isset( $new_instance['section_title'] )  && '' != $new_instance['section_title'] ) {
			$instance['title'] = strip_tags( $new_instance['section_title'] );
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
		wp_enqueue_script( 'organic_widgets-subpage-widget-js', plugin_dir_url( __FILE__ ) . 'js/subpage-widget.js', array( 'jquery', 'media-upload', 'media-views' ) );

		// Scripts for Color Picker
    wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'organic-widgets-module-color-picker', ORGANIC_WIDGETS_ADMIN_JS_DIR . 'organic-widgets-module-color-picker.js', array( 'jquery', 'media-upload', 'media-views', 'wp-color-picker' ) );

		// Scripts for Image Background Module
		wp_enqueue_script( 'organic-widgets-module-image-background', ORGANIC_WIDGETS_ADMIN_JS_DIR . 'organic-widgets-module-image-background.js', array( 'jquery', 'media-upload', 'media-views', 'wp-color-picker' ) );
		wp_localize_script( 'organic-widgets-module-image-background', 'SubpageWidget', array(
			'frame_title' => __( 'Select an Image', ORGANIC_WIDGETS_18N ),
			'button_title' => __( 'Insert Into Widget', ORGANIC_WIDGETS_18N ),
		) );

		wp_enqueue_style( 'organic_widgets-subpage-widget-css', plugin_dir_url( __FILE__ ) . 'css/subpage-widget.css' );

	}

} // class Organic_Widgets_Subpage_Section_Widget

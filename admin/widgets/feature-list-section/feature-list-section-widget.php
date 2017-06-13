<?php
/* Registers a widget to show a subsection on a page */

// Block direct requests.
if ( !defined('ABSPATH') )
	die( '-1' );

add_action( 'widgets_init', function(){
	register_widget( 'Organic_Widgets_Feature_List_Section_Widget' );
});

/**
 * Adds Organic_Widgets_Feature_List_Section_Widget widget.
 */
class Organic_Widgets_Feature_List_Section_Widget extends Organic_Widgets_Custom_Widget {

	const CUSTOM_IMAGE_SIZE_SLUG = 'organic_widgets_widget_image_upload';

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'organic_widgets_feature_list_section', // Base ID
			__( 'Feature List Section', ORGANIC_WIDGETS_18N ), // Name
			array(
				'description' => __( 'A list of features with icons.', ORGANIC_WIDGETS_18N ),
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

			<!-- BEGIN .organic_widgets-section -->
			<div class="organic_widgets-section organic_widgets-feature-list-section<?php if ( 0 < $bg_image_id ) { ?> has-thumb text-white<?php } ?>" <?php if ( 0 < $bg_image_id ) { ?>style="background-image:url(<?php echo $bg_image; ?>);"<?php } elseif ($bg_color) { ?>style="background-color:<?php echo $bg_color; ?>;"<?php } ?>>

			<!-- END .organic_widgets-section -->
			</div>

			<?php echo $args['after_widget'];

		} elseif ( ! empty( $instance['title'] ) || ! empty( $instance['feature_list_summary'] ) ) {

			$bg_image_id = isset( $instance['bg_image_id'] ) ? $instance['bg_image_id'] : false;
			$bg_image = ( isset( $instance['bg_image'] ) && '' != $instance['bg_image'] ) ? $instance['bg_image'] : false;
			$bg_color = ( isset( $instance['bg_color'] ) && '' != $instance['bg_color'] ) ? $instance['bg_color'] : false;
			$bg_video  = ( isset( $instance['bg_video'] ) && $instance['bg_video'] ) ? $instance['bg_video'] : false;
			$title = $instance['title'];
			$feature_list_summary = $instance['feature_list_summary'];
			$num_columns = ( isset( $instance['num_columns'] ) ) ? $instance['num_columns'] : 0;

			echo $args['before_widget'];


			?>

			<div class="organic_widgets-feature-list-section<?php if ( 0 < $bg_image_id ) { ?> has-thumb text-white<?php } ?>" <?php if ( 0 < $bg_image_id ) { ?>style="background-image:url(<?php echo $bg_image; ?>);"<?php } elseif ($bg_color) { ?>style="background-color:<?php echo $bg_color; ?>;"<?php } ?>>

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

				<?php if ( ! empty( $title ) ) { ?>
					<h3 class="headline text-center"><?php echo esc_html( $title ); ?></h3>
				<?php } ?>
				<?php if ( ! empty( $feature_list_summary ) ) { ?>
					<p class="summary"><?php echo $feature_list_summary ?></p>
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

		$this->id_prefix = $this->get_field_id('');

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

		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else { $title = ''; }

		if ( isset( $instance[ 'feature_list_summary' ] ) ) {
			$feature_list_summary = $instance[ 'feature_list_summary' ];
		} else { $feature_list_summary = ''; }

		if ( isset( $instance['num_columns'] ) ) {
			$num_columns = $instance['num_columns'];
		} else { $num_columns = 3; }



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

		<?php $this->section_background_input_markup( $instance, $this->bg_options ); ?>

		<hr />

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Section Title:', ORGANIC_WIDGETS_18N) ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'feature_list_summary' ); ?>"><?php _e('Section Content:', ORGANIC_WIDGETS_18N) ?></label>
			<textarea class="widefat" rows="6" cols="20" id="<?php echo $this->get_field_id( 'feature_list_summary' ); ?>" name="<?php echo $this->get_field_name( 'feature_list_summary' ); ?>"><?php echo $feature_list_summary; ?></textarea>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'num_columns' ); ?>"><?php _e('Number of Columns:', ORGANIC_WIDGETS_18N) ?></label>
			<select id="<?php echo $this->get_field_id('num_columns'); ?>" name="<?php echo $this->get_field_name('num_columns'); ?>" class="widefat" style="width:100%;">
		    <option <?php selected( $instance['num_columns'], '2'); ?> value="2">2</option>
		    <option <?php selected( $instance['num_columns'], '3'); ?> value="3">3</option>
		    <option <?php selected( $instance['num_columns'], '4'); ?> value="4">4</option>
			</select>
		</p>

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
		if ( isset( $new_instance['title'] ) )
			$instance['title'] = strip_tags( $new_instance['title'] );
		if ( isset( $new_instance['feature_list_summary'] ) )
			$instance['feature_list_summary'] = strip_tags( $new_instance['feature_list_summary'] );
		if ( isset( $new_instance['num_columns'] ) )
			$instance['num_columns'] = strip_tags( $new_instance['num_columns'] );



		//Widget Title
		if ( isset( $new_instance['page_id'] ) && $new_instance['page_id'] > 0 ) {
			$instance['title'] = strip_tags( get_the_title( $instance['page_id'] ) );
		}
		elseif ( isset( $new_instance['title'] )  && '' != $new_instance['title'] ) {
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
		wp_enqueue_script( 'organic_widgets-feature-list-section-widget-js', plugin_dir_url( __FILE__ ) . 'js/feature-list-section-widget.js', array( 'jquery', 'media-upload', 'media-views' ) );
		wp_enqueue_style( 'organic_widgets-feature-list-section-widget-css', plugin_dir_url( __FILE__ ) . 'css/feature-list-section-widget.css' );

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_script( 'organic-widgets-module-color-picker', ORGANIC_WIDGETS_ADMIN_JS_DIR . 'organic-widgets-module-color-picker.js', array( 'jquery', 'media-upload', 'media-views', 'wp-color-picker' ) );

		wp_enqueue_script( 'organic-widgets-module-image-background', ORGANIC_WIDGETS_ADMIN_JS_DIR . 'organic-widgets-module-image-background.js', array( 'jquery', 'media-upload', 'media-views', 'wp-color-picker' ) );
		wp_localize_script( 'organic-widgets-module-image-background', 'SubpageWidget', array(
			'frame_title' => __( 'Select an Image', ORGANIC_WIDGETS_18N ),
			'button_title' => __( 'Insert Into Widget', ORGANIC_WIDGETS_18N ),
		) );

	}

} // class Organic_Widgets_Feature_List_Section_Widget

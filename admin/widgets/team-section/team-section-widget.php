<?php
/* Registers a widget to show a Team subsection on a page */

// Block direct requests.
if ( !defined('ABSPATH') )
	die('-1');


add_action( 'widgets_init', function(){
	register_widget( 'Organic_Widgets_Team_Section_Widget' );
});
/**
 * Adds Organic_Widgets_Team_Section_Widget widget.
 */
class Organic_Widgets_Team_Section_Widget extends Organic_Widgets_Custom_Widget {

	const CUSTOM_IMAGE_SIZE_SLUG = 'organic_widgets_widget_image_upload';

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'organic_widgets_team_section', // Base ID
			__( 'Team Section', ORGANIC_WIDGETS_18N ), // Name
			array(
				'description' => __( 'A section displaying team members.', ORGANIC_WIDGETS_18N ),
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

		$attr = array();
		$attr = apply_filters( 'image_widget_image_attributes', $attr, $instance );
		if ( isset( $instance['bg_image_id'] ) && '' != $instance['bg_image_id'] ) {
			$bg_image_id = $instance['bg_image_id'];
			$img_array = wp_get_attachment_image_src($bg_image_id, 'organic_widgets-featured-large', false, $attr);
			$bg_image = $img_array[0];
		} else { $bg_image_id = 0; }
		$title = isset( $instance['title'] ) ? $instance['title'] : false;

		echo $args['before_widget'];
		?>
		<!-- BEGIN .organic_widgets-section -->
		<div class="organic_widgets-section organic_widgets-team-section<?php if ( 0 < $bg_image_id ) { ?> has-thumb text-white<?php } ?>" <?php if ( 0 < $bg_image_id ) { ?>style="background-image:url(<?php echo $bg_image; ?>);"<?php } elseif ($bg_color) { ?>style="background-color:<?php echo $bg_color; ?>;"<?php } ?>>

			<!-- BEGIN .row -->
			<div class="row">

				<!-- BEGIN .content -->
				<div class="content">

					<?php if ( ! empty( $instance['title'] ) ) { ?>
						<h2 class="headline <?php if ( $bg_image_id > 0 ) { ?> text-white<?php } ?>"><?php echo apply_filters( 'widget_title', $instance['title'] ); ?></h2>
					<?php } ?>

					<!-- BEGIN .post-area -->
					<div class="post-area wide">

						<?php get_template_part( 'content/loop', 'team' ); ?>

					<!-- END .post-area -->
					</div>

				<!-- END .content -->
				</div>

			<!-- END .row -->
			</div>

		<!-- END .organic_widgets-section -->
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

		return $instance;
	}

	/**
	 * Enqueue all the javascript.
	 */
	public function admin_setup() {

		wp_enqueue_media();
		wp_enqueue_script( 'team-section-widget-js', plugin_dir_url( __FILE__ ) . 'js/team-section-widget.js', array( 'jquery', 'media-upload', 'media-views' ) );
		wp_enqueue_style( 'organic_widgets-team-section-widget-css', plugin_dir_url( __FILE__ ) . 'css/team-section-widget.css' );

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_script( 'organic-widgets-module-color-picker', ORGANIC_WIDGETS_ADMIN_JS_DIR . 'organic-widgets-module-color-picker.js', array( 'jquery', 'media-upload', 'media-views', 'wp-color-picker' ) );

		wp_enqueue_script( 'organic-widgets-module-image-background', ORGANIC_WIDGETS_ADMIN_JS_DIR . 'organic-widgets-module-image-background.js', array( 'jquery', 'media-upload', 'media-views', 'wp-color-picker' ) );
		wp_localize_script( 'organic-widgets-module-image-background', 'SubpageWidget', array(
			'frame_title' => __( 'Select an Image', ORGANIC_WIDGETS_18N ),
			'button_title' => __( 'Insert Into Widget', ORGANIC_WIDGETS_18N ),
		) );

	}

} // class Organic_Widgets_Team_Section_Widget

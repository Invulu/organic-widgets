<?php
/* Registers a widget to show a profile */

// Block direct requests.
if ( ! defined( 'ABSPATH' ) )
	die( '-1' );


add_action( 'widgets_init', function(){
	register_widget( 'Organic_Widgets_Profile_Widget' );
});
/**
 * Adds Organic_Widgets_Profile_Widget widget.
 */
class Organic_Widgets_Profile_Widget extends Organic_Widgets_Custom_Widget {

	const CUSTOM_IMAGE_SIZE_SLUG = 'organic_widgets_widget_image_upload';

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'organic_widgets_profile', // Base ID
			__( 'Profile', ORGANIC_WIDGETS_18N ), // Name
			array(
				'description' => __( 'Display a personal profile.', ORGANIC_WIDGETS_18N ),
				'customize_selective_refresh' => true,
			) // Args
		);

		$this->id_prefix = $this->get_field_id('');

		// Bg options
		$this->bg_options = array(
			'color' => true,
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

		$instance['bg_color'] = isset( $instance['bg_color'] ) ? $instance['bg_color'] : false;
		$instance['bg_image_id'] = isset( $instance['bg_image_id'] ) ? $instance['bg_image_id'] : false;
		$instance['bg_image'] = ( isset( $instance['bg_image'] ) && '' != $instance['bg_image'] ) ? $instance['bg_image'] : false;
		$instance['title'] = isset( $instance['title'] ) ? $instance['title'] : false;
		$instance['subtitle'] = isset( $instance['subtitle'] ) ? $instance['subtitle'] : false;
		$instance['summary'] = isset( $instance['summary'] ) ? $instance['summary'] : false;
		$instance['personal_url'] = isset( $instance['personal_url'] ) ? $instance['personal_url'] : false;
		$instance['twitter_url'] = isset( $instance['twitter_url'] ) ? $instance['twitter_url'] : false;
		$instance['linkedin_url'] = isset( $instance['linkedin_url'] ) ? $instance['linkedin_url'] : false;
		$instance['facebook_url'] = isset( $instance['facebook_url'] ) ? $instance['facebook_url'] : false;
		$instance['email'] = isset( $instance['email'] ) ? $instance['email'] : false;

		echo $args['before_widget'];
		?>

		<?php if ( $instance['title'] || '' != $instance['title'] || $instance['subtitle'] || '' != $instance['subtitle'] || $instance['summary'] || '' != $instance['summary'] ) { ?>

			<!-- BEGIN .organic-widgets-section -->
			<div class="organic-widgets-section organic_widgets-profile-section" <?php if ($instance['bg_color']) { ?>style="background-color:<?php echo $instance['bg_color']; ?>;"<?php } ?>>

				<!-- BEGIN .organic-widgets-profile -->
				<div class="organic-widgets-profile">

					<?php if ( $instance['bg_image_id'] > 0 ) { ?>
						<div class="organic-widgets-profile-img"><img src="<?php echo $instance['bg_image']; ?>" alt="<?php __( 'Profile Image', ORGANIC_WIDGETS_18N ) ?>" /></div>
					<?php } ?>

					<!-- BEGIN .organic-profile-content -->
					<div class="organic-profile-content">

					<?php if ( ! empty( $instance['title'] ) ) { ?>
						<h2 class="organic-widgets-profile-title"><?php echo apply_filters( 'widget_title', $instance['title'] ); ?></h2>
					<?php } ?>

					<?php if ( ! empty( $instance['subtitle'] ) ) { ?>
						<h3 class="organic-widgets-profile-sub-title"><?php echo $instance['subtitle']; ?></h3>
					<?php } ?>

						<div class="organic-widgets-profile-divider"></div>

					<?php if ( ! empty( $instance['summary'] ) ) { ?>
						<div class="organic-widgets-profile-excerpt"><?php echo $instance['summary']; ?></div>
					<?php } ?>

					<?php if ( ! empty( $instance['personal_url'] ) || ! empty( $instance['twitter_url'] ) || ! empty( $instance['linkedin_url'] ) || ! empty( $instance['facebook_url'] ) || ! empty( $instance['email'] ) ) { ?>

					<ul class="organic-widgets-social-icons">

						<?php if ( ! empty( $instance['personal_url'] ) ) { ?>
							<li><a href="<?php echo $instance['personal_url']; ?>" target="_blank"><span><?php esc_html_e( 'Personal Link', ORGANIC_WIDGETS_18N ); ?></span></a></li>
						<?php } ?>

						<?php if ( ! empty( $instance['twitter_url'] ) ) { ?>
							<li><a href="<?php echo $instance['twitter_url']; ?>" target="_blank"><span><?php esc_html_e( 'Twitter', ORGANIC_WIDGETS_18N ); ?></span></a></li>
						<?php } ?>

						<?php if ( ! empty( $instance['linkedin_url'] ) ) { ?>
							<li><a href="<?php echo $instance['linkedin_url']; ?>" target="_blank"><span><?php esc_html_e( 'LinkedIn', ORGANIC_WIDGETS_18N ); ?></span></a></li>
						<?php } ?>

						<?php if ( ! empty( $instance['facebook_url'] ) ) { ?>
							<li><a href="<?php echo $instance['facebook_url']; ?>" target="_blank"><span><?php esc_html_e( 'Facebook', ORGANIC_WIDGETS_18N ); ?></span></a></li>
						<?php } ?>

						<?php if ( ! empty( $instance['email'] ) ) { ?>
							<li><a href="mailto:<?php echo $instance['email']; ?>" target="_blank"><span><?php esc_html_e( 'Email', ORGANIC_WIDGETS_18N ); ?></span></a></li>
						<?php } ?>

					</ul>

					<?php } ?>

					<!-- END .organic-profile-content -->
					</div>

				<!-- END .organic-widgets-profile -->
				</div>

			<!-- END .organic-widgets-section -->
			</div>

		<?php } ?>

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

		$this->id_prefix = $this->get_field_id('');

		if (!isset( $instance['bg_image'] ) ) {
			$instance['bg_image'] = false;
		}
		if (!isset( $instance['bg_image_id'] ) || $instance['bg_image_id'] < 1 ) {
			$instance['bg_image_id'] = 0;
		}
		if (!isset( $instance['title'] ) ) {
			$instance['title'] = false;
		}
		if (!isset( $instance['subtitle'] ) ) {
			$instance['subtitle'] = false;
		}
		if (!isset( $instance['summary'] ) ) {
			$instance['summary'] = false;
		}
		if (!isset( $instance['personal_url'] ) ) {
			$instance['personal_url'] = false;
		}
		if (!isset( $instance['twitter_url'] ) ) {
			$instance['twitter_url'] = false;
		}
		if (!isset( $instance['linkedin_url'] ) ) {
			$instance['linkedin_url'] = false;
		}
		if (!isset( $instance['facebook_url'] ) ) {
			$instance['facebook_url'] = false;
		}
		if (!isset( $instance['email'] ) ) {
			$instance['email'] = false;
		} ?>

		<?php $this->bg_image_scripts(); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'bg_image' ); ?>"><?php _e( 'Profile Image:', ORGANIC_WIDGETS_18N ) ?></label>
			<div class="uploader">
				<input type="submit" class="button" name="<?php echo $this->get_field_name('uploader_button'); ?>" id="<?php echo $this->get_field_id('uploader_button'); ?>" value="<?php if ( $instance['bg_image_id'] ) { _e( 'Change Image', ORGANIC_WIDGETS_18N ); }else { _e( 'Select Image', ORGANIC_WIDGETS_18N ); }?>" onclick="subpageWidgetImage.uploader( '<?php echo $this->id; ?>', '<?php echo $this->id_prefix; ?>' ); return false;" />
				<input type="submit" class="organic_widgets-remove-image-button button" name="<?php echo $this->get_field_name('remover_button'); ?>" id="<?php echo $this->get_field_id('remover_button'); ?>" value="<?php _e('Remove Image', ORGANIC_WIDGETS_18N); ?>" onclick="subpageWidgetImage.remover( '<?php echo $this->id; ?>', '<?php echo $this->id_prefix; ?>', 'remover_button' ); return false;" <?php if ( $instance['bg_image_id'] < 1 ) { echo( 'style="display:none;"' ); } ?>/>
				<div class="organic_widgets-widget-image-preview" id="<?php echo $this->get_field_id('preview'); ?>">
					<?php echo $this->get_image_html($instance); ?>
				</div>
				<input type="hidden" id="<?php echo $this->get_field_id('bg_image_id'); ?>" name="<?php echo $this->get_field_name('bg_image_id'); ?>" value="<?php echo abs($instance['bg_image_id']); ?>" />
				<input type="hidden" id="<?php echo $this->get_field_id('bg_image'); ?>" name="<?php echo $this->get_field_name('bg_image'); ?>" value="<?php echo $instance['bg_image']; ?>" />
			</div>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', ORGANIC_WIDGETS_18N) ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php if ( $instance['title'] ) echo $instance['title']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'subtitle' ); ?>"><?php _e('Subtitle:', ORGANIC_WIDGETS_18N) ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'subtitle' ); ?>" name="<?php echo $this->get_field_name( 'subtitle' ); ?>" value="<?php if ( $instance['subtitle'] ) echo $instance['subtitle']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'summary' ); ?>"><?php _e('Summary:', ORGANIC_WIDGETS_18N) ?></label>
			<textarea class="widefat" rows="6" cols="20" id="<?php echo $this->get_field_id( 'summary' ); ?>" name="<?php echo $this->get_field_name( 'summary' ); ?>"><?php echo $instance['summary']; ?></textarea>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'personal_url' ); ?>"><?php _e('Personal Link or Social Media URL:', ORGANIC_WIDGETS_18N) ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'personal_url' ); ?>" name="<?php echo $this->get_field_name( 'personal_url' ); ?>" value="<?php echo $instance['personal_url']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'twitter_url' ); ?>"><?php _e('Twitter Profile URL:', ORGANIC_WIDGETS_18N) ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'twitter_url' ); ?>" name="<?php echo $this->get_field_name( 'twitter_url' ); ?>" value="<?php echo $instance['twitter_url']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'linkedin_url' ); ?>"><?php _e('LinkedIn Profile URL:', ORGANIC_WIDGETS_18N) ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'linkedin_url' ); ?>" name="<?php echo $this->get_field_name( 'linkedin_url' ); ?>" value="<?php echo $instance['linkedin_url']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'facebook_url' ); ?>"><?php _e('Facebook Profile URL:', ORGANIC_WIDGETS_18N) ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'facebook_url' ); ?>" name="<?php echo $this->get_field_name( 'facebook_url' ); ?>" value="<?php echo $instance['facebook_url']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'email' ); ?>"><?php _e('Email Address:', ORGANIC_WIDGETS_18N) ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'email' ); ?>" name="<?php echo $this->get_field_name( 'email' ); ?>" value="<?php echo $instance['email']; ?>" />
		</p>

		<hr/>

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

		if (isset( $new_instance['bg_image_id'] ) )
			$instance['bg_image_id'] = strip_tags( $new_instance['bg_image_id'] );
		if (isset( $new_instance['bg_image'] ) )
			$instance['bg_image'] = strip_tags( $new_instance['bg_image'] );
		if (isset( $new_instance['title'] ) )
			$instance['title'] = strip_tags( $new_instance['title'] );
		if (isset( $new_instance['subtitle'] ) )
			$instance['subtitle'] = strip_tags( $new_instance['subtitle'] );
		if (isset( $new_instance['summary'] ) )
			$instance['summary'] = strip_tags( $new_instance['summary'] );
		if (isset( $new_instance['personal_url'] ) )
			$instance['personal_url'] = strip_tags( $new_instance['personal_url'] );
		if (isset( $new_instance['twitter_url'] ) )
			$instance['twitter_url'] = strip_tags( $new_instance['twitter_url'] );
		if (isset( $new_instance['linkedin_url'] ) )
			$instance['linkedin_url'] = strip_tags( $new_instance['linkedin_url'] );
		if (isset( $new_instance['facebook_url'] ) )
			$instance['facebook_url'] = strip_tags( $new_instance['facebook_url'] );
		if (isset( $new_instance['email'] ) )
			$instance['email'] = strip_tags( $new_instance['email'] );
		if ( isset( $new_instance['bg_color'] ) && $this->check_hex_color( $new_instance['bg_color'] ) ) {
			$instance['bg_color'] = strip_tags( $new_instance['bg_color'] );
		} else {
			$instance['bg_color'] = false;
		}

		return $instance;

	}

	/**
	 * Enqueue all the javascript.
	 */
	public function admin_setup() {

		wp_enqueue_media();
		wp_enqueue_script( 'organic_widgets-profile-widget-js', plugin_dir_url( __FILE__ ) . 'js/profile-widget.js', array( 'jquery', 'media-upload', 'media-views' ) );
		wp_enqueue_style( 'organic_widgets-profile-widget-css', plugin_dir_url( __FILE__ ) . 'css/profile-widget.css' );

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

		wp_enqueue_style( 'organic-widgets-fontawesome', ORGANIC_WIDGETS_BASE_DIR . 'public/css/font-awesome.css' );

	}

} // class Organic_Widgets_Profile_Widget

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
class Organic_Widgets_Profile_Widget extends WP_Widget {

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
			) // Args
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

		$instance['organic_widgets_profile_bg_image_id'] = isset( $instance['organic_widgets_profile_bg_image_id'] ) ? $instance['organic_widgets_profile_bg_image_id'] : false;
		$instance['organic_widgets_profile_bg_image'] = ( isset( $instance['organic_widgets_profile_bg_image'] ) && '' != $instance['organic_widgets_profile_bg_image'] ) ? $instance['organic_widgets_profile_bg_image'] : false;
		$instance['organic_widgets_profile_title'] = isset( $instance['organic_widgets_profile_title'] ) ? $instance['organic_widgets_profile_title'] : false;
		$instance['organic_widgets_profile_subtitle'] = isset( $instance['organic_widgets_profile_subtitle'] ) ? $instance['organic_widgets_profile_subtitle'] : false;
		$instance['organic_widgets_profile_summary'] = isset( $instance['organic_widgets_profile_summary'] ) ? $instance['organic_widgets_profile_summary'] : false;
		$instance['organic_widgets_profile_personal_url'] = isset( $instance['organic_widgets_profile_personal_url'] ) ? $instance['organic_widgets_profile_personal_url'] : false;
		$instance['organic_widgets_profile_twitter_url'] = isset( $instance['organic_widgets_profile_twitter_url'] ) ? $instance['organic_widgets_profile_twitter_url'] : false;
		$instance['organic_widgets_profile_linkedin_url'] = isset( $instance['organic_widgets_profile_linkedin_url'] ) ? $instance['organic_widgets_profile_linkedin_url'] : false;
		$instance['organic_widgets_profile_facebook_url'] = isset( $instance['organic_widgets_profile_facebook_url'] ) ? $instance['organic_widgets_profile_facebook_url'] : false;
		$instance['organic_widgets_profile_email'] = isset( $instance['organic_widgets_profile_email'] ) ? $instance['organic_widgets_profile_email'] : false;

		echo $args['before_widget'];
		?>

		<!-- BEGIN .profile -->
		<div class="profile">

			<!-- BEGIN .holder -->
			<div class="holder radius-full">

				<?php if ( $instance['organic_widgets_profile_bg_image_id'] > 0 ) { ?>
					<div class="feature-img"><img src="<?php echo $instance['organic_widgets_profile_bg_image']; ?>" alt="<?php __( 'Profile Image', ORGANIC_WIDGETS_18N ) ?>" /></div>
				<?php } ?>

				<!-- BEGIN .information -->
				<div class="information">

				<?php if ( ! empty( $instance['organic_widgets_profile_title'] ) ) { ?>
					<h2 class="title"><?php echo apply_filters( 'widget_title', $instance['organic_widgets_profile_title'] ); ?></h2>
				<?php } ?>

				<?php if ( ! empty( $instance['organic_widgets_profile_subtitle'] ) ) { ?>
					<h3 class="sub-title"><?php echo $instance['organic_widgets_profile_subtitle']; ?></h3>
				<?php } ?>

				<?php if ( ! empty( $instance['organic_widgets_profile_summary'] ) ) { ?>
					<div class="excerpt"><?php echo $instance['organic_widgets_profile_summary']; ?></div>
				<?php } ?>

				<?php if ( ! empty( $instance['organic_widgets_profile_personal_url'] ) || ! empty( $instance['organic_widgets_profile_twitter_url'] ) || ! empty( $instance['organic_widgets_profile_linkedin_url'] ) || ! empty( $instance['organic_widgets_profile_facebook_url'] ) || ! empty( $instance['organic_widgets_profile_email'] ) ) { ?>

				<ul class="social-icons">

					<?php if ( ! empty( $instance['organic_widgets_profile_personal_url'] ) ) { ?>
						<li><a href="<?php echo $instance['organic_widgets_profile_personal_url']; ?>" target="_blank"><span><?php esc_html_e( 'Personal Link', ORGANIC_WIDGETS_18N ); ?></span></a></li>
					<?php } ?>

					<?php if ( ! empty( $instance['organic_widgets_profile_twitter_url'] ) ) { ?>
						<li><a href="<?php echo $instance['organic_widgets_profile_twitter_url']; ?>" target="_blank"><span><?php esc_html_e( 'Twitter', ORGANIC_WIDGETS_18N ); ?></span></a></li>
					<?php } ?>

					<?php if ( ! empty( $instance['organic_widgets_profile_linkedin_url'] ) ) { ?>
						<li><a href="<?php echo $instance['organic_widgets_profile_linkedin_url']; ?>" target="_blank"><span><?php esc_html_e( 'LinkedIn', ORGANIC_WIDGETS_18N ); ?></span></a></li>
					<?php } ?>

					<?php if ( ! empty( $instance['organic_widgets_profile_facebook_url'] ) ) { ?>
						<li><a href="<?php echo $instance['organic_widgets_profile_facebook_url']; ?>" target="_blank"><span><?php esc_html_e( 'Facebook', ORGANIC_WIDGETS_18N ); ?></span></a></li>
					<?php } ?>

					<?php if ( ! empty( $instance['organic_widgets_profile_email'] ) ) { ?>
						<li><a href="mailto:<?php echo $instance['organic_widgets_profile_email']; ?>" target="_blank"><span><?php esc_html_e( 'Email', ORGANIC_WIDGETS_18N ); ?></span></a></li>
					<?php } ?>

				</ul>

				<?php } ?>

				<!-- END .information -->
				</div>

			<!-- END .holder -->
			</div>

		<!-- END .profile -->
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

		$id_prefix = $this->get_field_id('');


		if (!isset( $instance['organic_widgets_profile_bg_image_id'] ) ) {
			$instance['organic_widgets_profile_bg_image_id'] = 0;
		}
		if (!isset( $instance['organic_widgets_profile_bg_image_id'] ) || $instance['organic_widgets_profile_bg_image_id'] < 1 ) {
			$instance['organic_widgets_profile_bg_image'] = false;
		}
		if (!isset( $instance['organic_widgets_profile_title'] ) ) {
			$instance['organic_widgets_profile_title'] = false;
		}
		if (!isset( $instance['organic_widgets_profile_subtitle'] ) ) {
			$instance['organic_widgets_profile_subtitle'] = false;
		}
		if (!isset( $instance['organic_widgets_profile_summary'] ) ) {
			$instance['organic_widgets_profile_summary'] = false;
		}
		if (!isset( $instance['organic_widgets_profile_personal_url'] ) ) {
			$instance['organic_widgets_profile_personal_url'] = false;
		}
		if (!isset( $instance['organic_widgets_profile_twitter_url'] ) ) {
			$instance['organic_widgets_profile_twitter_url'] = false;
		}
		if (!isset( $instance['organic_widgets_profile_linkedin_url'] ) ) {
			$instance['organic_widgets_profile_linkedin_url'] = false;
		}
		if (!isset( $instance['organic_widgets_profile_facebook_url'] ) ) {
			$instance['organic_widgets_profile_facebook_url'] = false;
		}
		if (!isset( $instance['organic_widgets_profile_email'] ) ) {
			$instance['organic_widgets_profile_email'] = false;
		}



		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'organic_widgets_profile_bg_image' ); ?>"><?php _e( 'Profile Image:', ORGANIC_WIDGETS_18N ) ?></label>
			<div class="uploader">
				<input type="submit" class="button" name="<?php echo $this->get_field_name('uploader_button'); ?>" id="<?php echo $this->get_field_id('uploader_button'); ?>" value="<?php _e( 'Select an Image', ORGANIC_WIDGETS_18N ); ?>" onclick="profileWidgetImage.uploader( '<?php echo $this->id; ?>', '<?php echo $id_prefix; ?>' ); return false;" />
				<input type="submit" class="organic_widgets-remove-image-button button" name="<?php echo $this->get_field_name('remover_button'); ?>" id="<?php echo $this->get_field_id('remover_button'); ?>" value="<?php _e('Remove Image', ORGANIC_WIDGETS_18N); ?>" onclick="profileWidgetImage.remover( '<?php echo $this->id; ?>', '<?php echo $id_prefix; ?>', 'remover_button' ); return false;" <?php if ( $instance['organic_widgets_profile_bg_image_id'] < 1 ) { echo( 'style="display:none;"' ); } ?>/>
				<div class="organic_widgets-widget-image-preview" id="<?php echo $this->get_field_id('preview'); ?>">
					<?php echo $this->get_image_html($instance); ?>
				</div>
				<input type="hidden" id="<?php echo $this->get_field_id('organic_widgets_profile_bg_image_id'); ?>" name="<?php echo $this->get_field_name('organic_widgets_profile_bg_image_id'); ?>" value="<?php echo abs($instance['organic_widgets_profile_bg_image_id']); ?>" />
				<input type="hidden" id="<?php echo $this->get_field_id('organic_widgets_profile_bg_image'); ?>" name="<?php echo $this->get_field_name('organic_widgets_profile_bg_image'); ?>" value="<?php echo $instance['organic_widgets_profile_bg_image']; ?>" />
			</div>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'organic_widgets_profile_title' ); ?>"><?php _e('Title:', ORGANIC_WIDGETS_18N) ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'organic_widgets_profile_title' ); ?>" name="<?php echo $this->get_field_name( 'organic_widgets_profile_title' ); ?>" value="<?php if ( $instance['organic_widgets_profile_title'] ) echo $instance['organic_widgets_profile_title']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'organic_widgets_profile_subtitle' ); ?>"><?php _e('Subtitle:', ORGANIC_WIDGETS_18N) ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'organic_widgets_profile_subtitle' ); ?>" name="<?php echo $this->get_field_name( 'organic_widgets_profile_subtitle' ); ?>" value="<?php if ( $instance['organic_widgets_profile_subtitle'] ) echo $instance['organic_widgets_profile_subtitle']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'organic_widgets_profile_summary' ); ?>"><?php _e('Summary:', ORGANIC_WIDGETS_18N) ?></label>
			<textarea class="widefat" rows="6" cols="20" id="<?php echo $this->get_field_id( 'organic_widgets_profile_summary' ); ?>" name="<?php echo $this->get_field_name( 'organic_widgets_profile_summary' ); ?>"><?php echo $instance['organic_widgets_profile_summary']; ?></textarea>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'organic_widgets_profile_personal_url' ); ?>"><?php _e('Personal Link or Social Media URL:', ORGANIC_WIDGETS_18N) ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'organic_widgets_profile_personal_url' ); ?>" name="<?php echo $this->get_field_name( 'organic_widgets_profile_personal_url' ); ?>" value="<?php echo $instance['organic_widgets_profile_personal_url']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'organic_widgets_profile_twitter_url' ); ?>"><?php _e('Twitter Profile URL:', ORGANIC_WIDGETS_18N) ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'organic_widgets_profile_twitter_url' ); ?>" name="<?php echo $this->get_field_name( 'organic_widgets_profile_twitter_url' ); ?>" value="<?php echo $instance['organic_widgets_profile_twitter_url']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'organic_widgets_profile_linkedin_url' ); ?>"><?php _e('LinkedIn Profile URL:', ORGANIC_WIDGETS_18N) ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'organic_widgets_profile_linkedin_url' ); ?>" name="<?php echo $this->get_field_name( 'organic_widgets_profile_linkedin_url' ); ?>" value="<?php echo $instance['organic_widgets_profile_linkedin_url']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'organic_widgets_profile_facebook_url' ); ?>"><?php _e('Facebook Profile URL:', ORGANIC_WIDGETS_18N) ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'organic_widgets_profile_facebook_url' ); ?>" name="<?php echo $this->get_field_name( 'organic_widgets_profile_facebook_url' ); ?>" value="<?php echo $instance['organic_widgets_profile_facebook_url']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'organic_widgets_profile_email' ); ?>"><?php _e('Email Address:', ORGANIC_WIDGETS_18N) ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'organic_widgets_profile_email' ); ?>" name="<?php echo $this->get_field_name( 'organic_widgets_profile_email' ); ?>" value="<?php echo $instance['organic_widgets_profile_email']; ?>" />
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

		if (isset( $new_instance['organic_widgets_profile_bg_image_id'] ) )
			$instance['organic_widgets_profile_bg_image_id'] = strip_tags( $new_instance['organic_widgets_profile_bg_image_id'] );
		if (isset( $new_instance['organic_widgets_profile_bg_image'] ) )
			$instance['organic_widgets_profile_bg_image'] = strip_tags( $new_instance['organic_widgets_profile_bg_image'] );
		if (isset( $new_instance['organic_widgets_profile_title'] ) )
			$instance['organic_widgets_profile_title'] = strip_tags( $new_instance['organic_widgets_profile_title'] );
		if (isset( $new_instance['organic_widgets_profile_subtitle'] ) )
			$instance['organic_widgets_profile_subtitle'] = strip_tags( $new_instance['organic_widgets_profile_subtitle'] );
		if (isset( $new_instance['organic_widgets_profile_summary'] ) )
			$instance['organic_widgets_profile_summary'] = strip_tags( $new_instance['organic_widgets_profile_summary'] );
		if (isset( $new_instance['organic_widgets_profile_personal_url'] ) )
			$instance['organic_widgets_profile_personal_url'] = strip_tags( $new_instance['organic_widgets_profile_personal_url'] );
		if (isset( $new_instance['organic_widgets_profile_twitter_url'] ) )
			$instance['organic_widgets_profile_twitter_url'] = strip_tags( $new_instance['organic_widgets_profile_twitter_url'] );
		if (isset( $new_instance['organic_widgets_profile_linkedin_url'] ) )
			$instance['organic_widgets_profile_linkedin_url'] = strip_tags( $new_instance['organic_widgets_profile_linkedin_url'] );
		if (isset( $new_instance['organic_widgets_profile_facebook_url'] ) )
			$instance['organic_widgets_profile_facebook_url'] = strip_tags( $new_instance['organic_widgets_profile_facebook_url'] );
		if (isset( $new_instance['organic_widgets_profile_email'] ) )
			$instance['organic_widgets_profile_email'] = strip_tags( $new_instance['organic_widgets_profile_email'] );

		return $instance;
	}

	/**
	 * Render the image html output.
	 *
	 * @param array $instance
	 * @param bool $include_link will only render the link if this is set to true. Otherwise link is ignored.
	 * @return string image html
	 */
	private function get_image_html( $instance ) {

		if ( isset( $instance['organic_widgets_profile_bg_image_id'] ) ) {
			$instance['organic_widgets_profile_bg_image_id'] = $instance['organic_widgets_profile_bg_image_id'];
		} else { $instance['organic_widgets_profile_bg_image_id'] = 0; }

		$output = '';

		$size = 'organic_widgets-featured-square';

		$attr = array();
		$attr = apply_filters( 'image_widget_image_attributes', $attr, $instance );

		$img_array = wp_get_attachment_image( $instance['organic_widgets_profile_bg_image_id'], $size, false, $attr );

		// If there is an organic_widgets_profile_bg_image, use it to render the image. Eventually we should kill this and simply rely on organic_widgets_profile_bg_image_ids.
		if ( ! empty( $instance['organic_widgets_profile_bg_image'] ) ) {
			// If all we have is an image src url we can still render an image.
			$attr['src'] = $instance['organic_widgets_profile_bg_image'];
			$attr = array_map( 'esc_attr', $attr );
			$output .= "<img ";
			foreach ( $attr as $name => $value ) {
				$output .= sprintf( ' %s="%s"', $name, $value );
			}
			$output .= ' />';
		} elseif( abs( $instance['organic_widgets_profile_bg_image_id'] ) > 0 ) {
			$output .= $img_array[0];
		}

		return $output;
	}

	/**
	 * Enqueue all the javascript.
	 */
	public function admin_setup() {
		wp_enqueue_media();
		wp_enqueue_script( 'organic_widgets-profile-widget-js', plugin_dir_url( __FILE__ ) . 'js/profile-widget.js', array( 'jquery', 'media-upload', 'media-views' ) );

		wp_localize_script( 'organic_widgets-profile-widget-js', 'ProfileWidget', array(
			'frame_title' => __( 'Select an Image', ORGANIC_WIDGETS_18N ),
			'button_title' => __( 'Insert Into Widget', ORGANIC_WIDGETS_18N ),
		) );

		wp_enqueue_style( 'organic_widgets-profile-widget-css', plugin_dir_url( __FILE__ ) . 'css/profile-widget.css' );
	}

} // class Organic_Widgets_Profile_Widget

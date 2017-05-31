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
class Organic_Widgets_Content_Widget extends WP_Widget {

	const CUSTOM_IMAGE_SIZE_SLUG = 'organic_widgets_widget_image_upload';

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'organic_widgets_featured_content', // Base ID
			__( 'Featured Content', $plugin->get_plugin_name ), // Name
			array(
				'description' => __( 'A featured content widget for displaying a page summary or custom content.', $plugin->get_plugin_name ),
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

		if ( ! empty( $instance['page_id'] ) ) {

			echo $args['before_widget'];
			$page_id = $instance['page_id'];
			$page_excerpt = $this->organic_widgets_get_the_excerpt($page_id);
			$page_title = get_the_title( $page_id );

			?>

			<div class="holder">
				<div class="feature-img"><?php echo get_the_post_thumbnail( $page_id, 'organic_widgets-featured-medium' )?></div>
				<div class="information">
					<?php if ( ! empty( $page_title ) ) { ?>
						<h3 class="headline"><?php echo apply_filters( 'widget_title', $page_title ); ?></h3>
					<?php } ?>
					<?php if ( ! empty( $page_excerpt ) ) { ?>
						<div class="excerpt"><?php echo $page_excerpt; ?></div>
					<?php } ?>
					<a class="button" href="<?php echo get_the_permalink( $page_id );?>"><?php esc_html_e( 'Read More', $plugin->get_plugin_name ); ?></a>
				</div>
			</div>

			<?php

			echo $args['after_widget'];

		} elseif ( ! empty( $instance['organic_widgets_featured_content_title'] ) || ! empty( $instance['organic_widgets_featured_content_summary'] ) ) {

			echo $args['before_widget'];

			$attr = array();
			$attr = apply_filters( 'image_widget_image_attributes', $attr, $instance );
			if ( isset( $instance['organic_widgets_featured_content_bg_image_id'] ) && '' != $instance['organic_widgets_featured_content_bg_image_id'] ) {
				$organic_widgets_featured_content_bg_image_id = $instance['organic_widgets_featured_content_bg_image_id'];
				$img_array = wp_get_attachment_image_src($organic_widgets_featured_content_bg_image_id, 'organic_widgets-featured-medium', false, $attr);
				$organic_widgets_featured_content_bg_image = $img_array[0];
			} else { $organic_widgets_featured_content_bg_image_id = 0; }
			$organic_widgets_featured_content_title = $instance['organic_widgets_featured_content_title'];
			$organic_widgets_featured_content_summary = $instance['organic_widgets_featured_content_summary'];
			$organic_widgets_featured_content_link_url = $instance['organic_widgets_featured_content_link_url'];
			$organic_widgets_featured_content_link_title = $instance['organic_widgets_featured_content_link_title'];

			?>

			<div class="holder">
				<?php if ( 0 < $organic_widgets_featured_content_bg_image_id ) { ?>
					<div class="feature-img"><img src="<?php echo $organic_widgets_featured_content_bg_image; ?>" /></div>
				<?php } elseif ( '1' == get_option( 'fresh_site' ) ) { ?>
					<div class="feature-img"><img src="<?php echo get_template_directory_uri(); ?>/images/image-about.jpg" /></div>
				<?php } ?>
				<div class="information">
					<?php if ( ! empty( $organic_widgets_featured_content_title ) ) { ?>
						<h3 class="headline"><?php echo esc_html( $organic_widgets_featured_content_title ); ?></h3>
					<?php } ?>
					<?php if ( ! empty( $organic_widgets_featured_content_summary ) ) { ?>
						<div class="excerpt"><?php echo $organic_widgets_featured_content_summary ?></div>
					<?php } ?>
					<?php if ( ! empty( $organic_widgets_featured_content_link_url ) ) { ?>
						<a class="button" href="<?php echo esc_url( $organic_widgets_featured_content_link_url ); ?>">
							<?php if ( ! empty( $organic_widgets_featured_content_link_title ) ) { ?>
								<?php echo $organic_widgets_featured_content_link_title ?>
							<?php } else { ?>
								<?php esc_html_e( 'Read More', $plugin->get_plugin_name ); ?>
							<?php } ?>
						</a>
					<?php } ?>
				</div>
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
		$id_prefix = $this->get_field_id('');
		if ( isset( $instance['organic_widgets_featured_content_bg_image_id'] ) && '' != $instance['organic_widgets_featured_content_bg_image_id'] ) {
			$organic_widgets_featured_content_bg_image_id = $instance['organic_widgets_featured_content_bg_image_id'];
		} else { $organic_widgets_featured_content_bg_image_id = 0; }
		if ( isset( $instance['organic_widgets_featured_content_bg_image'] ) && '' != $instance['organic_widgets_featured_content_bg_image'] ) {
			$organic_widgets_featured_content_bg_image = $instance['organic_widgets_featured_content_bg_image'];
		} else { $organic_widgets_featured_content_bg_image = 0; }
		if ( isset( $instance[ 'page_id' ] ) ) {
			$organic_widgets_featured_content_page_id = $instance[ 'page_id' ];
		} else { $organic_widgets_featured_content_page_id = 0; }
		if ( isset( $instance[ 'organic_widgets_featured_content_title' ] ) ) {
			$organic_widgets_featured_content_title = $instance[ 'organic_widgets_featured_content_title' ];
		} else { $organic_widgets_featured_content_title = ''; }
		if ( isset( $instance[ 'organic_widgets_featured_content_summary' ] ) ) {
			$organic_widgets_featured_content_summary = $instance[ 'organic_widgets_featured_content_summary' ];
		} else { $organic_widgets_featured_content_summary = ''; }
		if ( isset( $instance[ 'organic_widgets_featured_content_link_url' ] ) ) {
			$organic_widgets_featured_content_link_url = $instance[ 'organic_widgets_featured_content_link_url' ];
		} else { $organic_widgets_featured_content_link_url = ''; }
		if ( isset( $instance[ 'organic_widgets_featured_content_link_title' ] ) ) {
			$organic_widgets_featured_content_link_title = $instance[ 'organic_widgets_featured_content_link_title' ];
		} else { $organic_widgets_featured_content_link_title = ''; }
		?>

		<p><b><?php _e('Choose Existing Page:', $plugin->get_plugin_name) ?></b></p>

		<p>
			<?php wp_dropdown_pages( array(
				'class' => 'widefat',
				'selected' => $organic_widgets_featured_content_page_id,
				'id' => $this->get_field_id( 'page_id' ),
				'name' => $this->get_field_name( 'page_id' ),
				'show_option_none' => __( '— Select Existing Page —', $plugin->get_plugin_name ),
				'option_none_value' => '0',
			) ); ?>
		</p>

		<hr />

		<p><b><?php _e('Or Add Custom Content:', $plugin->get_plugin_name) ?></b></p>

		<p>
			<label for="<?php echo $this->get_field_id( 'organic_widgets_featured_content_bg_image' ); ?>"><?php _e('Background Image:', $plugin->get_plugin_name) ?></label>
			<div class="uploader">
				<input type="submit" class="button" name="<?php echo $this->get_field_name('uploader_button'); ?>" id="<?php echo $this->get_field_id('uploader_button'); ?>" value="<?php _e('Select an Image', $plugin->get_plugin_name); ?>" onclick="featuredContentWidgetImage.uploader( '<?php echo $this->id; ?>', '<?php echo $id_prefix; ?>' ); return false;" />
				<input type="submit" class="organic_widgets-remove-image-button button" name="<?php echo $this->get_field_name('remover_button'); ?>" id="<?php echo $this->get_field_id('remover_button'); ?>" value="<?php _e('Remove Image', $plugin->get_plugin_name); ?>" onclick="featuredContentWidgetImage.remover( '<?php echo $this->id; ?>', '<?php echo $id_prefix; ?>', 'remover_button' ); return false;" <?php if ( $organic_widgets_featured_content_bg_image_id < 1 ) { echo( 'style="display:none;"' ); } ?>/>
				<div class="organic_widgets-widget-image-preview" id="<?php echo $this->get_field_id('preview'); ?>">
					<?php echo $this->get_image_html($instance); ?>
				</div>
				<input type="hidden" id="<?php echo $this->get_field_id('organic_widgets_featured_content_bg_image_id'); ?>" name="<?php echo $this->get_field_name('organic_widgets_featured_content_bg_image_id'); ?>" value="<?php echo abs($organic_widgets_featured_content_bg_image_id); ?>" />
				<input type="hidden" id="<?php echo $this->get_field_id('organic_widgets_featured_content_bg_image'); ?>" name="<?php echo $this->get_field_name('organic_widgets_featured_content_bg_image'); ?>" value="<?php echo $organic_widgets_featured_content_bg_image; ?>" />
			</div>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'organic_widgets_featured_content_title' ); ?>"><?php _e('Title:', $plugin->get_plugin_name) ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'organic_widgets_featured_content_title' ); ?>" name="<?php echo $this->get_field_name( 'organic_widgets_featured_content_title' ); ?>" value="<?php echo $organic_widgets_featured_content_title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'organic_widgets_featured_content_summary' ); ?>"><?php _e('Summary:', $plugin->get_plugin_name) ?></label>
			<textarea class="widefat" rows="6" cols="20" id="<?php echo $this->get_field_id( 'organic_widgets_featured_content_summary' ); ?>" name="<?php echo $this->get_field_name( 'organic_widgets_featured_content_summary' ); ?>"><?php echo $organic_widgets_featured_content_summary; ?></textarea>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'organic_widgets_featured_content_link_url' ); ?>"><?php _e('Link URL:', $plugin->get_plugin_name) ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'organic_widgets_featured_content_link_url' ); ?>" name="<?php echo $this->get_field_name( 'organic_widgets_featured_content_link_url' ); ?>" value="<?php echo $organic_widgets_featured_content_link_url; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'organic_widgets_featured_content_link_title' ); ?>"><?php _e('Link Text:', $plugin->get_plugin_name) ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'organic_widgets_featured_content_link_title' ); ?>" name="<?php echo $this->get_field_name( 'organic_widgets_featured_content_link_title' ); ?>" value="<?php echo $organic_widgets_featured_content_link_title; ?>" />
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
		$instance = array();
		$instance['page_id'] = ( ! empty( $new_instance['page_id'] ) ) ? strip_tags( $new_instance['page_id'] ) : '';
		$instance['organic_widgets_featured_content_bg_image_id'] = strip_tags( $new_instance['organic_widgets_featured_content_bg_image_id'] );
		$instance['organic_widgets_featured_content_title'] = strip_tags( $new_instance['organic_widgets_featured_content_title'] );
		$instance['organic_widgets_featured_content_summary'] = strip_tags( $new_instance['organic_widgets_featured_content_summary'] );
		$instance['organic_widgets_featured_content_link_url'] = strip_tags( $new_instance['organic_widgets_featured_content_link_url'] );
		$instance['organic_widgets_featured_content_link_title'] = strip_tags( $new_instance['organic_widgets_featured_content_link_title'] );

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

		if ( isset( $instance['organic_widgets_featured_content_bg_image_id'] ) && '' != $instance['organic_widgets_featured_content_bg_image_id'] ) {
			$organic_widgets_featured_content_bg_image_id = $instance['organic_widgets_featured_content_bg_image_id'];
		} else { $organic_widgets_featured_content_bg_image_id = 0; }

		$output = '';

		$size = 'organic_widgets-featured-medium';

		$attr = array();
		$attr = apply_filters( 'image_widget_image_attributes', $attr, $instance );

		$img_array = wp_get_attachment_image_src($organic_widgets_featured_content_bg_image_id, $size, false, $attr);

		// If there is an organic_widgets_featured_content_bg_image, use it to render the image. Eventually we should kill this and simply rely on organic_widgets_featured_content_bg_image_ids.
		if ( 0 < $organic_widgets_featured_content_bg_image_id ) {
			// If all we have is an image src url we can still render an image.
			$attr['src'] = $img_array[0];
			$attr = array_map( 'esc_attr', $attr );
			$output .= "<img ";
			foreach ( $attr as $name => $value ) {
				$output .= sprintf( ' %s="%s"', $name, $value );
			}
			$output .= ' />';
		} elseif( abs( $organic_widgets_featured_content_bg_image_id ) > 0 ) {
			$output .= $img_array[0];
		}

		return $output;
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

		wp_enqueue_script( 'organic_widgets-featured-content-widget-js', plugin_dir_url( __FILE__ ) . 'js/featured-content-widget.js', array( 'jquery', 'media-upload', 'media-views' ) );

		wp_localize_script( 'organic_widgets-featured-content-widget-js', 'FeaturedContentWidget', array(
			'frame_title' => __( 'Select an Image', $plugin->get_plugin_name ),
			'button_title' => __( 'Insert Into Widget', $plugin->get_plugin_name ),
		) );

		wp_enqueue_style( 'organic_widgets-featured-content-widget-css', plugin_dir_url( __FILE__ ) . 'css/featured-content-widget.css' );
	}

} // class Organic_Widgets_Content_Widget

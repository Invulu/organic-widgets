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
class Organic_Widgets_Subpage_Section_Widget extends WP_Widget {

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

				<div class="row"><!-- BEGIN .row -->

					<div class="content no-bg"><!-- BEGIN .content -->

						<div class="sixteen columns"><!-- BEGIN .columns -->

							<div class="post-area wide clearfix">

								<?php the_content( esc_html__( 'Read More', ORGANIC_WIDGETS_18N ) ); ?>

							</div>

						</div><!-- END .columns -->

					</div><!-- END .content -->

				</div><!-- END .row -->

			</div>

			<?php endwhile; ?>
			<?php endif; ?>
			<?php wp_reset_postdata(); ?>

			<?php echo $args['after_widget'];

		} elseif ( ! empty( $instance['organic_widgets_subpage_title'] ) || ! empty( $instance['organic_widgets_subpage_summary'] ) ) {

			$organic_widgets_subpage_bg_image_id = isset( $instance['organic_widgets_subpage_bg_image_id'] ) ? $instance['organic_widgets_subpage_bg_image_id'] : false;
			$organic_widgets_subpage_bg_image = ( isset( $instance['organic_widgets_subpage_bg_image'] ) && '' != $instance['organic_widgets_subpage_bg_image'] ) ? $instance['organic_widgets_subpage_bg_image'] : false;
			$organic_widgets_subpage_title = $instance['organic_widgets_subpage_title'];
			$organic_widgets_subpage_summary = $instance['organic_widgets_subpage_summary'];

			echo $args['before_widget'];

			?>

			<div class="organic_widgets-subpage-section<?php if ( 0 < $organic_widgets_subpage_bg_image_id ) { ?> has-thumb text-white<?php } ?>" <?php if ( 0 < $organic_widgets_subpage_bg_image_id ) { ?>style="background-image:url(<?php echo $organic_widgets_subpage_bg_image; ?>);"<?php } ?>>

				<div class="row"><!-- BEGIN .row -->

					<div class="content no-bg"><!-- BEGIN .content -->

						<div class="sixteen columns"><!-- BEGIN .columns -->

							<div class="post-area wide clearfix">

								<?php if ( ! empty( $organic_widgets_subpage_title ) ) { ?>
									<h3 class="headline text-center"><?php echo esc_html( $organic_widgets_subpage_title ); ?></h3>
								<?php } ?>
								<?php if ( ! empty( $organic_widgets_subpage_summary ) ) { ?>
									<p class="summary"><?php echo $organic_widgets_subpage_summary ?></p>
								<?php } ?>

							</div>

						</div><!-- END .columns -->

					</div><!-- END .content -->

				</div><!-- END .row -->

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

		$id_prefix = $this->get_field_id('');

		if ( isset( $instance[ 'page_id' ] ) ) {
			$organic_widgets_subpage_page_id = $instance[ 'page_id' ];
		} else { $organic_widgets_subpage_page_id = 0; }

		if ( isset( $instance['organic_widgets_subpage_bg_image_id'] ) ) {
			$organic_widgets_subpage_bg_image_id = $instance['organic_widgets_subpage_bg_image_id'];
		} else { $organic_widgets_subpage_bg_image_id = 0; }

		if ( isset( $instance['organic_widgets_subpage_bg_image_id'] ) && isset( $instance['organic_widgets_subpage_bg_image'] ) ) {
			$organic_widgets_subpage_bg_image = $instance['organic_widgets_subpage_bg_image'];
		} else { $organic_widgets_subpage_bg_image = false; }

		if ( isset( $instance[ 'organic_widgets_subpage_title' ] ) ) {
			$organic_widgets_subpage_title = $instance[ 'organic_widgets_subpage_title' ];
		} else { $organic_widgets_subpage_title = ''; }

		if ( isset( $instance[ 'organic_widgets_subpage_summary' ] ) ) {
			$organic_widgets_subpage_summary = $instance[ 'organic_widgets_subpage_summary' ];
		} else { $organic_widgets_subpage_summary = ''; }

		?>

		<p><b><?php _e('Choose Existing Page:', ORGANIC_WIDGETS_18N) ?></b></p>

		<p>
			<?php wp_dropdown_pages( array(
				'class' => 'widefat',
				'selected' => $organic_widgets_subpage_page_id,
				'id' => $this->get_field_id( 'page_id' ),
				'name' => $this->get_field_name( 'page_id' ),
				'show_option_none' => __( '— Select Existing Page —', ORGANIC_WIDGETS_18N ),
				'option_none_value' => '0',
			) ); ?>
		</p>

		<hr />

		<p><b><?php _e('Or Add Custom Content:', ORGANIC_WIDGETS_18N) ?></b></p>

		<p>
			<label for="<?php echo $this->get_field_id( 'organic_widgets_subpage_bg_image' ); ?>"><?php _e( 'Background Image:', ORGANIC_WIDGETS_18N ) ?></label>
			<div class="uploader">
				<input type="submit" class="button" name="<?php echo $this->get_field_name('uploader_button'); ?>" id="<?php echo $this->get_field_id('uploader_button'); ?>" value="<?php _e( 'Select an Image', ORGANIC_WIDGETS_18N ); ?>" onclick="subpageWidgetImage.uploader( '<?php echo $this->id; ?>', '<?php echo $id_prefix; ?>' ); return false;" />
				<input type="submit" class="organic_widgets-remove-image-button button" name="<?php echo $this->get_field_name('remover_button'); ?>" id="<?php echo $this->get_field_id('remover_button'); ?>" value="<?php _e('Remove Image', ORGANIC_WIDGETS_18N); ?>" onclick="subpageWidgetImage.remover( '<?php echo $this->id; ?>', '<?php echo $id_prefix; ?>', 'remover_button' ); return false;" <?php if ( $organic_widgets_subpage_bg_image_id < 1 ) { echo( 'style="display:none;"' ); } ?>/>
				<div class="organic_widgets-widget-image-preview" id="<?php echo $this->get_field_id('preview'); ?>">
					<?php echo $this->get_image_html($instance); ?>
				</div>
				<input type="hidden" id="<?php echo $this->get_field_id('organic_widgets_subpage_bg_image_id'); ?>" name="<?php echo $this->get_field_name('organic_widgets_subpage_bg_image_id'); ?>" value="<?php echo abs($organic_widgets_subpage_bg_image_id); ?>" />
				<input type="hidden" id="<?php echo $this->get_field_id('organic_widgets_subpage_bg_image'); ?>" name="<?php echo $this->get_field_name('organic_widgets_subpage_bg_image'); ?>" value="<?php echo $organic_widgets_subpage_bg_image; ?>" />
			</div>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'organic_widgets_subpage_title' ); ?>"><?php _e('Title:', ORGANIC_WIDGETS_18N) ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'organic_widgets_subpage_title' ); ?>" name="<?php echo $this->get_field_name( 'organic_widgets_subpage_title' ); ?>" value="<?php echo $organic_widgets_subpage_title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'organic_widgets_subpage_summary' ); ?>"><?php _e('Summary:', ORGANIC_WIDGETS_18N) ?></label>
			<textarea class="widefat" rows="6" cols="20" id="<?php echo $this->get_field_id( 'organic_widgets_subpage_summary' ); ?>" name="<?php echo $this->get_field_name( 'organic_widgets_subpage_summary' ); ?>"><?php echo $organic_widgets_subpage_summary; ?></textarea>
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

		if ( isset( $new_instance['page_id'] ) )
			$instance['page_id'] = strip_tags( $new_instance['page_id'] );
		if ( isset( $new_instance['organic_widgets_subpage_bg_image_id'] ) )
			$instance['organic_widgets_subpage_bg_image_id'] = strip_tags( $new_instance['organic_widgets_subpage_bg_image_id'] );
		if ( isset( $new_instance['organic_widgets_subpage_bg_image'] ) )
			$instance['organic_widgets_subpage_bg_image'] = strip_tags( $new_instance['organic_widgets_subpage_bg_image'] );
		if ( isset( $new_instance['organic_widgets_subpage_title'] ) )
			$instance['organic_widgets_subpage_title'] = strip_tags( $new_instance['organic_widgets_subpage_title'] );
		if ( isset( $new_instance['organic_widgets_subpage_summary'] ) )
			$instance['organic_widgets_subpage_summary'] = strip_tags( $new_instance['organic_widgets_subpage_summary'] );

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

		if ( isset( $instance['organic_widgets_subpage_bg_image_id'] ) ) {
			$organic_widgets_subpage_bg_image_id = $instance['organic_widgets_subpage_bg_image_id'];
		} else { $organic_widgets_subpage_bg_image_id = 0; }

		$output = '';

		$size = 'organic_widgets-featured-large';

		$attr = array();
		$attr = apply_filters( 'image_widget_image_attributes', $attr, $instance );

		$img_array = wp_get_attachment_image( $organic_widgets_subpage_bg_image_id, $size, false, $attr );

		// If there is an organic_widgets_subpage_bg_image, use it to render the image. Eventually we should kill this and simply rely on organic_widgets_subpage_bg_image_ids.
		if ( ! empty( $instance['organic_widgets_subpage_bg_image'] ) ) {
			// If all we have is an image src url we can still render an image.
			$attr['src'] = $instance['organic_widgets_subpage_bg_image'];
			$attr = array_map( 'esc_attr', $attr );
			$output .= "<img ";
			foreach ( $attr as $name => $value ) {
				$output .= sprintf( ' %s="%s"', $name, $value );
			}
			$output .= ' />';
		} elseif( abs( $organic_widgets_subpage_bg_image_id ) > 0 ) {
			$output .= $img_array[0];
		}

		return $output;
	}

	/**
	 * Enqueue all the javascript.
	 */
	public function admin_setup() {
		wp_enqueue_media();
		wp_enqueue_script( 'organic_widgets-subpage-widget-js', plugin_dir_url( __FILE__ ) . 'js/subpage-widget.js', array( 'jquery', 'media-upload', 'media-views' ) );

		wp_localize_script( 'organic_widgets-subpage-widget-js', 'SubpageWidget', array(
			'frame_title' => __( 'Select an Image', ORGANIC_WIDGETS_18N ),
			'button_title' => __( 'Insert Into Widget', ORGANIC_WIDGETS_18N ),
		) );

		wp_enqueue_style( 'organic_widgets-subpage-widget-css', plugin_dir_url( __FILE__ ) . 'css/subpage-widget.css' );
	}

} // class Organic_Widgets_Subpage_Section_Widget

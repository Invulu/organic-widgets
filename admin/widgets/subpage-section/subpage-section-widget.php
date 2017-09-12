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
			__( 'Organic Subpage', ORGANIC_WIDGETS_18N ), // Name
			array(
				'description' => __( 'A subpage\'s content displayed as a section of another page.', ORGANIC_WIDGETS_18N ),
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

		echo $args['before_widget']; ?>

		<!-- BEGIN .organic-widgets-section -->
		<div class="organic-widgets-section organic-widgets-subpage-section organic-widgets-video-bg-section" <?php if ( 0 < $bg_image_id ) { ?>style="background-image:url(<?php echo $bg_image; ?>);"<?php } elseif ($bg_color) { ?>style="background-color:<?php echo $bg_color; ?>;"<?php } ?>>

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

		if ( ! empty( $instance['page_id'] ) ) {

			// Get Page Info
			$page_id = $instance['page_id'];
			$the_featured_image = get_the_post_thumbnail_url( $page_id, 'organic-widgets-featured-large' );

			$page_query = new WP_Query(array(
				'post_type'	 				=> 'page',
				'page_id' 					=> $page_id,
				'posts_per_page' 		=> 1,
			) );

			if ( $page_query->have_posts() ) : while ( $page_query->have_posts() ) : $page_query->the_post(); ?>

				<!-- BEGIN .organic-widgets-content -->
				<div class="organic-widgets-content">

					<?php the_content( esc_html__( 'Read More', ORGANIC_WIDGETS_18N ) ); ?>

				<!-- END .organic-widgets-content -->
				</div>

			<!-- END .organic-widgets-section -->
			</div>

			<?php endwhile;
			endif;
			wp_reset_postdata();

			echo $args['after_widget'];

		} elseif ( ! empty( $instance['title'] ) || ! empty( $instance['text'] ) ) { ?>

				<!-- BEGIN .organic-widgets-content -->
				<div class="organic-widgets-content">

					<?php if ( ! empty( $instance['title'] ) ) { ?>
						<h2 class="organic-widgets-title"><?php echo apply_filters( 'widget_title', $instance['title'] ); ?></h2>
					<?php } ?>
					<?php if ( ! empty( $instance['text']) ) { ?>
						<div class="organic-widgets-text"><?php echo apply_filters( 'the_content', $instance['text'] ); ?></div>
					<?php } ?>

				<!-- END .organic-widgets-content -->
				</div>

			<!-- END .organic-widgets-section -->
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

		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title' => '',
				'text' => '',
			)
		);

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

		?>

		<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" class="title" type="hidden" value="<?php echo esc_attr( $instance['title'] ); ?>">
		<input id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" class="text organic-widgets-wysiwyg-anchor" type="hidden" value="<?php echo esc_attr( $instance['text'] ); ?>">
		<input id="<?php echo $this->get_field_id( 'filter' ); ?>" name="<?php echo $this->get_field_name( 'filter' ); ?>" class="filter" type="hidden" value="on">
		<input id="<?php echo $this->get_field_id( 'visual' ); ?>" name="<?php echo $this->get_field_name( 'visual' ); ?>" class="visual" type="hidden" value="on">

		<hr />
		<br />

		<p><b><?php _e('OR Use Content From Page:', ORGANIC_WIDGETS_18N) ?></b></p>

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
		<script type="text/html" id="tmpl-widget-organic_widgets_subpage_section-control-fields">

			<# var elementIdPrefix = 'el' + String( Math.random() ).replace( /\D/g, '' ) + '_' #>

			<p><b><?php _e('Add Custom Content:', ORGANIC_WIDGETS_18N) ?></b></p>

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
		if ( isset( $new_instance['title'] ) )
			$instance['title'] = strip_tags( $new_instance['title'] );
		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['text'] = $new_instance['text'];
		} else {
			$instance['text'] = wp_kses_post( $new_instance['text'] );
		}
		// Widget Title
		if ( isset( $new_instance['title'] )  && '' != $new_instance['title'] ) {
			$instance['title'] = strip_tags( $new_instance['title'] );
		} else {
			$instance['title'] = '';
		}
		/*--- END Text/Title ----*/

		if ( ! isset( $old_instance['created'] ) )
			$instance['created'] = time();
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

		// Widget Title
		if ( isset( $new_instance['page_id'] ) && $new_instance['page_id'] > 0 ) {
			$instance['title'] = strip_tags( get_the_title( $instance['page_id'] ) );
		}

		return $instance;
	}

	/**
	 * Enqueue all the javascript.
	 */
	public function admin_setup() {

		// Text Editor
		wp_enqueue_editor();
		wp_enqueue_script( 'organic-widgets-subpage-widgets-text-title', plugin_dir_url( __FILE__ ) . 'js/subpage-widgets.js', array( 'jquery' ) );
		wp_localize_script( 'organic-widgets-subpage-widgets-text-title', 'OrganicSubpageWidget', array(
			'id_base' => $this->id_base,
		) );
		wp_add_inline_script( 'organic-widgets-subpage-widgets-text-title', 'wp.organicSubpageWidget.init();', 'after' );

		wp_enqueue_media();

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_script( 'organic-widgets-module-color-picker', ORGANIC_WIDGETS_ADMIN_JS_DIR . 'organic-widgets-module-color-picker.js', array( 'jquery', 'media-upload', 'media-views', 'wp-color-picker' ) );

		wp_enqueue_script( 'organic-widgets-module-image-background', ORGANIC_WIDGETS_ADMIN_JS_DIR . 'organic-widgets-module-image-background.js', array( 'jquery', 'media-upload', 'media-views', 'wp-color-picker' ) );
		wp_localize_script( 'organic-widgets-module-image-background', 'OrganicWidgetBG', array(
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

} // class Organic_Widgets_Subpage_Section_Widget

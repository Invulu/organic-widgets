<?php
/* Registers a widget to show a Featured Content subsection on a page */

// Block direct requests.
if ( !defined('ABSPATH') )
	die( '-1' );

/**
 * Adds Organic_Widgets_Content_Widget widget.
 */
class Organic_Widgets_Content_Widget extends Organic_Widgets_Custom_Widget {

	const CUSTOM_IMAGE_SIZE_SLUG = 'organic_widgets_widget_image_upload';

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'organic_widgets_featured_content', // Base ID
			__( 'Organic Featured Content', ORGANIC_WIDGETS_18N ), // Name
			array(
				'description' => __( 'A widget for displaying an existing page or custom content.', ORGANIC_WIDGETS_18N ),
				'customize_selective_refresh' => false,
			) // Args
		);

		$this->id_prefix = $this->get_field_id('');

		// Bg options
		$this->bg_options = array(
			'color' => true,
			'image' => true
		);

		// Admin Scripts
		add_action( 'admin_print_scripts-widgets.php', array( $this, 'admin_setup' ) );
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

		$group = $this->organic_widgets_groupable_widget( $args );
		$group_id = $group['group_id'];
		if ( $group['first'] && ! $group['last'] ) {
			$first_last = ' organic-widgets-groupable-first';
		} elseif ( $group['last'] && ! $group['first'] ) {
			$first_last = ' organic-widgets-groupable-last';
		} else {
			$first_last = false;
		}

		echo $args['before_widget'];

		if ( ! empty( $instance['page_id'] ) ) {

			$bg_color = isset( $instance['bg_color'] ) ? $instance['bg_color'] : false;
			$bg_image_id = isset( $instance['bg_image_id'] ) ? $instance['bg_image_id'] : false;
			$bg_image = ( isset( $instance['bg_image'] ) && '' != $instance['bg_image'] ) ? $instance['bg_image'] : false;
			$feature_img_id = false;
			$page_id = $instance['page_id'];
			$page_excerpt = $this->organic_widgets_get_the_excerpt($page_id);
			$page_title = get_the_title( $page_id );

			$page_query = new WP_Query(array(
				'post_type'	 				=> 'page',
				'page_id' 					=> $page_id,
				'posts_per_page' 		=> 1,
			) );

			if ( $page_query->have_posts() ) : while ( $page_query->have_posts() ) : $page_query->the_post();

			?>

			<!-- BEGIN .organic-widgets-section -->
			<div class="organic-widgets-section organic-widgets-featured-content-section<?php if ( $first_last ) { echo esc_attr( $first_last ); } ?>" <?php if ( 0 < $bg_image_id ) { ?>style="background-image:url(<?php echo $bg_image; ?>);"<?php } elseif ($bg_color) { ?>style="background-color:<?php echo $bg_color; ?>;"<?php } ?> <?php if ($group_id) { echo 'data-group-id="' . $group_id . '"'; } ?>>

				<!-- BEGIN .organic-widgets-content -->
				<div class="organic-widgets-content">

					<?php if ( has_post_thumbnail() ) {  ?>
						<div class="organic-widgets-img"><?php echo get_the_post_thumbnail( $page_id, 'organic-widgets-featured-medium' )?></div>
					<?php } ?>

					<!-- BEGIN .organic-widgets-card-content -->
					<div class="organic-widgets-card-content">

						<?php the_content( esc_html__( 'Read More', ORGANIC_WIDGETS_18N ) ); ?>
						<?php edit_post_link( esc_html__( '(Edit)', ORGANIC_WIDGETS_18N ), '<p>', '</p>' ); ?>

					<!-- END .organic-widgets-card-content -->
					</div>

				<!-- END .organic-widgets-content -->
				</div>

			<!-- END .organic-widgets-section -->
			</div><?php

			endwhile;
			endif;
			wp_reset_postdata();

		} else {

			$attr = array();
			$attr = apply_filters( 'image_widget_image_attributes', $attr, $instance );
			$bg_color = isset( $instance['bg_color'] ) ? $instance['bg_color'] : false;
			$bg_image_id = isset( $instance['bg_image_id'] ) ? $instance['bg_image_id'] : false;
			$bg_image = ( isset( $instance['bg_image'] ) && '' != $instance['bg_image'] ) ? $instance['bg_image'] : false;
			$link_url = ( isset( $instance['link_url'] ) ) ? $instance['link_url'] : false;
			$link_title = ( isset( $instance['link_title'] ) ) ? $instance['link_title'] : false;
			?>

			<!-- BEGIN .organic-widgets-section -->
			<div class="organic-widgets-section organic-widgets-featured-content-section<?php if ( $first_last ) { echo esc_attr( $first_last ); } ?>" <?php if ( 0 < $bg_image_id ) { ?>style="background-image:url(<?php echo $bg_image; ?>);"<?php } elseif ($bg_color) { ?>style="background-color:<?php echo $bg_color; ?>;"<?php } ?> <?php if ($group_id) { echo 'data-group-id="' . $group_id . '"'; } ?>>

				<?php if ( ! empty( $instance['title'] ) || ! empty( $instance['text'] ) ) { ?>

					<!-- BEGIN .organic-widgets-content -->
					<div class="organic-widgets-content">

						<!-- BEGIN .organic-widgets-card-content -->
						<div class="organic-widgets-card-content">

							<?php if ( ! empty( $instance['title'] ) ) { ?>
								<h2><?php echo apply_filters( 'organic_widget_title', $instance['title'] ); ?></h2>
							<?php } ?>

							<?php if ( ! empty( $instance['text'] ) ) { ?>
								<?php echo apply_filters( 'the_content', $instance['text'] ); ?>
							<?php } ?>

							<?php if ( ! empty( $link_url ) ) { ?>
								<a class="button" href="<?php echo esc_url( $link_url ); ?>">
									<?php if ( ! empty( $link_title ) ) { ?>
										<?php echo $link_title ?>
									<?php } else { ?>
										<?php esc_html_e( 'Read More', ORGANIC_WIDGETS_18N ); ?>
									<?php } ?>
								</a>
							<?php } ?>

						<!-- END .organic-widgets-card-content -->
						</div>

					<!-- END .organic-widgets-content -->
					</div>

				<?php } ?>

			<!-- END .organic-widgets-section -->
			</div>

		<?php }

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

		// Set up variables
		$this->id_prefix = $this->get_field_id('');
		$bg_color = ( isset( $instance['bg_color'] ) && '' != $instance['bg_color'] ) ? $instance['bg_color'] : false;
		if ( isset( $instance['bg_image_id'] ) && '' != $instance['bg_image_id'] ) {
			$bg_image_id = $instance['bg_image_id'];
		} else { $bg_image_id = 0; }
		if ( isset( $instance['bg_image'] ) && '' != $instance['bg_image'] ) {
			$bg_image = $instance['bg_image'];
		} else { $bg_image = false; }
		if ( isset( $instance[ 'page_id' ] ) ) {
			$page_id = $instance[ 'page_id' ];
		} else { $page_id = 0; }
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else { $title = ''; }
		if ( isset( $instance[ 'text' ] ) ) {
			$text = $instance[ 'text' ];
		} else { $text = ''; }
		if ( isset( $instance[ 'link_url' ] ) ) {
			$link_url = $instance[ 'link_url' ];
		} else { $link_url = ''; }
		if ( isset( $instance[ 'link_title' ] ) ) {
			$link_title = $instance[ 'link_title' ];
		} else { $link_title = ''; }
		?>

		<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" class="title" type="hidden" value="<?php echo esc_attr( $instance['title'] ); ?>">
		<input id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" class="text organic-widgets-wysiwyg-anchor" type="hidden" value="<?php echo esc_attr( $instance['text'] ); ?>">
		<input id="<?php echo $this->get_field_id( 'filter' ); ?>" name="<?php echo $this->get_field_name( 'filter' ); ?>" class="filter" type="hidden" value="on">
		<input id="<?php echo $this->get_field_id( 'visual' ); ?>" name="<?php echo $this->get_field_name( 'visual' ); ?>" class="visual" type="hidden" value="on">

		<p>
			<label for="<?php echo $this->get_field_id( 'link_title' ); ?>"><?php _e('Button Text:', ORGANIC_WIDGETS_18N) ?></label>
			<input class="widefat link_title" type="text" id="<?php echo $this->get_field_id( 'link_title' ); ?>" name="<?php echo $this->get_field_name( 'link_title' ); ?>" value="<?php echo $link_title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'link_url' ); ?>"><?php _e('Button URL:', ORGANIC_WIDGETS_18N) ?></label>
			<input class="widefat link_url" type="text" id="<?php echo $this->get_field_id( 'link_url' ); ?>" name="<?php echo $this->get_field_name( 'link_url' ); ?>" value="<?php echo $link_url; ?>" />
		</p>

		<br/>

		<h3><?php _e('Or Choose Existing Page:', ORGANIC_WIDGETS_18N) ?></h3>

		<p>
			<?php wp_dropdown_pages( array(
				'class' => 'widefat organic-widgets-page-selector',
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
		<script type="text/html" id="tmpl-widget-organic_widgets_featured_content_section-control-fields">

			<# var elementIdPrefix = 'el' + String( Math.random() ).replace( /\D/g, '' ) + '_' #>

			<h3><?php _e('Add Custom Content:', ORGANIC_WIDGETS_18N) ?></h3>

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

		$instance = array();

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
		$instance['page_id'] = ( ! empty( $new_instance['page_id'] ) ) ? strip_tags( $new_instance['page_id'] ) : '';
		if ( isset( $new_instance['bg_color'] ) && $this->check_hex_color( $new_instance['bg_color'] ) ) {
			$instance['bg_color'] = strip_tags( $new_instance['bg_color'] );
		} else {
			$instance['bg_color'] = false;
		}
		$instance['bg_image_id'] = strip_tags( $new_instance['bg_image_id'] );
		$instance['bg_image'] = strip_tags( $new_instance['bg_image'] );
		if ( isset( $new_instance['alignment'] ) )
			$instance['alignment'] = strip_tags( $new_instance['alignment'] );
		$instance['link_url'] = strip_tags( $new_instance['link_url'] );
		$instance['link_title'] = strip_tags( $new_instance['link_title'] );


		return $instance;

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

		// Text Editor
		wp_enqueue_editor();
		wp_enqueue_script( 'organic-featured-content-widgets-text-title', plugin_dir_url( __FILE__ ) . 'js/featured-content-widgets.js', array( 'jquery' ) );
		wp_localize_script( 'organic-featured-content-widgets-text-title', 'OrganicFeaturedContentWidget', array(
			'id_base' => $this->id_base,
		) );
		wp_add_inline_script( 'organic-featured-content-widgets-text-title', 'wp.organicFeaturedContentWidget.init();', 'after' );

		wp_enqueue_script( 'organic-widgets-module-groupable-widgets', ORGANIC_WIDGETS_ADMIN_JS_DIR . 'organic-widgets-module-groupable-widgets.js', array( 'jquery' ) );
		wp_localize_script( 'organic-widgets-module-groupable-widgets', 'GroupableWidgets', array(
			'active_pane' => false,
			'widgets' => array()
		) );

		wp_enqueue_media();

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'organic-widgets-module-color-picker', ORGANIC_WIDGETS_ADMIN_JS_DIR . 'organic-widgets-module-color-picker.js', array( 'jquery', 'wp-color-picker' ) );

		wp_enqueue_script( 'organic-widgets-module-image-background', ORGANIC_WIDGETS_ADMIN_JS_DIR . 'organic-widgets-module-image-background.js', array( 'jquery', 'media-upload', 'media-views', 'wp-color-picker' ) );
		wp_localize_script( 'organic-widgets-module-image-background', 'FeaturedContentWidget', array(
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

} // class Organic_Widgets_Content_Widget

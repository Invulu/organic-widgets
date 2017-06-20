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
			__( 'Team Members', ORGANIC_WIDGETS_18N ), // Name
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

		$attr = array();
		$attr = apply_filters( 'image_widget_image_attributes', $attr, $instance );
		$bg_image_id = isset( $instance['bg_image_id'] ) ? $instance['bg_image_id'] : false;
		$bg_image = ( isset( $instance['bg_image'] ) && '' != $instance['bg_image'] ) ? $instance['bg_image'] : false;
		$bg_color = ( isset( $instance['bg_color'] ) && '' != $instance['bg_color'] ) ? $instance['bg_color'] : false;
		$title = ( isset( $instance['title'] ) ) ? $instance['title'] : false;
		$summary = ( isset( $instance['summary'] ) ) ? $instance['summary'] : false;
		$category = ( isset( $instance['category'] ) ) ? $instance['category'] : 0;
		$num_columns = ( isset( $instance['num_columns'] ) ) ? $instance['num_columns'] : 4;
		$max_posts = ( isset( $instance['max_posts'] ) ) ? $instance['max_posts'] : 4;

		echo $args['before_widget'];
		?>
		<!-- BEGIN .organic-widgets-section -->
		<div class="organic-widgets-section organic-widgets-team-section<?php if ( 0 < $bg_image_id ) { ?> has-thumb text-white<?php } ?>" <?php if ( 0 < $bg_image_id ) { ?>style="background-image:url(<?php echo $bg_image; ?>);"<?php } elseif ($bg_color) { ?>style="background-color:<?php echo $bg_color; ?>;"<?php } ?>>

			<?php if ( ! empty( $instance['title'] ) ) { ?>
				<h2 class="headline <?php if ( $bg_image_id > 0 ) { ?> text-white<?php } ?>"><?php echo apply_filters( 'widget_title', $instance['title'] ); ?></h2>
			<?php } ?>

			<?php if ( ! empty( $instance['summary'] ) ) { ?>
				<p class="summary"><?php echo $instance['summary'] ?></p>
			<?php } ?>

			<?php $wp_query = new WP_Query( array(
				'posts_per_page' => $max_posts,
				'post_type' => 'post',
				'suppress_filters' => 0,
				'tax_query' => array(
			    array(
			      'taxonomy' => 'category',
			      'field'    => 'id',
			      'terms'    => $category
			    ),
			  ),
			) ); ?>
			<?php if ( $wp_query->have_posts() ) : ?>

				<!-- BEGIN .organic-widgets-row -->
				<div class="organic-widgets-row organic-widgets-team-holder organic-widgets-post-holder">

					<?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>

					<!-- BEGIN .organic-widget-masonry-wrapper -->
					<div class="organic-widget-masonry-wrapper organic-widgets-column organic-widgets-<?php echo $this->column_string( $num_columns ); ?>">

						<article>

							<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>

							<!-- BEGIN .organic-widgets-content -->
							<div class="organic-widgets-content">

								<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

								<!-- BEGIN .organic-widgets-post-meta -->
								<div class="organic-widgets-post-meta">
									<div class="organic-widgets-post-date">
										<p class="organic-widgets-align-left">
											<?php echo get_the_modified_date(); ?>
										</p>
									</div>
								<!-- END .organic-widgets-post-meta -->
								</div>

								<?php the_excerpt(); ?>

							<!-- END .organic-widgets-content -->
							</div>

						</article>

					<!-- END .organic-widget-masonry-wrapper -->
					</div>



					<?php endwhile; ?>
				<!-- END .organic-widgets-row -->
				</div>

			<?php endif; ?>

		<!-- END .organic-widgets-section -->
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
		if ( isset( $instance[ 'summary' ] ) ) {
			$summary = $instance[ 'summary' ];
		} else { $summary = ''; }
		if ( isset( $instance['category'] ) ) {
			$category = $instance['category'];
		} else { $category = false; }
		if ( isset( $instance['num_columns'] ) ) {
			$num_columns = $instance['num_columns'];
		} else { $num_columns = 3; }
		if ( isset( $instance['max_posts'] ) ) {
			$max_posts = $instance['max_posts'];
		} else { $max_posts = 3; }
		if ( isset( $instance['bg_color'] ) ) {
			$bg_color = $instance['bg_color'];
		} else { $bg_color = false; }
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

		<p>
			<label for="<?php echo $this->get_field_id( 'summary' ); ?>"><?php _e('Summary:', ORGANIC_WIDGETS_18N) ?></label>
			<textarea class="widefat" rows="6" cols="20" id="<?php echo $this->get_field_id( 'summary' ); ?>" name="<?php echo $this->get_field_name( 'summary' ); ?>"><?php echo $summary; ?></textarea>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e('Team Category:', ORGANIC_WIDGETS_18N) ?></label>
			<?php wp_dropdown_categories( array(
				'selected' => $category,
				'id' => $this->get_field_id( 'category' ),
				'name' => $this->get_field_name( 'category' )
			)); ?>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'max_posts' ); ?>"><?php _e('Max Number of Posts:', ORGANIC_WIDGETS_18N) ?></label>
			<input type="number" min="1" max="16" value="<?php echo $max_posts; ?>" id="<?php echo $this->get_field_id('max_posts'); ?>" name="<?php echo $this->get_field_name('max_posts'); ?>" class="widefat" style="width:100%;"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'num_columns' ); ?>"><?php _e('Number of Columns:', ORGANIC_WIDGETS_18N) ?></label>
			<select id="<?php echo $this->get_field_id('num_columns'); ?>" name="<?php echo $this->get_field_name('num_columns'); ?>" class="widefat" style="width:100%;">
				<option <?php selected( $num_columns, '1'); ?> value="1">1</option>
				<option <?php selected( $num_columns, '2'); ?> value="2">2</option>
		    <option <?php selected( $num_columns, '3'); ?> value="3">3</option>
		    <option <?php selected( $num_columns, '4'); ?> value="4">4</option>
				<option <?php selected( $num_columns, '5'); ?> value="5">5</option>
			</select>
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
		if ( isset( $new_instance['bg_color'] ) && $this->check_hex_color( $new_instance['bg_color'] ) ) {
			$instance['bg_color'] = strip_tags( $new_instance['bg_color'] );
		} else {
			$instance['bg_color'] = false;
		}
		if ( isset( $new_instance['summary'] ) )
			$instance['summary'] = strip_tags( $new_instance['summary'] );
		if ( isset( $new_instance['category'] ) )
			$instance['category'] = strip_tags( $new_instance['category'] );
		if ( isset( $new_instance['num_columns'] ) )
			$instance['num_columns'] = strip_tags( $new_instance['num_columns'] );
		if ( isset( $new_instance['max_posts'] ) )
			$instance['max_posts'] = strip_tags( $new_instance['max_posts'] );

		return $instance;
	}

	/**
	 * Enqueue admin javascript.
	 */
	public function admin_setup() {

		wp_enqueue_media();
		wp_enqueue_script( 'team-section-widget-js', plugin_dir_url( __FILE__ ) . 'js/team-section-widget.js', array( 'jquery', 'media-upload', 'media-views' ) );
		wp_enqueue_style( 'organic-widgets-team-section-widget-css', plugin_dir_url( __FILE__ ) . 'css/team-section-widget.css' );

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

		wp_enqueue_script( 'organic-widgets-masonry', ORGANIC_WIDGETS_BASE_DIR . 'public/js/masonry.js', array( 'jquery', 'media-upload', 'media-views', 'masonry' ) );
		wp_enqueue_script( 'team-section-widget-public-js', ORGANIC_WIDGETS_BASE_DIR . 'public/js/team-section.js', array( 'jquery', 'media-upload', 'media-views', 'masonry' ) );

	}



} // class Organic_Widgets_Team_Section_Widget

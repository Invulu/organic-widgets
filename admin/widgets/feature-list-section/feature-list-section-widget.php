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
		$feature_list_summary = ( isset( $instance['feature_list_summary'] ) && '' != $instance['feature_list_summary'] ) ? $instance['feature_list_summary'] : false;
		$button_text = ( isset( $instance['button_text'] ) && '' != $instance['button_text'] ) ? $instance['button_text'] : false;
		$button_url = ( isset( $instance['button_url'] ) && '' != $instance['button_url'] ) ? $instance['button_url'] : false;
		$title = $instance['title'];
		$num_columns = ( isset( $instance['num_columns'] ) ) ? $instance['num_columns'] : 3;
		$features_array = ( isset( $instance['features_array'] ) ) ? json_decode( $instance['features_array'], true) :  array();

		echo $args['before_widget'];


		?>

		<div class="organic-widgets-section organic-widgets-feature-list-section<?php if ( 0 < $bg_image_id ) { ?> has-thumb text-white<?php } ?>" <?php if ( 0 < $bg_image_id ) { ?>style="background-image:url(<?php echo $bg_image; ?>);"<?php } elseif ($bg_color) { ?>style="background-color:<?php echo $bg_color; ?>;"<?php } ?>>

			<?php if ( ! empty( $title ) ) { ?>
				<h3><?php echo esc_html( $title ); ?></h3>
			<?php } ?>
			<?php if ( ! empty( $feature_list_summary ) ) { ?>
				<p class="summary"><?php echo $feature_list_summary ?></p>
			<?php } ?>

			<?php if ( $features_array && count( $features_array ) ) { ?>

				<div class="organic-widgets-feature-list-items-wrapper">

					<?php foreach ( $features_array as $feature ) { ?>

						<div class="organic-widgets-feature-list-item">

							<div class="organic-widgets-feature-list-item-image">
								<p><?php echo $feature['image']; ?></p>
							</div>

							<div class="organic-widgets-feature-list-item-text">
								<h4><?php echo $feature['title']; ?></h4>
								<p><?php echo $feature['summary']; ?></p>
							</div>

						</div>

					<?php } ?>

				</div>

			<?php } ?>

			<?php if ( ! empty( $button_url ) ) { ?>
				<a href="<?php echo esc_url($button_url);?>"><button class="organic-widgets-button"><?php if ( ! empty($buttom_text) ) { echo esc_html($button_text); } else { _e( 'See More', ORGANIC_WIDGETS_18N); } ?></button></a>
			<?php } ?>

		</div>

		<?php

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

		$this->id_prefix = $this->get_field_id('');

		if ( isset( $instance['bg_image_id'] ) ) {
			$bg_image_id = $instance['bg_image_id'];
		} else { $bg_image_id = 0; }

		if ( isset( $instance['bg_image_id'] ) && isset( $instance['bg_image'] ) ) {
			$bg_image = $instance['bg_image'];
		} else { $bg_image = false; }

		if ( isset( $instance['bg_color'] ) ) {
			$bg_color = $instance['bg_color'];
		} else { $bg_color = false; }

		if ( isset( $instance['bg_color'] ) ) {
			$bg_color = $instance['bg_color'];
		} else { $bg_color = false; }

		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else { $title = ''; }

		if ( isset( $instance[ 'button_text' ] ) ) {
			$button_text = $instance[ 'button_text' ];
		} else { $button_text = false; }

		if ( isset( $instance[ 'button_url' ] ) ) {
			$button_url = $instance[ 'button_url' ];
		} else { $button_url = false; }

		if ( isset( $instance[ 'feature_list_summary' ] ) ) {
			$feature_list_summary = $instance[ 'feature_list_summary' ];
		} else { $feature_list_summary = ''; }

		if ( isset( $instance['num_columns'] ) ) {
			$num_columns = $instance['num_columns'];
		} else { $num_columns = 3; }

		if ( isset( $instance['features_array'] ) ) {
			$features_array = json_decode( $instance['features_array'], true );
		} else {
			$features_array = array();
		}


		?>
		<div class="organic-widgets-feature-list-widget-admin">

			<h4>Content</h4>

			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Section Title:', ORGANIC_WIDGETS_18N); ?></label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'feature_list_summary' ); ?>"><?php _e('Section Content:', ORGANIC_WIDGETS_18N); ?></label>
				<textarea class="widefat" rows="6" cols="20" id="<?php echo $this->get_field_id( 'feature_list_summary' ); ?>" name="<?php echo $this->get_field_name( 'feature_list_summary' ); ?>"><?php echo $feature_list_summary; ?></textarea>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'button_text' ); ?>"><?php _e('Button Text:', ORGANIC_WIDGETS_18N); ?></label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'button_text' ); ?>" name="<?php echo $this->get_field_name( 'button_text' ); ?>" value="<?php echo $button_text; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'button_url' ); ?>"><?php _e('Button URL', ORGANIC_WIDGETS_18N); ?></label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'button_url' ); ?>" name="<?php echo $this->get_field_name( 'button_url' ); ?>" value="<?php echo $button_url; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'num_columns' ); ?>"><?php _e('Number of Columns:', ORGANIC_WIDGETS_18N); ?></label>
				<select id="<?php echo $this->get_field_id('num_columns'); ?>" name="<?php echo $this->get_field_name('num_columns'); ?>" class="widefat" style="width:100%;">
			    <option <?php selected( $num_columns, '2'); ?> value="2">2</option>
			    <option <?php selected( $num_columns, '3'); ?> value="3">3</option>
			    <option <?php selected( $num_columns, '4'); ?> value="4">4</option>
				</select>
			</p>


			<hr/>

			<p>
				<label for="<?php echo $this->get_field_id( 'features_array' ); ?>"><h4><?php _e( 'Features:', ORGANIC_WIDGETS_18N ) ?></h4></label>

				<?php //Loop through each item and echo form section
				$form_keys = array();
				$form_orders = array();
				if ( is_array( $features_array ) ) {
					usort( $features_array, array( $this, 'sort_by_order' ) );
					foreach ( $features_array as $key => $feature ) {

						if ( isset( $feature['order'] ) ) {
							$order = $feature['order'];
						} else {
							$order = $key;
						}

						// Echo Form Item
						$this->echo_feature_list_form_item( $key, $order, $feature );

						// Add Key to Array
						array_push( $form_keys, $key );
						array_push( $form_orders, $order );

					}
				}

				// Get Next ID
				if ( count($form_keys) > 0 ) {
					$key = max( $form_keys ) + 1;
				} else {
					$key = 1;
				}

				// Get Next Order
				if ( count($form_orders) > 0 ) {
					$order = max( $form_orders ) + 1;
				} else {
					$order = 1;
				}

				// Echo Form Item
				$this->echo_feature_list_form_item( $key, $order ); ?>

				<div class="organic-widgets-feature-list-add-item">
					<i class="fa fa-plus"></i>
				</div>

				<input type="hidden" class="organic-widgets-feature-list-hidden-input" id="<?php echo $this->get_field_id('features_array'); ?>" name="<?php echo $this->get_field_name('features_array'); ?>" value='<?php if ( count($features_array) > 0 ){ echo json_encode($features_array); }?>' />

			</p>

			<hr />

			<?php $this->section_background_input_markup( $instance, $this->bg_options ); ?>

		</div>
		<?php
	}

	protected function echo_feature_list_form_item( $id, $order, $feature = false ) {
		$id = (int) $id;
		?>

		<div class="organic-widgets-feature-list-item-form-item" data-feature-id="<?php echo $id; ?>" data-order="<?php echo $order; ?>">

			<div class="organic-widgets-feature-list-select" data-val="<?php if ( $feature && $feature['icon'] ) { echo esc_attr($feature['icon']); } ?>" data-feature-id="<?php echo $id; ?>">
				<div class="organic-widget-dropdown-button">
					<div class="organic-widget-feature-list-select-icon"><i class="fa fa-angle-down"></i></div>
					<p><?php _e('Select Icon', ORGANIC_WIDGETS_18N); ?></p>
				</div>
				<div class="organic-widget-feature-move-button">
					<div class="organic-widget-move-up">
						<i class="fa fa-angle-up"></i>
					</div>
				</div>
				<div class="organic-widget-feature-move-button">
					<div class="organic-widget-move-down">
						<i class="fa fa-angle-down"></i>
					</div>
				</div>
				<div class="organic-widget-feature-delete-button">
					<i class="fa fa-trash"></i>
				</div>
				<div class="organic-widgets-clear"></div>
				<div class="organic-widgets-feature-list-select-dropdown">
					<?php $this->getIconOptionsDivs(); ?>
					<div class="organic-widgets-clear"></div>
				</div>
			</div>

			<div class="">

				<div class="organic-widgets-feature-list-icon-preview-wrapper">
					<p>
						<label><?php _e('Icon:', ORGANIC_WIDGETS_18N); ?></label>
					</p>
					<div class="organic-widgets-feature-list-icon-preview">
						<?php if ( $feature && isset( $feature['icon'] ) ) { ?>
							<i class="fa <?php echo esc_attr($feature['icon']); ?>"></i>
						<?php } ?>
					</div>
				</div>

				<div class="organic-widgets-feature-list-text-fields-wrapper">
					<p>
						<label><?php _e( 'Feature Title:', ORGANIC_WIDGETS_18N ) ?></label>
						<input class="widefat organic-widgets-feature-list-title-input" type="text" value="<?php if ( $feature && $feature['title'] ) echo esc_html($feature['title']); ?>" />
					</p>
					<p>
						<label><?php _e( 'Feature Summary:', ORGANIC_WIDGETS_18N ) ?></label>
						<textarea class="widefat organic-widgets-feature-list-summary-input" rows="3" cols="20" ><?php if ( $feature && $feature['summary'] ) echo esc_html($feature['summary']); ?></textarea>
					</p>
				</div>

				<div class="organic-widgets-clear"></div>

			</div>

		</div>

		<?php
	}

	/**
	 * Function for sorting arrays with usort
	 *
	 * @param array $item to be compared with b
	 * @param array $item to be compared with a
	 *
	 * @return int comparator
	 */
	protected function sort_by_order($a, $b) {

		if ( isset( $a['order'] ) && isset( $b['order'] ) ) {
			return $a['order'] - $b['order'];
		} else {
			return -1;
		}

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

		if ( isset( $new_instance['bg_image_id'] ) )
			$instance['bg_image_id'] = strip_tags( $new_instance['bg_image_id'] );
		if ( isset( $new_instance['bg_image'] ) )
			$instance['bg_image'] = strip_tags( $new_instance['bg_image'] );
		if ( isset( $new_instance['bg_color'] ) && $this->check_hex_color( $new_instance['bg_color'] ) ) {
			$instance['bg_color'] = strip_tags( $new_instance['bg_color'] );
		} else {
			$instance['bg_color'] = false;
		}
		if ( isset( $new_instance['title'] ) )
			$instance['title'] = strip_tags( $new_instance['title'] );
		if ( isset( $new_instance['button_text'] ) )
			$instance['button_text'] = strip_tags( $new_instance['button_text'] );
		if ( isset( $new_instance['button_url'] ) )
			$instance['button_url'] = strip_tags( $new_instance['button_url'] );
		if ( isset( $new_instance['feature_list_summary'] ) )
			$instance['feature_list_summary'] = strip_tags( $new_instance['feature_list_summary'] );
		if ( isset( $new_instance['num_columns'] ) )
			$instance['num_columns'] = strip_tags( $new_instance['num_columns'] );
		if ( isset( $new_instance['features_array'] ) ) {
			$features_array = json_decode($new_instance['features_array'],true);
			error_log(print_r($features_array,true));
			foreach( $features_array as $key => $feature ) {

			}
			$instance['features_array'] = $new_instance['features_array'];
		}



		//Widget Title
		if ( isset( $new_instance['title'] )  && '' != $new_instance['title'] ) {
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
		wp_enqueue_script( 'organic-widgets-feature-list-section-widget-js', plugin_dir_url( __FILE__ ) . 'js/feature-list-section-widget.js', array( 'jquery', 'media-upload', 'media-views' ) );
		wp_enqueue_style( 'organic-widgets-feature-list-section-widget-css', plugin_dir_url( __FILE__ ) . 'css/feature-list-section-widget.css' );

		wp_enqueue_style( 'organic-widgets-fontawesome', ORGANIC_WIDGETS_BASE_DIR . 'public/css/font-awesome.css' );

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

		if ( ! wp_script_is('organic-widgets-backgroundimagebrightness-js') ) { wp_enqueue_script( 'organic-widgets-backgroundimagebrightness-js', ORGANIC_WIDGETS_BASE_DIR . 'public/js/jquery.backgroundbrightness.js', array( 'jquery' ) ); }

		// wp_enqueue_style( 'organic-widgets-fontawesome', ORGANIC_WIDGETS_BASE_DIR . 'public/css/font-awesome.css' );

	}


} // class Organic_Widgets_Feature_List_Section_Widget

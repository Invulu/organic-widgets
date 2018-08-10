<?php
/* Registers a widget to show a features on a page */

// Block direct requests.
if ( !defined('ABSPATH') )
	die( '-1' );

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
			__( 'Organic Feature List', ORGANIC_WIDGETS_18N ), // Name
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
		$text = ( isset( $instance['text'] ) && '' != $instance['text'] ) ? $instance['text'] : false;
		$button_text = ( isset( $instance['button_text'] ) && '' != $instance['button_text'] ) ? $instance['button_text'] : false;
		$button_url = ( isset( $instance['button_url'] ) && '' != $instance['button_url'] ) ? $instance['button_url'] : false;
		$num_columns = ( isset( $instance['num_columns'] ) ) ? $instance['num_columns'] : 4;
		$repeatable_array = ( isset( $instance['repeatable_array'] ) ) ? json_decode( $instance['repeatable_array'], true) :  array();

		echo $args['before_widget']; ?>

		<div class="organic-widgets-section organic-widgets-feature-list-section" <?php if ( 0 < $bg_image_id ) { ?>style="background-image:url(<?php echo $bg_image; ?>);"<?php } elseif ($bg_color) { ?>style="background-color:<?php echo $bg_color; ?>;"<?php } ?>>

			<!-- BEGIN .organic-widgets-content -->
			<div class="organic-widgets-content">

			<?php if ( ! empty( $instance['title'] ) ) { ?>
				<h2 class="organic-widgets-title"><?php echo apply_filters( 'organic_widget_title', $instance['title'] );?></h2>
			<?php } ?>

			<?php if ( ! empty( $instance['text'] ) ) { ?>
				<div class="organic-widgets-text"><?php echo apply_filters( 'the_content', $instance['text'] ); ?></div>
			<?php } ?>

				<div class="organic-widgets-feature-list-items-wrapper organic-widgets-flex-row organic-widgets-flex-wrap">

					<?php
					if ( is_array( $repeatable_array ) && count( $repeatable_array ) ) {
						$incrementer = 0;
						usort( $repeatable_array, array( $this, 'sort_by_order' ) );
						foreach ( $repeatable_array as $key => $repeatable ) {

							if ( $repeatable && isset( $repeatable['icon'] ) ) {
								$icon_type = strpos( $repeatable['icon'], 'fa' ) !== false ? 'fontawesome' : 'image';
							} else {
								$icon_type = false;
							}

							$incrementer++;
							?>

							<div class="organic-widgets-feature-list-item organic-widgets-<?php echo $this->column_string( $num_columns ); ?>">

								<div class="organic-widgets-feature-list-item-content">

									<div class="organic-widgets-feature-list-item-icon">

										<?php if ( $icon_type == 'image' ) { ?>
											<?php echo $this->get_image_html( $instance, $repeatable ); ?>
										<?php } elseif ( $icon_type == 'fontawesome' ) { ?>
											<i class="fa <?php echo esc_attr( $repeatable['icon'] ); ?>"></i>
										<?php } ?>

									</div>

									<div class="organic-widgets-feature-list-item-text">
										<h6>
											<?php if ( '' != $repeatable['link_url'] ) { echo '<a href="' . esc_url( $repeatable['link_url'] ) . '">'; } ?>
												<?php if ( array_key_exists( 'title', $repeatable ) ) { echo esc_html( $repeatable['title'] ); } ?>
											<?php if ( '' != $repeatable['link_url'] ) { echo '</a>'; } ?>
										</h6>
										<p>
											<?php if ( array_key_exists( 'text', $repeatable ) ) { echo $repeatable['text']; }?>
										</p>
									</div>

								</div>

							</div>

						<?php }
					}
					?>

				</div>

			<?php if ( ! empty( $button_url ) ) { ?>
				<div class="organic-widgets-button-holder">
					<a class="organic-widgets-button button" href="<?php echo esc_url( $button_url );?>"><?php if ( ! empty( $button_text ) ) { echo esc_html( $button_text ); } else { _e( 'See More', ORGANIC_WIDGETS_18N); } ?></a>
				</div>
			<?php } ?>

			<!-- END .organic-widgets-content -->
			</div>

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

		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title' => '',
				'text' => '',
			)
		);

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

		if ( isset( $instance[ 'text' ] ) ) {
			$text = $instance[ 'text' ];
		} else { $text = ''; }

		if ( isset( $instance['num_columns'] ) ) {
			$num_columns = $instance['num_columns'];
		} else { $num_columns = 4; }

		if ( isset( $instance['repeatable_array'] ) ) {
			$repeatable_array = json_decode( $instance['repeatable_array'], true );
		} else {
			$repeatable_array = array();
		}
		?>

		<div class="organic-widgets-repeatable-form-item-widget-admin">

			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" class="title" type="hidden" value="<?php echo esc_attr( $instance['title'] ); ?>">
			<input id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" class="text organic-widgets-wysiwyg-anchor" type="hidden" value="<?php echo esc_attr( $instance['text'] ); ?>">
			<input id="<?php echo $this->get_field_id( 'filter' ); ?>" name="<?php echo $this->get_field_name( 'filter' ); ?>" class="filter" type="hidden" value="on">
			<input id="<?php echo $this->get_field_id( 'visual' ); ?>" name="<?php echo $this->get_field_name( 'visual' ); ?>" class="visual" type="hidden" value="on">

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
					<option <?php selected( $num_columns, '2' ); ?> value="2">2</option>
					<option <?php selected( $num_columns, '3' ); ?> value="3">3</option>
					<option <?php selected( $num_columns, '4' ); ?> value="4">4</option>
					<option <?php selected( $num_columns, '5' ); ?> value="5">5</option>
					<option <?php selected( $num_columns, '6' ); ?> value="6">6</option>
				</select>
			</p>

			<hr/>

			<?php $this->repeatable_form_item_inputs_markup( $repeatable_array, 'Features', $instance ); ?>

			<?php $this->section_background_input_markup( $instance, $this->bg_options ); ?>

		</div>
		<?php
	}

	/**
	 * Render form template scripts.
	 *
	 *
	 * @access public
	 */
	public function render_control_template_scripts() {
		?>
		<script type="text/html" id="tmpl-widget-organic_widgets_feature_list_section-control-fields">

			<# var elementIdPrefix = 'el' + String( Math.random() ).replace( /\D/g, '' ) + '_' #>

			<p><b><?php _e( 'Add Custom Content:', ORGANIC_WIDGETS_18N ) ?></b></p>

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
	 * Ouput HTML for a Repeatable Feature List Form Item
	 *
	 *
	 * @access protected
	 */
	protected function echo_repeatable_form_item( $id = 'template', $order = 'template', $repeatable = false, $instance = false ) {

		if ( $id === 'template' || $order === 'template' ) {
			$template = true;
			$id = '__x__';
			$order = '';
		} else {
			$template = false;
			$id = (int) $id;
			$order = (int) $order;
		}
		$icon_id_string = '_reapeatable_icon_image-' . $id;

		if ( $repeatable && isset( $repeatable['icon'] ) ) {
			$icon_type = strpos( $repeatable['icon'], 'fa' ) !== false ? 'fontawesome' : 'image';
		} else {
			$icon_type = false;
		}

		?>

		<div class="<?php if ( $template ) { echo 'organic-widgets-repeatable-form-item-template'; } else { echo 'organic-widgets-repeatable-form-item'; } ?>" data-feature-id="<?php echo $id; ?>" data-order="<?php echo $order; ?>">

			<div class="organic-widgets-repeatable-form-item-title-bar">
				Feature <span class="organic-widgets-repeatable-item-number"><?php echo $order + 1; ?></span>
			</div>

			<div class="organic-widgets-repeatable-form-item-fields-wrapper">

				<div class="organic-widgets-feature-list-icon-preview-wrapper">
					<p>
						<label><?php _e('Icon:', ORGANIC_WIDGETS_18N); ?></label>
					</p>
					<div class="organic-widgets-feature-list-icon-preview" id="<?php echo( $this->get_field_id( 'image_preview' . $icon_id_string ) ); ?>">
						<?php if ( $icon_type && $icon_type == 'image' && $repeatable['icon'] > 0 ) { ?>
							<?php echo $this->get_image_html( $instance, $repeatable ); ?>
						<?php } else if ( $icon_type == 'fontawesome' ) { ?>
							<i class="fa <?php echo esc_attr($repeatable['icon']); ?>"></i>
						<?php } ?>
					</div>
				</div>

				<div class="organic-widgets-feature-list-text-fields-wrapper">

					<p>
						<label><?php _e( 'Feature Title:', ORGANIC_WIDGETS_18N ) ?></label>
						<input class="widefat organic-widgets-feature-list-title-input organic-widgets-repeatable-form-item-input" data-input-name="title" data-activator="true" type="text" value="<?php if ( $repeatable && array_key_exists( 'title', $repeatable ) ) echo esc_html($repeatable['title']); ?>" data-feature-id="<?php echo $id; ?>"/>
					</p>

					<p>
						<label><?php _e( 'Feature Link URL:', ORGANIC_WIDGETS_18N ) ?></label>
						<input class="widefat organic-widgets-feature-list-link-url-input organic-widgets-repeatable-form-item-input" data-input-name="link_url" type="text" value="<?php if ( $repeatable && array_key_exists( 'link_url', $repeatable ) ) echo esc_url($repeatable['link_url']); ?>" data-feature-id="<?php echo $id; ?>"/>
					</p>

				</div>

				<div class="organic-widgets-clear"></div>

				<label><?php _e( 'Select Icon:', ORGANIC_WIDGETS_18N ); ?></label>
				<p>
					<div class="organic-widgets-feature-list-select">
						<div class="organic-widgets-dropdown-button">
							<div class="organic-widgets-feature-list-select-icon"><i class="fa fa-angle-down"></i></div>
							<p><?php _e('Select Icon', ORGANIC_WIDGETS_18N); ?></p>
						</div>
						<div class="organic-widgets-clear"></div>
						<div class="organic-widgets-feature-list-select-dropdown">
							<?php $this->getIconOptionsDivs(); ?>
							<div class="organic-widgets-clear"></div>
						</div>
					</div>
				</p>

				<p>
					<label for="<?php echo $this->get_field_id( 'bg_image' ); ?>"><?php _e( 'Custom Icon Image:', ORGANIC_WIDGETS_18N ); ?></label>
					<div class="organic-widgets-image-uploader">
						<div class="organic-widgets-upload-image-button button" id="<?php echo( $this->get_field_id( 'uploader_button' . $icon_id_string ) ); ?>" onclick="organicWidgetFeatureIconImage.uploader( '<?php echo $this->id; ?>', '<?php echo $this->id_prefix;?>', '<?php echo $icon_id_string; ?>' ); return false;" ><?php if ( isset( $repeatable['icon'] ) ) { _e( 'Change Image', ORGANIC_WIDGETS_18N ); } else { _e( 'Select Image', ORGANIC_WIDGETS_18N ); }?></div>
						<div class="organic-widgets-remove-image-button button" id="<?php echo( $this->get_field_id( 'remover_button' . $icon_id_string ) ); ?>" onclick="organicWidgetFeatureIconImage.remover( '<?php echo $this->id; ?>', '<?php echo $this->id_prefix;?>', '<?php echo $icon_id_string; ?>', 'remover_button' ); return false;" <?php if ( ! isset( $repeatable['icon'] ) || $repeatable['icon'] < 1 ) echo( 'style="display:none;"' ); ?>><?php _e( 'Remove Image', ORGANIC_WIDGETS_18N ); ?></div>
						<input class="organic-widgets-repeatable-form-item-input" data-input-name="icon" data-activator="true" type="hidden" id="<?php echo( $this->get_field_id( 'icon' . $icon_id_string ) ); ?>" name="<?php echo( $this->get_field_name( 'icon' . $icon_id_string ) ); ?>" value="<?php if ( $icon_type ) echo esc_attr( $repeatable['icon'] ); ?>" data-feature-id="<?php echo $id; ?>"/>
					</div>
				</p>

				<p>
					<label><?php _e( 'Feature text:', ORGANIC_WIDGETS_18N ) ?></label>
					<textarea class="widefat organic-widgets-feature-list-text-input organic-widgets-repeatable-form-item-input" data-input-name="text" data-activator="true" rows="3" cols="20" ><?php if ( $repeatable && array_key_exists( 'text', $repeatable ) ) echo $repeatable['text']; ?></textarea>
				</p>

				<div class="organic-widgets-clear"></div>

				<?php $this->echo_repeatable_form_item_actions(); ?>

			</div>

		</div>

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
		if ( isset( $new_instance['bg_image_id'] ) )
			$instance['bg_image_id'] = strip_tags( $new_instance['bg_image_id'] );
		if ( isset( $new_instance['bg_image'] ) )
			$instance['bg_image'] = strip_tags( $new_instance['bg_image'] );
		if ( isset( $new_instance['bg_color'] ) && $this->check_hex_color( $new_instance['bg_color'] ) ) {
			$instance['bg_color'] = strip_tags( $new_instance['bg_color'] );
		} else {
			$instance['bg_color'] = false;
		}
		if ( isset( $new_instance['button_text'] ) )
			$instance['button_text'] = strip_tags( $new_instance['button_text'] );
		if ( isset( $new_instance['button_url'] ) )
			$instance['button_url'] = strip_tags( $new_instance['button_url'] );
		if ( isset( $new_instance['num_columns'] ) )
			$instance['num_columns'] = strip_tags( $new_instance['num_columns'] );
		if ( isset( $new_instance['repeatable_array'] ) ) {
			$instance['repeatable_array'] = $new_instance['repeatable_array'];
		}

		return $instance;
	}

	/**
	 * Enqueue all the javascript.
	 */
	public function admin_setup() {

		// Text Editor
		wp_enqueue_editor();

		wp_enqueue_script( 'organic-widgets-feature-list-text-title', plugin_dir_url( __FILE__ ) . 'js/feature-list-widgets.js', array( 'jquery' ) );
		wp_localize_script( 'organic-widgets-feature-list-text-title', 'OrganicFeatureListWidget', array(
			'id_base' => $this->id_base,
		) );
		wp_add_inline_script( 'organic-widgets-feature-list-text-title', 'wp.organicFeatureListWidget.init();', 'after' );

		wp_enqueue_media();

		// Repeatable Form Items
		wp_enqueue_script( 'organic-widgets-module-repeatable-form-item-js', ORGANIC_WIDGETS_ADMIN_JS_DIR . 'organic-widgets-module-repeatable-form-items.js', array( 'jquery' ) );

		wp_enqueue_style( 'organic-widgets-fontawesome', ORGANIC_WIDGETS_BASE_DIR . 'public/css/font-awesome.css' );

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_script( 'organic-widgets-module-color-picker', ORGANIC_WIDGETS_ADMIN_JS_DIR . 'organic-widgets-module-color-picker.js', array( 'jquery', 'wp-color-picker' ) );

		wp_enqueue_script( 'organic-widgets-module-image-background', ORGANIC_WIDGETS_ADMIN_JS_DIR . 'organic-widgets-module-image-background.js', array( 'jquery', 'media-upload', 'media-views', 'wp-color-picker' ) );
		wp_localize_script( 'organic-widgets-module-image-background', 'OrganicWidgetBG', array(
			'frame_title' => __( 'Select an Image', ORGANIC_WIDGETS_18N ),
			'button_title' => __( 'Insert Into Widget', ORGANIC_WIDGETS_18N ),
		) );
		wp_enqueue_script( 'organic-widgets-module-repeatable-icon-image', ORGANIC_WIDGETS_ADMIN_JS_DIR . 'organic-widgets-module-repeatable-icon-image.js', array( 'jquery', 'media-upload', 'media-views', 'wp-color-picker' ) );
		wp_localize_script( 'organic-widgets-module-repeatable-icon-image', 'RepeatableIcon', array(
			'frame_title' => __( 'Select an Image', ORGANIC_WIDGETS_18N ),
			'button_title' => __( 'Insert Into Widget', ORGANIC_WIDGETS_18N ),
		) );

	}

	/**
	 * Enqueue public javascript.
	 */
	public function public_scripts() {

		wp_enqueue_style( 'organic-widgets-fontawesome', ORGANIC_WIDGETS_BASE_DIR . 'public/css/font-awesome.css' );
		if ( ! wp_script_is('organic-widgets-backgroundimagebrightness-js') ) { wp_enqueue_script( 'organic-widgets-backgroundimagebrightness-js', ORGANIC_WIDGETS_BASE_DIR . 'public/js/jquery.backgroundbrightness.js', array( 'jquery' ) ); }

	}


} // class Organic_Widgets_Feature_List_Section_Widget

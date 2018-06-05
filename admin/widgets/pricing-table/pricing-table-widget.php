<?php
/* Registers a widget to show a profile */

// Block direct requests.
if ( ! defined( 'ABSPATH' ) )
	die( '-1' );

/**
 * Adds Organic_Widgets_Pricing_Table_Widget widget.
 */
class Organic_Widgets_Pricing_Table_Widget extends Organic_Widgets_Custom_Widget {

	const CUSTOM_IMAGE_SIZE_SLUG = 'organic_widgets_widget_image_upload';

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'organic_widgets_pricing_table', // Base ID
			__( 'Organic Pricing Table', ORGANIC_WIDGETS_18N ), // Name
			array(
				'description' => __( 'Display a table of features and prices', ORGANIC_WIDGETS_18N ),
				'customize_selective_refresh' => false,
			) // Args
		);

		$this->id_prefix = $this->get_field_id('');

		// Bg options
		$this->bg_options = array(
			'color' => true,
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

		$instance['bg_color'] = isset( $instance['bg_color'] ) ? $instance['bg_color'] : false;
		$instance['bg_image_id'] = isset( $instance['bg_image_id'] ) ? $instance['bg_image_id'] : false;
		$instance['bg_image'] = ( isset( $instance['bg_image'] ) && '' != $instance['bg_image'] ) ? $instance['bg_image'] : false;

		$group = $this->organic_widgets_groupable_widget( $args );
		$group_id = $group['group_id'];
		if ( $group['first'] && ! $group['last'] ) {
			$first_last = ' organic-widgets-groupable-first';
		} elseif ( $group['last'] && ! $group['first'] ) {
			$first_last = ' organic-widgets-groupable-last';
		} else {
			$first_last = false;
		}
		$repeatable_array = ( isset( $instance['repeatable_array'] ) && '' != $instance['repeatable_array'] ) ? json_decode( $instance['repeatable_array'], true) :  array();
		?>

		<?php echo $args['before_widget']; ?>

			<!-- BEGIN .organic-widgets-section -->
			<div class="organic-widgets-section organic_widgets-pricing-table-section<?php if ( $first_last ) { echo esc_attr( $first_last ); } ?>" <?php if ($instance['bg_color']) { ?>style="background-color:<?php echo $instance['bg_color']; ?>;"<?php } ?> <?php if ($group_id) { echo 'data-group-id="' . $group_id . '"'; } ?>>

				<?php if ( ! empty( $instance['title'] ) || ! empty( $instance['text'] ) || ! empty( $instance['subtitle'] ) ) { ?>

					<!-- BEGIN .organic-widgets-pricing-table -->
					<div class="organic-widgets-pricing-table">

						<!-- BEGIN .organic-widgets-card -->
						<div class="organic-widgets-card">

							<?php if ( $instance['bg_image_id'] > 0 ) { ?>
								<div class="organic-widgets-profile-img"><img src="<?php echo $instance['bg_image']; ?>" alt="<?php __( 'Pricing Table Image', ORGANIC_WIDGETS_18N ) ?>" /></div>
							<?php } ?>

							<!-- BEGIN .organic-pricing-table-content -->
							<div class="organic-pricing-table-content">

								<table>

									<thead>
										<tr>
											<th>
												<?php if ( ! empty( $instance['title'] ) ) { ?>
													<h4 class="organic-widgets-pricing-table-title"><?php echo apply_filters( 'organic_widget_title', $instance['title'] ); ?></h4>
												<?php } ?>

													<div class="organic-widgets-pricing-table-divider"></div>

												<?php if ( ! empty( $instance['text'] ) ) { ?>
													<div class="organic-widgets-pricing-table-excerpt"><?php echo apply_filters( 'the_content', $instance['text'] ); ?></div>
												<?php } ?>
											</th>
										</tr>
									</thead>

									<tbody>
									<?php foreach( $repeatable_array as $row ) { ?>
										<tr>
											<td><?php echo $row['text_row']; ?></td>
										</tr>
									<?php } ?>
									</tbody>

									<?php if ( ! empty( $instance['purchase_button_url'] ) ) { ?>
										<tfoot>
											<tr>
												<?php if ( ! empty( $instance['purchase_button_url'] ) ) { ?>
													<td class="organic-widgets-pricing-table-purchase-wrapper">
														<a class="organic-widgets-button button" href="<?php echo esc_url( $instance['purchase_button_url'] );?>"><?php if ( ! empty( $instance['purchase_button_text'] ) ) { echo esc_html( $instance['purchase_button_text'] ); } else { _e( 'See More', ORGANIC_WIDGETS_18N); } ?></a>
													</td>
												<?php } ?>
											</tr>
										</tfoot>
									<?php } ?>

								</table>

							<!-- END .organic-pricing-table-content -->
							</div>

						<!-- END .organic-widgets-card -->
						</div>

					<!-- END .organic-widgets-pricing-table -->
					</div>

				<?php } //End Conditional checking for content ?>

			<!-- END .organic-widgets-section -->
			</div>

			<?php echo $args['after_widget'];

	} // End widget()

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

		if (!isset( $instance['bg_image'] ) ) {
			$instance['bg_image'] = false;
		}
		if (!isset( $instance['bg_image_id'] ) || $instance['bg_image_id'] < 1 ) {
			$instance['bg_image_id'] = 0;
		}
		if ( isset( $instance[ 'subtitle' ] ) ) {
			$subtitle = $instance[ 'subtitle' ];
		} else { $subtitle = ''; }
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
				<label for="<?php echo $this->get_field_id( 'purchase_button_text' ); ?>"><?php _e('Purchase Button Text:', ORGANIC_WIDGETS_18N); ?></label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'purchase_button_text' ); ?>" name="<?php echo $this->get_field_name( 'purchase_button_text' ); ?>" value="<?php if ( ! empty( $instance['purchase_button_text'] ) ) echo $instance['purchase_button_text']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'purchase_button_url' ); ?>"><?php _e('Purchase Button URL', ORGANIC_WIDGETS_18N); ?></label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'purchase_button_url' ); ?>" name="<?php echo $this->get_field_name( 'purchase_button_url' ); ?>" value="<?php if ( ! empty( $instance['purchase_button_url'] ) ) echo $instance['purchase_button_url']; ?>" />
			</p>

			<?php $this->bg_image_scripts(); ?>

			<p>
				<label for="<?php echo $this->get_field_id( 'bg_image' ); ?>"><?php _e( 'Pricing Table Image:', ORGANIC_WIDGETS_18N ) ?></label>
				<div class="uploader">
					<input type="submit" class="button" name="<?php echo $this->get_field_name('uploader_button'); ?>" id="<?php echo $this->get_field_id('uploader_button'); ?>" value="<?php if ( $instance['bg_image_id'] ) { _e( 'Change Image', ORGANIC_WIDGETS_18N ); }else { _e( 'Select Image', ORGANIC_WIDGETS_18N ); }?>" onclick="organicWidgetBackgroundImage.uploader( '<?php echo $this->id; ?>', '<?php echo $this->id_prefix; ?>' ); return false;" />
					<input type="submit" class="organic_widgets-remove-image-button button" name="<?php echo $this->get_field_name('remover_button'); ?>" id="<?php echo $this->get_field_id('remover_button'); ?>" value="<?php _e('Remove Image', ORGANIC_WIDGETS_18N); ?>" onclick="organicWidgetBackgroundImage.remover( '<?php echo $this->id; ?>', '<?php echo $this->id_prefix; ?>', 'remover_button' ); return false;" <?php if ( $instance['bg_image_id'] < 1 ) { echo( 'style="display:none;"' ); } ?>/>
					<div class="organic-widgets-widget-image-preview" id="<?php echo $this->get_field_id('preview'); ?>">
						<?php echo $this->get_image_html($instance); ?>
					</div>
					<input type="hidden" id="<?php echo $this->get_field_id('bg_image_id'); ?>" name="<?php echo $this->get_field_name('bg_image_id'); ?>" value="<?php echo abs($instance['bg_image_id']); ?>" />
					<input type="hidden" id="<?php echo $this->get_field_id('bg_image'); ?>" name="<?php echo $this->get_field_name('bg_image'); ?>" value="<?php echo $instance['bg_image']; ?>" />
				</div>
			</p>

			<hr/>

			<?php $this->repeatable_form_item_inputs_markup( $repeatable_array, 'Text Rows' ); ?>

			<?php $this->section_background_input_markup( $instance, $this->bg_options ); ?>

		</div>

  <?php
	}

	/**
	 * Ouput HTML for a Repeatable Social Link Form Item
	 *
	 *
	 * @access protected
	 */
	 protected function echo_repeatable_form_item( $id = 'template', $order = 'template', $repeatable = false ) {

		 if ( $id === 'template' || $order === 'template' ) {
			 $template = true;
			 $id = '__x__';
			 $order = '';
		 } else {
			 $template = false;
			 $id = (int) $id;
			 $order = (int) $order;
		 }
		?>

		<div class="<?php if ( $template ) { echo 'organic-widgets-repeatable-form-item-template'; } else { echo 'organic-widgets-repeatable-form-item'; } ?>" data-feature-id="<?php echo $id; ?>" data-order="<?php echo $order; ?>">

			<div class="organic-widgets-repeatable-form-item-title-bar">
				Text Row <span class="organic-widgets-repeatable-item-number"><?php echo $order + 1; ?></span>
			</div>

			<div class="organic-widgets-repeatable-form-item-fields-wrapper">

				<p>
					<label style="display:none;"><?php _e( 'Text Row:', ORGANIC_WIDGETS_18N ) ?></label>
					<input class="widefat organic-widgets-feature-list-text-row-input organic-widgets-repeatable-form-item-input" data-input-name="text_row" data-activator="true" type="text" value="<?php if ( $repeatable && array_key_exists( 'text_row', $repeatable ) ) { echo $repeatable['text_row']; } ?>" />
				</p>

				<div class="organic-widgets-clear"></div>

			</div>

			<?php $this->echo_repeatable_form_item_actions(); ?>

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
		<script type="text/html" id="tmpl-widget-organic_widgets_pricing_table-control-fields">

			<# var elementIdPrefix = 'el' + String( Math.random() ).replace( /\D/g, '' ) + '_' #>

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
		if (isset( $new_instance['bg_image_id'] ) )
			$instance['bg_image_id'] = strip_tags( $new_instance['bg_image_id'] );
		if (isset( $new_instance['bg_image'] ) )
			$instance['bg_image'] = strip_tags( $new_instance['bg_image'] );
		if ( isset( $new_instance['purchase_button_text'] ) )
			$instance['purchase_button_text'] = strip_tags( $new_instance['purchase_button_text'] );
		if ( isset( $new_instance['purchase_button_url'] ) )
			$instance['purchase_button_url'] = strip_tags( $new_instance['purchase_button_url'] );
		if ( isset( $new_instance['bg_color'] ) && $this->check_hex_color( $new_instance['bg_color'] ) ) {
			$instance['bg_color'] = strip_tags( $new_instance['bg_color'] );
		} else {
			$instance['bg_color'] = false;
		}
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
		wp_enqueue_script( 'organic-widgets-pricing-tables-text-title', plugin_dir_url( __FILE__ ) . 'js/pricing-table-widgets.js', array( 'jquery' ) );
		wp_localize_script( 'organic-widgets-pricing-tables-text-title', 'OrganicPricingTableWidget', array(
			'id_base' => $this->id_base,
		) );
		wp_add_inline_script( 'organic-widgets-pricing-tables-text-title', 'wp.organicPricingTableWidget.init();', 'after' );

		// Groupable Widget
		wp_enqueue_script( 'organic-widgets-module-groupable-widgets', ORGANIC_WIDGETS_ADMIN_JS_DIR . 'organic-widgets-module-groupable-widgets.js', array( 'jquery' ) );
		wp_localize_script( 'organic-widgets-module-groupable-widgets', 'GroupableWidgets', array(
			'active_pane' => false,
			'widgets' => array()
		) );

		// Repeatable Form Items
		wp_enqueue_script( 'organic-widgets-module-repeatable-form-item-js', ORGANIC_WIDGETS_ADMIN_JS_DIR . 'organic-widgets-module-repeatable-form-items.js', array( 'jquery' ) );

		wp_enqueue_media();

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_script( 'organic-widgets-module-color-picker', ORGANIC_WIDGETS_ADMIN_JS_DIR . 'organic-widgets-module-color-picker.js', array( 'jquery', 'wp-color-picker' ) );

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

		wp_enqueue_style( 'organic-widgets-fontawesome', ORGANIC_WIDGETS_BASE_DIR . 'public/css/font-awesome.css' );
		if ( ! wp_script_is('organic-widgets-backgroundimagebrightness-js') ) { wp_enqueue_script( 'organic-widgets-backgroundimagebrightness-js', ORGANIC_WIDGETS_BASE_DIR . 'public/js/jquery.backgroundbrightness.js', array( 'jquery' ) ); }

	}

} // class Organic_Widgets_Pricing_Table_Widget

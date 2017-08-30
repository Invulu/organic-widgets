<?php
/**
 * Child of the WP_Widget class
 *
 * @link       https://organicthemes.com
 * @since      1.0.0
 *
 *
 * @package    Organic_Widgets
 * @subpackage Organic_Widgets/admin
 */


// Block direct requests.
if ( !defined('ABSPATH') )
	die( '-1' );

class Organic_Widgets_Custom_Widget extends WP_Widget {

  /**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Organic_Widgets_Custom_Widget    $id_prefix    id_prefix for a class
	 */
  protected $id_prefix;

  /**
	 * Array containing the background options to allow for the section
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Organic_Widgets_Custom_Widget    $bg_options    array of options for backgrounds
	 */
  protected $bg_options;

  /**
	 * Check if a given hex value is valid
	 *
	 * @since    	1.0.0
	 * @param     string    $color_value      The color value to check (hex code)
	 * @return    boolean   									True if $color_value is a valid hex code, else False
	 */
	protected function check_hex_color( $color_value ) {

    if ( preg_match( '/^#[a-f0-9]{6}$/i', $color_value ) ) {
        return true;
    }

    return false;

  }

  /**
   * Check if a given video url is valid
   *
   * @since    	1.0.0
   * @param     string    $url      The video url to check
   * @return    boolean   					True if $video_url is an accepted url, false if not
   */
  protected function check_video_url( $video_url ) {

    if ( 'youtube' == $this->get_video_type( $video_url ) ) {
			return true;
		} else {
			return false;
		}

  }

  /**
   * Get Source of Video
   *
   * @since    	1.0.0
   * @param     string    $video_url      The video url to check
   * @return    string   					        Video source
   */
  protected function get_video_type( $video_url ) {

    if ( strpos( $video_url, 'youtube' ) !== -1 ) {

  		return 'youtube';

  	} elseif ( strpos( $video_url, 'vimeo' ) !== -1  ) {

  		return 'vimeo';

  	} else {

  		return false;

  	}

  }

  /**
   * Get YouTube Video ID
   *
   * @since    	1.0.0
   * @param     string    $video_url      The video url to check
   * @return    string   					        Video ID
   */
  function youtube_id_from_url( $video_url ) {
	  $pattern = '%^# Match any youtube URL
  			(?:https?://)?  # Optional scheme. Either http or https
  			(?:www\.)?      # Optional www subdomain
  			(?:             # Group host alternatives
  				youtu\.be/    # Either youtu.be,
  			| youtube\.com  # or youtube.com
  				(?:           # Group path alternatives
  					/embed/     # Either /embed/
  				| /v/         # or /v/
  				| /watch\?v=  # or /watch\?v=
  				)             # End path alternatives.
  			)               # End host alternatives.
  			([\w-]{10,12})  # Allow 10-12 for 11 char youtube id.
  		$%x';
  	$result = preg_match( $pattern, $video_url, $matches );
  	if ( $result ) {
  		return $matches[1];
  	}
  	return false;
  }

  /**
   * Sanitize a String for a JS Variable Name
   *
   * @since    	1.0.0
   * @param     string    $input      Input string
   * @return    string   					    Sanitized string
   */
  protected function sanitize_js_variable($input) {

    $pattern = '/[^a-zA-Z0-9]/';

    return preg_replace($pattern, '', (string) $input);

  }

	/**
   * Get a spelling of a number
   *
   * @since    	1.0.0
   * @param     int    $input      Input number
   * @return    string   					 Spelled out number
   */
  protected function spell_number($input) {

    if ( $input == 2 ) return 'two';
		if ( $input == 3 ) return 'two';
		if ( $input == 4 ) return 'two';

		return '';

  }

	/**
   * Get column string from int
   *
   * @since    	1.0.0
   * @param     int    $input      Input number
   * @return    string   					 Column string
   */
  protected function column_string($input) {

		if ( $input == 1 ) return 'single';
		if ( $input == 2 ) return 'half';
		if ( $input == 3 ) return 'third';
		if ( $input == 4 ) return 'fourth';
		if ( $input == 5 ) return 'fifth';
		if ( $input == 6 ) return 'sixth';

		return '';

  }

	/**
	 * Enqueue Scripts for Background Color
	 *
	 * @since    	1.0.0
	 */
  public function bg_color_scripts() {

    // Scripts for Color Picker
    if ( ! wp_style_is( 'wp-color-picker', 'enqueued' ) ) {
      wp_enqueue_style( 'wp-color-picker' );
    }
		if ( ! wp_script_is( 'wp-color-picker', 'enqueued' ) ) {
      wp_enqueue_script( 'wp-color-picker' );
    }
    if ( ! wp_script_is( 'organic-widgets-module-color-picker', 'enqueued' ) ) {
      wp_enqueue_script( 'organic-widgets-module-color-picker', ORGANIC_WIDGETS_ADMIN_JS_DIR . 'organic-widgets-module-color-picker.js', array( 'jquery', 'media-upload', 'media-views', 'wp-color-picker' ) );
    }

  }

	/**
	 * Enqueue Scripts for Background Images
	 *
	 * @since    	1.0.0
	 */
  public function bg_image_scripts() {

    // Scripts for Image Background Module
		if ( ! wp_script_is( 'organic-widgets-module-image-background', 'enqueued' ) ) {
      wp_enqueue_script( 'organic-widgets-module-image-background', ORGANIC_WIDGETS_ADMIN_JS_DIR . 'organic-widgets-module-image-background.js', array( 'jquery', 'media-upload', 'media-views', 'wp-color-picker' ) );
    }
		if ( ! wp_script_is( 'organic-widgets-module-image-background', 'enqueued' ) ) {
      wp_localize_script( 'organic-widgets-module-image-background', 'OrganicWidgetBG', array(
  			'frame_title' => __( 'Select an Image', ORGANIC_WIDGETS_18N ),
  			'button_title' => __( 'Insert Into Widget', ORGANIC_WIDGETS_18N ),
  		) );
    }

  }

	/**
	 * Echo video scripts to page
	 *
	 * @since    	1.0.0
	 * @param     array    $video_info     Array of video backgrounds
	 */
	protected function add_video_bg( $video_info ) {

		// Global Variable
		global $organic_widgets_video_bgs;

		if ( ! is_array( $organic_widgets_video_bgs ) ) {
			$organic_widgets_video_bgs = array();
		}

		array_push( $organic_widgets_video_bgs, $video_info );

	}

	/**
	 * Echo Video Background HTML
	 *
	 * @since    	1.0.0
	 * @param     array    $widget     Widget array
	 */
	protected function video_bg_html( $widget ) {

		if ( ! is_array( $widget ) || ! count( $widget ) ) {
			return false;
		}

		$widget['video_type'] = $this->get_video_type( $widget['video'] );
		if ( 'youtube' == $widget['video_type'] ) {
			$widget['video_id'] = $this->youtube_id_from_url( $widget['video'] );
			if ( $widget['video_id'] ) { ?>
			<div class="organic-widgets-video-bg-wrapper">
				<div class="organic-widgets-video-bg-container">
					<div class="organic-widgets-video-bg-center">
						<div id="<?php echo $this->sanitize_js_variable($widget['widget_id']); ?>" class="organic-widgets-video-bg"></div>
					</div>
				</div>
				<div class="organic-widgets-video-bg-shade"></div>
			</div>
			<?php }
		}

	}

	/**
	 * Render the section background inputs
	 *
	 * @since    	1.0.0
	 *
	 * @param		array 	$instance 	Widget instance
	 * @param		array 	$instance 	Background options
	 */
  protected function section_background_input_markup( $instance, $bg_options ) {

    $bg_color = array_key_exists( 'bg_color', $instance ) && $instance['bg_color'] ? $instance['bg_color'] : false;
    $bg_image_id = array_key_exists( 'bg_image_id', $instance ) && $instance['bg_image_id'] ? $instance['bg_image_id'] : false;
    $bg_image = array_key_exists( 'bg_image', $instance ) && $instance['bg_image'] ? $instance['bg_image'] : false;
    $bg_video = array_key_exists( 'bg_video', $instance ) && $instance['bg_video'] ? $instance['bg_video'] : false;

    ?>

		<hr />

    <h3><?php _e( 'Section Background Options:', ORGANIC_WIDGETS_18N ); ?></h3>

    <?php if ( array_key_exists( 'color', $bg_options ) && $bg_options['color'] ) { ?>
      <p>
  			<label for="<?php echo $this->get_field_name('bg_color'); ?>"><?php _e( 'Background Color:', ORGANIC_WIDGETS_18N ); ?></label><br>
  			<input type="text" name="<?php echo $this->get_field_name('bg_color'); ?>" id="<?php echo $this->get_field_id( 'bg_color' ); ?>" value="<?php echo esc_attr($bg_color); ?>" class="organic-widgets-color-picker" />
  		</p>
    <?php } ?>

    <?php if ( array_key_exists( 'image', $bg_options ) && $bg_options['image'] ) { ?>
      <p>
  			<label for="<?php echo $this->get_field_id( 'bg_image' ); ?>"><?php _e( 'Background Image:', ORGANIC_WIDGETS_18N ); ?></label>
  			<div class="uploader">
  				<input type="submit" class="button" name="<?php echo $this->get_field_name('uploader_button'); ?>" id="<?php echo $this->get_field_id('uploader_button'); ?>" value="<?php if ( $bg_image_id ) { _e( 'Change Image', ORGANIC_WIDGETS_18N ); }else { _e( 'Select Image', ORGANIC_WIDGETS_18N ); }?>" onclick="organicWidgetBackgroundImage.uploader( '<?php echo $this->id; ?>', '<?php echo $this->id_prefix; ?>' ); return false;" />
  				<input type="submit" class="organic-widgets-remove-image-button button" name="<?php echo $this->get_field_name('remover_button'); ?>" id="<?php echo $this->get_field_id('remover_button'); ?>" value="<?php _e('Remove Image', ORGANIC_WIDGETS_18N); ?>" onclick="organicWidgetBackgroundImage.remover( '<?php echo $this->id; ?>', '<?php echo $this->id_prefix; ?>', 'remover_button' ); return false;" <?php if ( $bg_image_id < 1 ) { echo( 'style="display:none;"' ); } ?>/>
  				<div class="organic-widgets-widget-image-preview" id="<?php echo $this->get_field_id('preview'); ?>">
  					<?php echo $this->get_image_html($instance); ?>
  				</div>
  				<input type="hidden" id="<?php echo $this->get_field_id('bg_image_id'); ?>" name="<?php echo $this->get_field_name('bg_image_id'); ?>" value="<?php echo abs($bg_image_id); ?>" />
  				<input type="hidden" id="<?php echo $this->get_field_id('bg_image'); ?>" name="<?php echo $this->get_field_name('bg_image'); ?>" value="<?php echo $bg_image; ?>" />
  			</div>
  		</p>
    <?php } ?>

    <?php if ( array_key_exists( 'video', $bg_options ) && $bg_options['video'] ) { ?>
      <p>
  			<label for="<?php echo $this->get_field_id( 'bg_video' ); ?>"><?php _e('YouTube Background Video:', ORGANIC_WIDGETS_18N) ?></label>
  			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'bg_video' ); ?>" name="<?php echo $this->get_field_name( 'bg_video' ); ?>" value="<?php echo esc_url($bg_video); ?>" />
  		</p>
    <?php }

  }

	/**
	 * Render the content aligner input
	 *
	 * @since    	1.0.0
	 *
	 * @param array $instance
	 */
	protected function content_aligner_input_markup( $instance ) {

		?>
		<label for="<?php echo $this->get_field_id( 'alignment' ); ?>"><?php _e('Content Alignment', ORGANIC_WIDGETS_18N); ?></label>
		<div class="organic-widgets-content-alignment">
			<table class="organic-widgets-content-alignment-table">
				<tr>
					<td class="organic-widgets-top-left" data-alignment="top-left" data-selected="<?php if ( ! empty( $instance['alignment'] ) && 'top-left' == $instance['alignment'] ) { echo 'true'; } ?>"><i class="fa fa-angle-left organic-widget-fa-rotate-45" aria-hidden="true"></i></td>
					<td class="organic-widgets-top-center" data-alignment="top-center" data-selected="<?php if ( ! empty( $instance['alignment'] ) && 'top-center' == $instance['alignment'] ) { echo 'true'; } ?>"><i class="fa fa-angle-up" aria-hidden="true"></i></td>
					<td class="organic-widgets-top-right" data-alignment="top-right" data-selected="<?php if ( ! empty( $instance['alignment'] ) && 'top-right' == $instance['alignment'] ) { echo 'true'; } ?>"><i class="fa fa-angle-up organic-widget-fa-rotate-45" aria-hidden="true"></i></td>
				</tr>
				<tr>
					<td class="organic-widgets-middle-left" data-alignment="middle-left" data-selected="<?php if ( ! empty( $instance['alignment'] ) && 'middle-left' == $instance['alignment'] ) { echo 'true'; } ?>"><i class="fa fa-angle-left" aria-hidden="true"></i></td>
					<td class="organic-widgets-middle-center" data-alignment="middle-center" data-selected="<?php if ( empty( $instance['alignment'] ) || 'middle-center' == $instance['alignment'] ) { echo 'true'; } ?>"><i class="fa fa-circle-thin" aria-hidden="true"></i></td>
					<td class="organic-widgets-middle-right" data-alignment="middle-right" data-selected="<?php if ( ! empty( $instance['alignment'] ) && 'middle-right' == $instance['alignment'] ) { echo 'true'; } ?>"><i class="fa fa-angle-right" aria-hidden="true"></i></td>
				</tr>
				<tr>
					<td class="organic-widgets-bottom-left" data-alignment="bottom-left" data-selected="<?php if ( ! empty( $instance['alignment'] ) && 'bottom-left' == $instance['alignment'] ) { echo 'true'; } ?>"><i class="fa fa-angle-down organic-widget-fa-rotate-45" aria-hidden="true"></i></td>
					<td class="organic-widgets-bottom-center" data-alignment="bottom-center" data-selected="<?php if ( ! empty( $instance['alignment'] ) && 'bottom-center' == $instance['alignment'] ) { echo 'true'; } ?>"><i class="fa fa-angle-down" aria-hidden="true"></i></td>
					<td class="organic-widgets-bottom-right" data-alignment="bottom-right" data-selected="<?php if ( ! empty( $instance['alignment'] ) && 'bottom-right' == $instance['alignment'] ) { echo 'true'; } ?>"><i class="fa fa-angle-right organic-widget-fa-rotate-45" aria-hidden="true"></i></td>
				</tr>
			</table>
			<input class="widefat" type="hidden" id="<?php echo $this->get_field_id( 'alignment' ); ?>" name="<?php echo $this->get_field_name( 'alignment' ); ?>" value="<?php if ( ! empty( $instance['alignment'] ) ) echo $instance['alignment']; ?>" />
		</div>
		<?php

	}

	/**
	 * Markup for Repeatable Form Items
	 *
	 * @since    	1.0.0
	 *
	 * @param array 	$repeatable_array 	An array of the repeatable form items.
	 * @param string 	$form_item_title 	The display text for the form item.
	 * @param array 	$instance 	Widget instance
	 */
	protected function repeatable_form_item_inputs_markup( $repeatable_array, $form_item_title, $instance = false ) {

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'repeatable_array' ); ?>"><h4><?php _e( $form_item_title, ORGANIC_WIDGETS_18N ) ?></h4></label>

			<?php //Loop through each item and echo form section
			$form_keys = array();
			$form_orders = array();
			if ( is_array( $repeatable_array ) ) {
				usort( $repeatable_array, array( $this, 'sort_by_order' ) );
				foreach ( $repeatable_array as $key => $repeatable ) {

					if ( isset( $repeatable['order'] ) ) {
						$order = $repeatable['order'];
					} else {
						$order = $key;
					}

					// Echo Form Item
					$this->echo_repeatable_form_item( $key, $order, $repeatable, $instance );

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
				$order = 0;
			}

			// Echo Form Item
			$this->echo_repeatable_form_item( $key, $order ); ?>

			<div class="organic-widgets-repeatable-add-item">
				<i class="fa fa-plus"></i>
			</div>

			<input type="hidden" class="organic-widgets-repeatable-hidden-input" id="<?php echo $this->get_field_id('repeatable_array'); ?>" name="<?php echo $this->get_field_name('repeatable_array'); ?>" value='<?php if ( count($repeatable_array) > 0 ){ echo json_encode($repeatable_array); }?>' />

			<?php $this->echo_repeatable_form_item(); ?>

		</p><?php

	}

	/**
	 * Echo the actions to move repeatable items up, down and delete
	 *
	 * @since    	1.0.0
	 *
	 */
	protected function echo_repeatable_form_item_actions() { ?>

		<div class="organic-widgets-repeatable-actions">
			<div class="organic-widgets-repeatable-move-button organic-widgets-move-up">
					<i class="fa fa-angle-up"></i>
			</div>
			<div class="organic-widgets-repeatable-move-button organic-widgets-move-down">
					<i class="fa fa-angle-down"></i>
			</div>
			<div class="organic-widgets-repeatable-delete-button">
				<i class="fa fa-trash"></i>
			</div>
			<div class="organic-widgets-clear"></div>
		</div>

	<?php
	}

  /**
	 * Render the image html output.
	 *
	 * @since    	1.0.0
	 *
	 * @param 	array 	$instance 		Widget instance
	 * @param 	array 	$repeatable 	Repeatable item
	 * @return 	string 	image html
	 */
	protected function get_image_html( $instance, $repeatable = false ) {

		if ( ! $repeatable ) {
			if ( isset( $instance['bg_image_id'] ) ) {
				$image_id = $instance['bg_image_id'];
			} else { $image_id = 0; }
		} else {
			if ( isset( $repeatable['icon'] ) ) {
				$image_id = $repeatable['icon'];
			} else { $image_id = 0; }
		}

		// If there is an bg_image, use it to render the image. Eventually we should kill this and simply rely on bg_image_ids.
		if ( (int) $image_id > 0 ) {
			$size = 'organic-widgets-featured-large';
			$img_array = wp_get_attachment_image( $image_id, 'full', false );
			if ( is_array( $img_array ) ) {
				$output = '<img src="'.$img_array[0].'" />';
			} else {
				$output = $img_array;
			}
		} else {
			$output = '';
		}

		return $output;

	}

	/**
	 * Function for sorting arrays with usort
	 *
	 * @since    	1.0.0
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
	 * Outputs HTML for Selecting fontAwesome Icons
	 *
	 * @since    	1.0.0
	 */
	protected function getIconOptionsDivs() { ?>

		<div class="organic-widgets-feature-select-item" data-val="fa-archive">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class ="fa fa-archive"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-balance-scale">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class ="fa fa-balance-scale"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-university">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class ="fa fa-university"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-bar-chart">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class ="fa fa-bar-chart"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-pie-chart">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class ="fa fa-pie-chart"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-beer">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-beer"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-bell">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-bell"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-bolt">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-bolt"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-book">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-book"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-briefcase">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-briefcase"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-bullhorn">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-bullhorn"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-calendar">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-calendar"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-camera">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-camera"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-camera-retro">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-camera-retro"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-check">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-check"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-cloud">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-cloud"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-coffee">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-coffee"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-cog">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-cog"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-cogs">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-cogs"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-comment">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-comment"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-comments">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-comments"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-credit-card">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-credit-card"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-dashboard">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-dashboard"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-database">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-database"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-desktop">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-desktop"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-diamond">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-diamond"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-download">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-download"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-edit">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-edit"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-envelope">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-envelope"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-exchange">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-exchange"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-film">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-film"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-filter">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-filter"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-fire">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-fire"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-flag">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-flag"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-folder-open">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-folder-open"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-gift">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-gift"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-glass">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-glass"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-globe">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-globe"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-group">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-group"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-headphones">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-headphones"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-heart">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-heart"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-home">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-home"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-inbox">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-inbox"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-key">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-key"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-leaf">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-leaf"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-laptop">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-laptop"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-legal">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-legal"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-lock">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-lock"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-unlock">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-unlock"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-magic">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-magic"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-magnet">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-magnet"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-map-marker">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-map-marker"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-mobile-phone">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-mobile-phone"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-money">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-money"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-music">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-music"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-pencil">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-pencil"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-plane">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-plane"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-plug">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-plug"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-plus">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-plus"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-podcast">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-podcast"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-print">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-print"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-puzzle-piece">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-puzzle-piece"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-qrcode">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-qrcode"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-random">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-random"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-refresh">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-refresh"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-road">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-road"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-rocket">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-rocket"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-rss">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-rss"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-search">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-search"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-server">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-server"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-share-alt">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-share-alt"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-shield">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-shield"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-shopping-cart">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-shopping-cart"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-signal">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-signal"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-sitemap">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-sitemap"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-sort-alpha-desc">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-sort-alpha-desc"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-sort-amount-asc">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-sort-amount-asc"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-star">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-star"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-life-ring">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-life-ring"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-tablet">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-tablet"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-tags">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-tags"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-tasks">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-tasks"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-telegram">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-telegram"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-thumbs-down">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-thumbs-down"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-thumbs-up">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-thumbs-up"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-tint">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-tint"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-trash">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-trash"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-trophy">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-trophy"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-truck">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-truck"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-umbrella">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-umbrella"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-upload">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-upload"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-user">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-user"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-volume-up">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-volume-up"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-exclamation-triangle">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-exclamation-triangle"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-wifi">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-wifi"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-wrench">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-wrench"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-phone">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-phone"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-facebook">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-facebook"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-twitter">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-twitter"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-github">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-github"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-linkedin">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-linkedin"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-pinterest">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-pinterest"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-google-plus">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-google-plus"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-ambulance">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-ambulance"></i>
			</div>
		</div>
		<div class="organic-widgets-feature-select-item" data-val="fa-user-md">
			<div class="organic-widgets-feature-select-item-sizer"></div>
			<div class="organic-widgets-feature-select-item-content">
				<i class="fa fa-user-md"></i>
			</div>
		</div>
		<?php
	}

	/**
	 * Returns an array showing if a groupable array is first, last, and what the id of the first element is
	 *
	 * @since    1.0.0
	 *
	 * @param 	array 	$args 	Widget arguments to determine groupability
	 * @return 	array 	Information about the widget's placement in a group
	 */
	protected function organic_widgets_groupable_widget( $args = false ) {

		$return_array = array(
			'first' => true,
			'last' => true,
			'group_id' => false,
		);

		if ( $args ) {

			// Get widget info and widget area info
			$widget_area_id = $args['id'];
			$widget_name = $args['widget_name'];
			$widget_id = $args['widget_id'];
			$widget_id_base = _get_widget_id_base( $widget_id );

			// Get widget before and check to see if it is same type
			$widget_areas = wp_get_sidebars_widgets();
			$this_widget_area = $widget_areas[$widget_area_id];

			//Evaluate FIRST
			// If widget is first in area, return true
			if ( $this_widget_area[0] == $widget_id ) {
				$return_array['first'] = true;
			} else {
				$this_widget_key = array_search( $widget_id, $this_widget_area );

				// Get previous widget
				$prev_widget_id = $this_widget_area[ $this_widget_key - 1 ];
				$prev_widget_id_base = _get_widget_id_base( $prev_widget_id );

				// If previous widget is of same type, return false
				if ( $prev_widget_id_base == $widget_id_base ) {
					$return_array['first'] = false;
				}
			}

			//Evaluation LAST
			// If widget is last in area, return true
			$length = count($this_widget_area);
			if ( $this_widget_area[ $length -1 ] == $widget_id ) {
				$return_array['last'] = true;
			} else {
				$this_widget_key = array_search( $widget_id, $this_widget_area );

				// Get next widget
				$next_widget_id = $this_widget_area[ $this_widget_key + 1 ];
				$next_widget_id_base = _get_widget_id_base( $next_widget_id );

				// If previous widget is of same type, return false
				if ( $next_widget_id_base == $widget_id_base ) {
					$return_array['last'] = false;
				}
			}

			//Group ID
			if ( $return_array['first'] ) {
				$return_array['group_id'] = $widget_id;
			} else {
				$test_widget_id_base = $widget_id_base;
				$test_widget_key = $this_widget_key;
				while ( $test_widget_id_base == $widget_id_base && $test_widget_key > 0 ) {
					// Get previous widget
					$test_widget_key = $test_widget_key - 1;
					$test_widget_id = $this_widget_area[ $test_widget_key ];
					$test_widget_id_base = _get_widget_id_base( $test_widget_id );
					if ($test_widget_id_base == $widget_id_base) {
						$return_array['group_id'] = $test_widget_id;
					}
				}
			}

			return $return_array;

		} else {

			return $return_array;

		}

	}

	/**
	 * Sanitize Slideshow Transition Interval.
	 *
	 * @param int or string $input Sanitizes user input.
	 * @return array
	 */
	function organic_widgets_sanitize_transition_interval( $input ) {
		$valid = array(
			2000 		=> esc_html__( '2 Seconds', ORGANIC_WIDGETS_18N ),
			4000 		=> esc_html__( '4 Seconds', ORGANIC_WIDGETS_18N ),
			6000 		=> esc_html__( '6 Seconds', ORGANIC_WIDGETS_18N ),
			8000 		=> esc_html__( '8 Seconds', ORGANIC_WIDGETS_18N ),
			10000 	=> esc_html__( '10 Seconds', ORGANIC_WIDGETS_18N ),
			12000 	=> esc_html__( '12 Seconds', ORGANIC_WIDGETS_18N ),
			20000 	=> esc_html__( '20 Seconds', ORGANIC_WIDGETS_18N ),
			30000 	=> esc_html__( '30 Seconds', ORGANIC_WIDGETS_18N ),
			60000 	=> esc_html__( '1 Minute', ORGANIC_WIDGETS_18N ),
			999999999	=> esc_html__( 'Hold Frame', ORGANIC_WIDGETS_18N ),
		);

		if ( array_key_exists( $input, $valid ) ) {
			return $input;
		} else {
			return '';
		}
	}

	/**
	 * Sanitize Slideshow Transition Style.
	 *
	 * @param string $input Sanitizes user input.
	 * @return array
	 */
	function organic_widgets_sanitize_transition_style( $input ) {
		$valid = array(
			'fade' 		=> esc_html__( 'Fade', ORGANIC_WIDGETS_18N ),
			'slide' 	=> esc_html__( 'Slide', ORGANIC_WIDGETS_18N ),
		);

		if ( array_key_exists( $input, $valid ) ) {
			return $input;
		} else {
			return '';
		}
	}

} // class Organic_Widgets_Custom_Widget

<?php
/* Adds functions to WP_Widget class */

// Block direct requests.
if ( !defined('ABSPATH') )
	die( '-1' );

/**
 * Adds Organic_Widgets_Subpage_Section_Widget widget.
 */
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
   * @return    boolean   					True if $video_url is a valid hex code, else False
   */
  protected function check_video_url( $video_url ) {

    error_log('check video url here');
    return true;

  }

  /**
   * Get Source of Video
   *
   * @since    	1.0.0
   * @param     string    $video_url      The video url to check
   * @return    string   					        Video source
   */
  protected function get_video_type( $video_url ) {

    if ( strpos( $video_url, 'youtube' ) > 0 ) {

  		return 'youtube';

  	} elseif ( strpos( $video_url, 'vimeo' ) > 0 ) {

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

  public function bg_image_scripts() {

    // Scripts for Image Background Module
		if ( ! wp_script_is( 'organic-widgets-module-image-background', 'enqueued' ) ) {
      error_log('bg_image_scripts');
      wp_enqueue_script( 'organic-widgets-module-image-background', ORGANIC_WIDGETS_ADMIN_JS_DIR . 'organic-widgets-module-image-background.js', array( 'jquery', 'media-upload', 'media-views', 'wp-color-picker' ) );
    }
		if ( ! wp_script_is( 'organic-widgets-module-image-background', 'enqueued' ) ) {
      wp_localize_script( 'organic-widgets-module-image-background', 'SubpageWidget', array(
  			'frame_title' => __( 'Select an Image', ORGANIC_WIDGETS_18N ),
  			'button_title' => __( 'Insert Into Widget', ORGANIC_WIDGETS_18N ),
  		) );
    }

  }

  public function bg_video_scripts() {

  }

  /**
   * Echo video scripts to page
   *
   * @since    	1.0.0
   * @param     array    $video_array     Array of video backgrounds
   */
  protected function video_bg_script( $video, $widget_id ) {

    $video_type = $this->get_video_type( $video );
    $video_id = $this->youtube_id_from_url( $video );
    $widget_id = $widget_id;
    $clean_widget_id = $this->sanitize_js_variable( $widget_id );

  	// Start outputting javascript to page.
  	echo "
  	<script>

  	//determine if devices is small or is iOS, where autoplay isn't enabled
  	if (typeof iOSOrSmall != 'function') {
  		function iOSOrSmall() {

  			if ( window.innerWidth < 691 ) { return true; }

  			var iDevices = [
  				'iPad Simulator',
  				'iPhone Simulator',
  				'iPod Simulator',
  				'iPad',
  				'iPhone',
  				'iPod'
  			];

  			while (iDevices.length) {
  				if (navigator.platform === iDevices.pop()){ return true; }
  			}

  			return false;
  		}
  	}

  	//if device is not small or ios, load video background from youtube
  	if (!iOSOrSmall()) {
  		// This code loads the IFrame Player API code asynchronously
  		var tag = document.createElement('script');

  		tag.src = 'https://www.youtube.com/iframe_api';
  		var firstScriptTag = document.getElementsByTagName('script')[0];
  		firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

  		// This function creates an <iframe> (and YouTube player)
  		// after the API code downloads.
  		function onYouTubeIframeAPIReady() {
  		";

  		if ( $video_type == 'youtube' && $video_id ) {

  			echo '
  				var player'.$clean_widget_id.';
  				player'.$clean_widget_id." = new YT.Player('".$clean_widget_id."', {
  					height: '1014',
  					width: '1920',
  					videoId: '".esc_html__( $video_id )."',
  					events: {
  						'onReady': onPlayerReady".$clean_widget_id.",
  						'onStateChange': onPlayerStateChange".$clean_widget_id."
  					},
  					playerVars: {
  						'loop':  '1',
  						'modestbranding': '1',
  						'autoplay':  '1',
  						'showinfo':  '0',
  						'controls':  '0',
  						'start': '0',
  						// 'end' : '30',
  						'playlist': '".esc_html__( $video_id )."',
  						'disablekb': '0',
  						'iv_load_policy': 3,
  						'rel': 0
  					}
  				});";
  		} elseif ( $video_type == 'vimeo' ) {
  			// Add vimeo video type.
  		}

  			echo ' } ';

  		if ( $video_type == 'youtube' && $video_id ) {

  			echo "
  			// Mute and start playing video when ready
        function onPlayerReady".$clean_widget_id."(event) {
          console.log('onPlayerReady');
          console.log(event);
          event.target.mute();
  				event.target.playVideo();
  				var width = jQuery('#".$widget_id."').parent().width();
  				var height = jQuery('#".$widget_id."').parent().width() * (3/4);
  				event.target.a.style.width = width + 'px';
  				event.target.a.style.height = height + 'px';
  			}
  			// Fade out overlay image
  			function onPlayerStateChange".$clean_widget_id."(event) {
  				if (event.data == YT.PlayerState.PLAYING) {
  						setTimeout( function(){
  							var width = jQuery('#".$widget_id."').parent().width();
  							var height = jQuery('#".$widget_id."').parent().width() * (3/4);
  							event.target.a.style.width = width + 'px';
  							event.target.a.style.height = height + 'px';
  							jQuery('.organic-widgets-video-bg-wrapper').find('iframe').fadeTo('slow', 1);
  							jQuery('.organic-widgets-video-bg-wrapper').find('video').fadeTo('slow', 1);
  						}
  					, 100);
  				}
  			}
  			";
  		} // Endif.


  			echo '} </script>';

  }

  protected function echo_color_picker_js( $color_picker_id = false ) {

    $color_picker_id = $color_picker_id ? $color_picker_id : "'.organic-widgets-color-picker'";

    ?>
    <!-- Update customizer with color changes.  -->
		<script type='text/javascript'>
		/**
		 * JS for Initializing Color Pickers
		 */

     ( function( $ ){
				function initColorPicker( widget ) {
					widget.find( '.organic-widgets-color-picker' ).wpColorPicker( {
						change: _.throttle( function() { // For Customizer
							$(this).trigger( 'change' );
						}, 200 )
					});
				}

				function onFormUpdate( event, widget ) {
					initColorPicker( widget );
				}

				$( document ).on( 'widget-added widget-updated', onFormUpdate );

				$( document ).ready( function() {
					$( '#widgets-right .widget:has(.organic-widgets-color-picker)' ).each( function () {
						initColorPicker( $( this ) );
					} );
				} );
			}( jQuery ) );

		</script><?php

  }

  protected function section_background_input_markup( $instance, $bg_options ) {



    $bg_color = array_key_exists( 'bg_color', $instance ) && $instance['bg_color'] ? $instance['bg_color'] : false;
    $bg_image_id = array_key_exists( 'bg_image_id', $instance ) && $instance['bg_image_id'] ? $instance['bg_image_id'] : false;
    $bg_image = array_key_exists( 'bg_image', $instance ) && $instance['bg_image'] ? $instance['bg_image'] : false;
    $bg_video = array_key_exists( 'bg_video', $instance ) && $instance['bg_video'] ? $instance['bg_video'] : false;

    ?>

    <h4>Section Background</h4>

    <?php if ( array_key_exists( 'color', $bg_options ) && $bg_options['color'] ) { ?>
      <p>
  			<label for="<?php echo $this->get_field_name('bg_color'); ?>"><?php _e( 'Background Color:', ORGANIC_WIDGETS_18N ) ?></label><br>
  			<input type="text" name="<?php echo $this->get_field_name('bg_color'); ?>" id="<?php echo $this->get_field_id( 'bg_color' ); ?>" value="<?php echo esc_attr($bg_color); ?>" class="organic-widgets-color-picker" />
  		</p>
    <?php } ?>

    <?php if ( array_key_exists( 'image', $bg_options ) && $bg_options['image'] ) { ?>
      <p>
  			<label for="<?php echo $this->get_field_id( 'bg_image' ); ?>"><?php _e( 'Background Image:', ORGANIC_WIDGETS_18N ) ?></label>
  			<div class="uploader">
  				<input type="submit" class="button" name="<?php echo $this->get_field_name('uploader_button'); ?>" id="<?php echo $this->get_field_id('uploader_button'); ?>" value="<?php if ( $bg_image_id ) { _e( 'Change Image', ORGANIC_WIDGETS_18N ); }else { _e( 'Select Image', ORGANIC_WIDGETS_18N ); }?>" onclick="subpageWidgetImage.uploader( '<?php echo $this->id; ?>', '<?php echo $this->id_prefix; ?>' ); return false;" />
  				<input type="submit" class="organic-widgets-remove-image-button button" name="<?php echo $this->get_field_name('remover_button'); ?>" id="<?php echo $this->get_field_id('remover_button'); ?>" value="<?php _e('Remove Image', ORGANIC_WIDGETS_18N); ?>" onclick="subpageWidgetImage.remover( '<?php echo $this->id; ?>', '<?php echo $this->id_prefix; ?>', 'remover_button' ); return false;" <?php if ( $bg_image_id < 1 ) { echo( 'style="display:none;"' ); } ?>/>
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
  			<label for="<?php echo $this->get_field_id( 'bg_video' ); ?>"><?php _e('Background Video:', ORGANIC_WIDGETS_18N) ?></label>
  			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'bg_video' ); ?>" name="<?php echo $this->get_field_name( 'bg_video' ); ?>" value="<?php echo esc_url($bg_video); ?>" />
  		</p>
    <?php }

  }

  /**
	 * Render the image html output.
	 *
	 * @param array $instance
	 * @param bool $include_link will only render the link if this is set to true. Otherwise link is ignored.
	 * @return string image html
	 */
	protected function get_image_HTML( $instance ) {

		if ( isset( $instance['bg_image_id'] ) ) {
			$bg_image_id = $instance['bg_image_id'];
		} else { $bg_image_id = 0; }

		$output = '';

		$size = 'organic-widgets-featured-large';

		$attr = array();
		$attr = apply_filters( 'image_widget_image_attributes', $attr, $instance );

		$img_array = wp_get_attachment_image( $bg_image_id, $size, false, $attr );

		// If there is an bg_image, use it to render the image. Eventually we should kill this and simply rely on bg_image_ids.
		if ( ! empty( $instance['bg_image'] ) ) {
			// If all we have is an image src url we can still render an image.
			$attr['src'] = $instance['bg_image'];
			$attr = array_map( 'esc_attr', $attr );
			$output .= "<img ";
			foreach ( $attr as $name => $value ) {
				$output .= sprintf( ' %s="%s"', $name, $value );
			}
			$output .= ' />';
		} elseif( abs( $bg_image_id ) > 0 ) {
			$output .= $img_array[0];
		}

		return $output;
	}

	function getIconOptions() { ?>

		<optgroup label="Feature Icons">
		<option value="fa-area-chart">Area Chart</option>
		<option value="fa-bar-chart">Bar Chart</option>
		<option value="fa-pie-chart">Pie Chart</option>
		<option value="icon-beaker">icon-beaker</option>
		<option value="icon-beer">icon-beer</option>
		<option value="icon-bell">icon-bell</option>
		<option value="icon-bell-alt">icon-bell-alt</option>
		<option value="icon-bolt">icon-bolt</option>
		<option value="icon-book">icon-book</option>
		<option value="icon-briefcase">icon-briefcase</option>
		<option value="icon-bullhorn">icon-bullhorn</option>
		<option value="icon-calendar">icon-calendar</option>
		<option value="icon-camera">icon-camera</option>
		<option value="icon-camera-retro">icon-camera-retro</option>
		<option value="icon-check">icon-check</option>
		<option value="icon-cloud">icon-cloud</option>
		<option value="icon-coffee">icon-coffee</option>
		<option value="icon-cog">icon-cog</option>
		<option value="icon-cogs">icon-cogs</option>
		<option value="icon-comment">icon-comment</option>
		<option value="icon-comment-alt">icon-comment-alt</option>
		<option value="icon-comments">icon-comments</option>
		<option value="icon-comments-alt">icon-comments-alt</option>
		<option value="icon-credit-card">icon-credit-card</option>
		<option value="icon-dashboard">icon-dashboard</option>
		<option value="icon-desktop">icon-desktop</option>
		<option value="icon-download">icon-download</option>
		<option value="icon-download-alt">icon-download-alt</option>
		<option value="icon-edit">icon-edit</option>
		<option value="icon-envelope">icon-envelope</option>
		<option value="icon-envelope-alt">icon-envelope-alt</option>
		<option value="icon-exchange">icon-exchange</option>
		<option value="icon-exclamation-sign">icon-exclamation-sign</option>
		<option value="icon-eye-open">icon-eye-open</option>
		<option value="icon-film">icon-film</option>
		<option value="icon-filter">icon-filter</option>
		<option value="icon-fire">icon-fire</option>
		<option value="icon-flag">icon-flag</option>
		<option value="icon-folder-open">icon-folder-open</option>
		<option value="icon-folder-open-alt">icon-folder-open-alt</option>
		<option value="icon-food">icon-food</option>
		<option value="icon-gift">icon-gift</option>
		<option value="icon-glass">icon-glass</option>
		<option value="icon-globe">icon-globe</option>
		<option value="icon-group">icon-group</option>
		<option value="icon-hdd">icon-hdd</option>
		<option value="icon-headphones">icon-headphones</option>
		<option value="icon-heart">icon-heart</option>
		<option value="icon-heart-empty">icon-heart-empty</option>
		<option value="icon-home">icon-home</option>
		<option value="icon-inbox">icon-inbox</option>
		<option value="icon-info-sign">icon-info-sign</option>
		<option value="icon-key">icon-key</option>
		<option value="icon-leaf">icon-leaf</option>
		<option value="icon-laptop">icon-laptop</option>
		<option value="icon-legal">icon-legal</option>
		<option value="icon-lemon">icon-lemon</option>
		<option value="icon-lightbulb">icon-lightbulb</option>
		<option value="icon-lock">icon-lock</option>
		<option value="icon-unlock">icon-unlock</option>
		<option value="icon-magic">icon-magic</option>
		<option value="icon-magnet">icon-magnet</option>
		<option value="icon-map-marker">icon-map-marker</option>
		<option value="icon-minus">icon-minus</option>
		<option value="icon-minus-sign">icon-minus-sign</option>
		<option value="icon-mobile-phone">icon-mobile-phone</option>
		<option value="icon-money">icon-money</option>
		<option value="icon-move">icon-move</option>
		<option value="icon-music">icon-music</option>
		<option value="icon-off">icon-off</option>
		<option value="icon-ok">icon-ok</option>
		<option value="icon-ok-circle">icon-ok-circle</option>
		<option value="icon-ok-sign">icon-ok-sign</option>
		<option value="icon-pencil">icon-pencil</option>
		<option value="icon-picture">icon-picture</option>
		<option value="icon-plane">icon-plane</option>
		<option value="icon-plus">icon-plus</option>
		<option value="icon-plus-sign">icon-plus-sign</option>
		<option value="icon-print">icon-print</option>
		<option value="icon-pushpin">icon-pushpin</option>
		<option value="icon-qrcode">icon-qrcode</option>
		<option value="icon-question-sign">icon-question-sign</option>
		<option value="icon-quote-left">icon-quote-left</option>
		<option value="icon-quote-right">icon-quote-right</option>
		<option value="icon-random">icon-random</option>
		<option value="icon-refresh">icon-refresh</option>
		<option value="icon-road">icon-road</option>
		<option value="icon-rss">icon-rss</option>
		<option value="icon-screenshot">icon-screenshot</option>
		<option value="icon-search">icon-search</option>
		<option value="icon-share-alt">icon-share-alt</option>
		<option value="icon-shopping-cart">icon-shopping-cart</option>
		<option value="icon-signal">icon-signal</option>
		<option value="icon-sitemap">icon-sitemap</option>
		<option value="icon-star">icon-star</option>
		<option value="icon-star-empty">icon-star-empty</option>
		<option value="icon-star-half">icon-star-half</option>
		<option value="icon-tablet">icon-tablet</option>
		<option value="icon-tags">icon-tags</option>
		<option value="icon-tasks">icon-tasks</option>
		<option value="icon-thumbs-down">icon-thumbs-down</option>
		<option value="icon-thumbs-up">icon-thumbs-up</option>
		<option value="icon-time">icon-time</option>
		<option value="icon-tint">icon-tint</option>
		<option value="icon-trash">icon-trash</option>
		<option value="icon-trophy">icon-trophy</option>
		<option value="icon-truck">icon-truck</option>
		<option value="icon-umbrella">icon-umbrella</option>
		<option value="icon-upload">icon-upload</option>
		<option value="icon-user">icon-user</option>
		<option value="icon-user-md">icon-user-md</option>
		<option value="icon-volume-up">icon-volume-up</option>
		<option value="icon-warning-sign">icon-warning-sign</option>
		<option value="icon-wrench">icon-wrench</option>
		<option value="icon-zoom-in">icon-zoom-in</option>
		<option value="icon-zoom-out">icon-zoom-out</option>
	<optgroup label="Social Icons">
		<option value="icon-phone">icon-phone</option>
		<option value="icon-phone-sign">icon-phone-sign</option>
		<option value="icon-facebook">icon-facebook</option>
		<option value="icon-facebook-sign">icon-facebook-sign</option>
		<option value="icon-twitter">icon-twitter</option>
		<option value="icon-twitter-sign">icon-twitter-sign</option>
		<option value="icon-github">icon-github</option>
		<option value="icon-github-alt">icon-github-alt</option>
		<option value="icon-github-sign">icon-github-sign</option>
		<option value="icon-linkedin">icon-linkedin</option>
		<option value="icon-linkedin-sign">icon-linkedin-sign</option>
		<option value="icon-pinterest">icon-pinterest</option>
		<option value="icon-pinterest-sign">icon-pinterest-sign</option>
		<option value="icon-google-plus">icon-google-plus</option>
		<option value="icon-google-plus-sign">icon-google-plus-sign</option>
		<option value="icon-sign-blank">icon-sign-blank</option>
	<optgroup label="Medical Icons">
		<option value="icon-ambulance">icon-ambulance</option>
		<option value="icon-beaker">icon-beaker</option>
		<option value="icon-h-sign">icon-h-sign</option>
		<option value="icon-hospital">icon-hospital</option>
		<option value="icon-medkit">icon-medkit</option>
		<option value="icon-plus-sign-alt">icon-plus-sign-alt</option>
		<option value="icon-stethoscope">icon-stethoscope</option>
		<option value="icon-user-md">icon-user-md</option>

		<?php
	}

} // class Organic_Widgets_Subpage_Section_Widget

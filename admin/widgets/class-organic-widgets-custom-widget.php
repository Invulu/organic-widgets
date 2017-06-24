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
		<option value="fa-area-chart">Area Chart &#xf080;</option>
		<option value="fa-bar-chart">Bar Chart</option>
		<option value="fa-pie-chart">Pie Chart</option>
		<option value="fa-beaker">fa-beaker</option>
		<option value="fa-beer">fa-beer</option>
		<option value="fa-bell">fa-bell</option>
		<option value="fa-bell-alt">fa-bell-alt</option>
		<option value="fa-bolt">fa-bolt</option>
		<option value="fa-book">fa-book</option>
		<option value="fa-briefcase">fa-briefcase</option>
		<option value="fa-bullhorn">fa-bullhorn</option>
		<option value="fa-calendar">fa-calendar</option>
		<option value="fa-camera">fa-camera</option>
		<option value="fa-camera-retro">fa-camera-retro</option>
		<option value="fa-check">fa-check</option>
		<option value="fa-cloud">fa-cloud</option>
		<option value="fa-coffee">fa-coffee</option>
		<option value="fa-cog">fa-cog</option>
		<option value="fa-cogs">fa-cogs</option>
		<option value="fa-comment">fa-comment</option>
		<option value="fa-comment-alt">fa-comment-alt</option>
		<option value="fa-comments">fa-comments</option>
		<option value="fa-comments-alt">fa-comments-alt</option>
		<option value="fa-credit-card">fa-credit-card</option>
		<option value="fa-dashboard">fa-dashboard</option>
		<option value="fa-desktop">fa-desktop</option>
		<option value="fa-download">fa-download</option>
		<option value="fa-download-alt">fa-download-alt</option>
		<option value="fa-edit">fa-edit</option>
		<option value="fa-envelope">fa-envelope</option>
		<option value="fa-envelope-alt">fa-envelope-alt</option>
		<option value="fa-exchange">fa-exchange</option>
		<option value="fa-exclamation-sign">fa-exclamation-sign</option>
		<option value="fa-eye-open">fa-eye-open</option>
		<option value="fa-film">fa-film</option>
		<option value="fa-filter">fa-filter</option>
		<option value="fa-fire">fa-fire</option>
		<option value="fa-flag">fa-flag</option>
		<option value="fa-folder-open">fa-folder-open</option>
		<option value="fa-folder-open-alt">fa-folder-open-alt</option>
		<option value="fa-food">fa-food</option>
		<option value="fa-gift">fa-gift</option>
		<option value="fa-glass">fa-glass</option>
		<option value="fa-globe">fa-globe</option>
		<option value="fa-group">fa-group</option>
		<option value="fa-hdd">fa-hdd</option>
		<option value="fa-headphones">fa-headphones</option>
		<option value="fa-heart">fa-heart</option>
		<option value="fa-heart-empty">fa-heart-empty</option>
		<option value="fa-home">fa-home</option>
		<option value="fa-inbox">fa-inbox</option>
		<option value="fa-info-sign">fa-info-sign</option>
		<option value="fa-key">fa-key</option>
		<option value="fa-leaf">fa-leaf</option>
		<option value="fa-laptop">fa-laptop</option>
		<option value="fa-legal">fa-legal</option>
		<option value="fa-lemon">fa-lemon</option>
		<option value="fa-lightbulb">fa-lightbulb</option>
		<option value="fa-lock">fa-lock</option>
		<option value="fa-unlock">fa-unlock</option>
		<option value="fa-magic">fa-magic</option>
		<option value="fa-magnet">fa-magnet</option>
		<option value="fa-map-marker">fa-map-marker</option>
		<option value="fa-minus">fa-minus</option>
		<option value="fa-minus-sign">fa-minus-sign</option>
		<option value="fa-mobile-phone">fa-mobile-phone</option>
		<option value="fa-money">fa-money</option>
		<option value="fa-move">fa-move</option>
		<option value="fa-music">fa-music</option>
		<option value="fa-off">fa-off</option>
		<option value="fa-ok">fa-ok</option>
		<option value="fa-ok-circle">fa-ok-circle</option>
		<option value="fa-ok-sign">fa-ok-sign</option>
		<option value="fa-pencil">fa-pencil</option>
		<option value="fa-picture">fa-picture</option>
		<option value="fa-plane">fa-plane</option>
		<option value="fa-plus">fa-plus</option>
		<option value="fa-plus-sign">fa-plus-sign</option>
		<option value="fa-print">fa-print</option>
		<option value="fa-pushpin">fa-pushpin</option>
		<option value="fa-qrcode">fa-qrcode</option>
		<option value="fa-question-sign">fa-question-sign</option>
		<option value="fa-quote-left">fa-quote-left</option>
		<option value="fa-quote-right">fa-quote-right</option>
		<option value="fa-random">fa-random</option>
		<option value="fa-refresh">fa-refresh</option>
		<option value="fa-road">fa-road</option>
		<option value="fa-rss">fa-rss</option>
		<option value="fa-screenshot">fa-screenshot</option>
		<option value="fa-search">fa-search</option>
		<option value="fa-share-alt">fa-share-alt</option>
		<option value="fa-shopping-cart">fa-shopping-cart</option>
		<option value="fa-signal">fa-signal</option>
		<option value="fa-sitemap">fa-sitemap</option>
		<option value="fa-star">fa-star</option>
		<option value="fa-star-empty">fa-star-empty</option>
		<option value="fa-star-half">fa-star-half</option>
		<option value="fa-tablet">fa-tablet</option>
		<option value="fa-tags">fa-tags</option>
		<option value="fa-tasks">fa-tasks</option>
		<option value="fa-thumbs-down">fa-thumbs-down</option>
		<option value="fa-thumbs-up">fa-thumbs-up</option>
		<option value="fa-time">fa-time</option>
		<option value="fa-tint">fa-tint</option>
		<option value="fa-trash">fa-trash</option>
		<option value="fa-trophy">fa-trophy</option>
		<option value="fa-truck">fa-truck</option>
		<option value="fa-umbrella">fa-umbrella</option>
		<option value="fa-upload">fa-upload</option>
		<option value="fa-user">fa-user</option>
		<option value="fa-user-md">fa-user-md</option>
		<option value="fa-volume-up">fa-volume-up</option>
		<option value="fa-warning-sign">fa-warning-sign</option>
		<option value="fa-wrench">fa-wrench</option>
		<option value="fa-zoom-in">fa-zoom-in</option>
		<option value="fa-zoom-out">fa-zoom-out</option>
	<optgroup label="Social Icons">
		<option value="fa-phone">fa-phone</option>
		<option value="fa-phone-sign">fa-phone-sign</option>
		<option value="fa-facebook">fa-facebook</option>
		<option value="fa-facebook-sign">fa-facebook-sign</option>
		<option value="fa-twitter">fa-twitter</option>
		<option value="fa-twitter-sign">fa-twitter-sign</option>
		<option value="fa-github">fa-github</option>
		<option value="fa-github-alt">fa-github-alt</option>
		<option value="fa-github-sign">fa-github-sign</option>
		<option value="fa-linkedin">fa-linkedin</option>
		<option value="fa-linkedin-sign">fa-linkedin-sign</option>
		<option value="fa-pinterest">fa-pinterest</option>
		<option value="fa-pinterest-sign">fa-pinterest-sign</option>
		<option value="fa-google-plus">fa-google-plus</option>
		<option value="fa-google-plus-sign">fa-google-plus-sign</option>
		<option value="fa-sign-blank">fa-sign-blank</option>
	<optgroup label="Medical Icons">
		<option value="fa-ambulance">fa-ambulance</option>
		<option value="fa-beaker">fa-beaker</option>
		<option value="fa-h-sign">fa-h-sign</option>
		<option value="fa-hospital">fa-hospital</option>
		<option value="fa-medkit">fa-medkit</option>
		<option value="fa-plus-sign-alt">fa-plus-sign-alt</option>
		<option value="fa-stethoscope">fa-stethoscope</option>
		<option value="fa-user-md">fa-user-md</option>

		<?php
	}

	function getIconOptionsDivs() { ?>

		<div class="organic-widgets-feature-select-item" value="fa-area-chart"><i class="fa fa-area-chart"></i> Area Chart</div>
		<div class="organic-widgets-feature-select-item" value="fa-bar-chart"><i class ="fa fa-bar-chart"></i> Bar Chart</div>
		<div class="organic-widgets-feature-select-item" value="fa-pie-chart">Pie Chart</div>
		<div class="organic-widgets-feature-select-item" value="fa-beaker"><i class="fa fa-beaker"></i> name</div>
		<div class="organic-widgets-feature-select-item" value="fa-beer"><i class="fa fa-beer"></i> name</div>
		<div class="organic-widgets-feature-select-item" value="fa-bell"><i class="fa fa-bell"></i> name</div>
		<div class="organic-widgets-feature-select-item" value="fa-bell-alt">fa-bell-alt</div>
		<div class="organic-widgets-feature-select-item" value="fa-bolt"><i class="fa fa-bolt"></i> name</div>
		<div class="organic-widgets-feature-select-item" value="fa-book"><i class="fa fa-book"></i> name</div>
		<div class="organic-widgets-feature-select-item" value="fa-briefcase"><i class="fa fa-briefcase"></i> name</div>
		<div class="organic-widgets-feature-select-item" value="fa-bullhorn"><i class="fa fa-bullhorn"></i> name</div>
		<div class="organic-widgets-feature-select-item" value="fa-calendar"><i class="fa fa-calendar"></i> name</div>
		<div class="organic-widgets-feature-select-item" value="fa-camera"><i class="fa fa-camera"></i> name</div>
		<div class="organic-widgets-feature-select-item" value="fa-camera-retro">fa-camera-retro</div>
		<div class="organic-widgets-feature-select-item" value="fa-check"><i class="fa fa-check"></i> name</div>
		<div class="organic-widgets-feature-select-item" value="fa-cloud"><i class="fa fa-cloud"></i> name</div>
		<div class="organic-widgets-feature-select-item" value="fa-coffee"><i class="fa fa-coffee"></i> name</div>
		<div class="organic-widgets-feature-select-item" value="fa-cog"><i class="fa fa-cog"></i> name</div>
		<div class="organic-widgets-feature-select-item" value="fa-cogs"><i class="fa fa-cogs"></i> name</div>
		<div class="organic-widgets-feature-select-item" value="fa-comment"><i class="fa fa-comment"></i> name</div>
		<div class="organic-widgets-feature-select-item" value="fa-comment-alt">fa-comment-alt</div>
		<div class="organic-widgets-feature-select-item" value="fa-comments"><i class="fa fa-comments"></i> name</div>
		<div class="organic-widgets-feature-select-item" value="fa-comments-alt">fa-comments-alt</div>
		<div class="organic-widgets-feature-select-item" value="fa-credit-card">fa-credit-card</div>
		<div class="organic-widgets-feature-select-item" value="fa-dashboard"><i class="fa fa-dashboard"></i> name</div>
		<div class="organic-widgets-feature-select-item" value="fa-desktop"><i class="fa fa-desktop"></i> name</div>
		<div class="organic-widgets-feature-select-item" value="fa-download"><i class="fa fa-download"></i> name</div>
		<div class="organic-widgets-feature-select-item" value="fa-download-alt">fa-download-alt</div>
		<div class="organic-widgets-feature-select-item" value="fa-edit"><i class="fa fa-edit"></i> name</div>
		<div class="organic-widgets-feature-select-item" value="fa-envelope">fa-envelope</div>
		<div class="organic-widgets-feature-select-item" value="fa-envelope-alt">fa-envelope-alt</div>
		<div class="organic-widgets-feature-select-item" value="fa-exchange">fa-exchange</div>
		<div class="organic-widgets-feature-select-item" value="fa-exclamation-sign">fa-exclamation-sign</div>
		<div class="organic-widgets-feature-select-item" value="fa-eye-open">fa-eye-open</div>
		<div class="organic-widgets-feature-select-item" value="fa-film">fa-film</div>
		<div class="organic-widgets-feature-select-item" value="fa-filter">fa-filter</div>
		<div class="organic-widgets-feature-select-item" value="fa-fire">fa-fire</div>
		<div class="organic-widgets-feature-select-item" value="fa-flag">fa-flag</div>
		<div class="organic-widgets-feature-select-item" value="fa-folder-open">fa-folder-open</div>
		<div class="organic-widgets-feature-select-item" value="fa-folder-open-alt">fa-folder-open-alt</div>
		<div class="organic-widgets-feature-select-item" value="fa-food">fa-food</div>
		<div class="organic-widgets-feature-select-item" value="fa-gift">fa-gift</div>
		<div class="organic-widgets-feature-select-item" value="fa-glass">fa-glass</div>
		<div class="organic-widgets-feature-select-item" value="fa-globe">fa-globe</div>
		<div class="organic-widgets-feature-select-item" value="fa-group">fa-group</div>
		<div class="organic-widgets-feature-select-item" value="fa-hdd">fa-hdd</div>
		<div class="organic-widgets-feature-select-item" value="fa-headphones">fa-headphones</div>
		<div class="organic-widgets-feature-select-item" value="fa-heart">fa-heart</div>
		<div class="organic-widgets-feature-select-item" value="fa-heart-empty">fa-heart-empty</div>
		<div class="organic-widgets-feature-select-item" value="fa-home">fa-home</div>
		<div class="organic-widgets-feature-select-item" value="fa-inbox">fa-inbox</div>
		<div class="organic-widgets-feature-select-item" value="fa-info-sign">fa-info-sign</div>
		<div class="organic-widgets-feature-select-item" value="fa-key">fa-key</div>
		<div class="organic-widgets-feature-select-item" value="fa-leaf">fa-leaf</div>
		<div class="organic-widgets-feature-select-item" value="fa-laptop">fa-laptop</div>
		<div class="organic-widgets-feature-select-item" value="fa-legal">fa-legal</div>
		<div class="organic-widgets-feature-select-item" value="fa-lemon">fa-lemon</div>
		<div class="organic-widgets-feature-select-item" value="fa-lightbulb">fa-lightbulb</div>
		<div class="organic-widgets-feature-select-item" value="fa-lock">fa-lock</div>
		<div class="organic-widgets-feature-select-item" value="fa-unlock">fa-unlock</div>
		<div class="organic-widgets-feature-select-item" value="fa-magic">fa-magic</div>
		<div class="organic-widgets-feature-select-item" value="fa-magnet">fa-magnet</div>
		<div class="organic-widgets-feature-select-item" value="fa-map-marker">fa-map-marker</div>
		<div class="organic-widgets-feature-select-item" value="fa-minus">fa-minus</div>
		<div class="organic-widgets-feature-select-item" value="fa-minus-sign">fa-minus-sign</div>
		<div class="organic-widgets-feature-select-item" value="fa-mobile-phone">fa-mobile-phone</div>
		<div class="organic-widgets-feature-select-item" value="fa-money">fa-money</div>
		<div class="organic-widgets-feature-select-item" value="fa-move">fa-move</div>
		<div class="organic-widgets-feature-select-item" value="fa-music">fa-music</div>
		<div class="organic-widgets-feature-select-item" value="fa-off">fa-off</div>
		<div class="organic-widgets-feature-select-item" value="fa-ok">fa-ok</div>
		<div class="organic-widgets-feature-select-item" value="fa-ok-circle">fa-ok-circle</div>
		<div class="organic-widgets-feature-select-item" value="fa-ok-sign">fa-ok-sign</div>
		<div class="organic-widgets-feature-select-item" value="fa-pencil">fa-pencil</div>
		<div class="organic-widgets-feature-select-item" value="fa-picture">fa-picture</div>
		<div class="organic-widgets-feature-select-item" value="fa-plane">fa-plane</div>
		<div class="organic-widgets-feature-select-item" value="fa-plus">fa-plus</div>
		<div class="organic-widgets-feature-select-item" value="fa-plus-sign">fa-plus-sign</div>
		<div class="organic-widgets-feature-select-item" value="fa-print">fa-print</div>
		<div class="organic-widgets-feature-select-item" value="fa-pushpin">fa-pushpin</div>
		<div class="organic-widgets-feature-select-item" value="fa-qrcode">fa-qrcode</div>
		<div class="organic-widgets-feature-select-item" value="fa-question-sign">fa-question-sign</div>
		<div class="organic-widgets-feature-select-item" value="fa-quote-left">fa-quote-left</div>
		<div class="organic-widgets-feature-select-item" value="fa-quote-right">fa-quote-right</div>
		<div class="organic-widgets-feature-select-item" value="fa-random">fa-random</div>
		<div class="organic-widgets-feature-select-item" value="fa-refresh">fa-refresh</div>
		<div class="organic-widgets-feature-select-item" value="fa-road">fa-road</div>
		<div class="organic-widgets-feature-select-item" value="fa-rss">fa-rss</div>
		<div class="organic-widgets-feature-select-item" value="fa-screenshot">fa-screenshot</div>
		<div class="organic-widgets-feature-select-item" value="fa-search">fa-search</div>
		<div class="organic-widgets-feature-select-item" value="fa-share-alt">fa-share-alt</div>
		<div class="organic-widgets-feature-select-item" value="fa-shopping-cart">fa-shopping-cart</div>
		<div class="organic-widgets-feature-select-item" value="fa-signal">fa-signal</div>
		<div class="organic-widgets-feature-select-item" value="fa-sitemap">fa-sitemap</div>
		<div class="organic-widgets-feature-select-item" value="fa-star">fa-star</div>
		<div class="organic-widgets-feature-select-item" value="fa-star-empty">fa-star-empty</div>
		<div class="organic-widgets-feature-select-item" value="fa-star-half">fa-star-half</div>
		<div class="organic-widgets-feature-select-item" value="fa-tablet">fa-tablet</div>
		<div class="organic-widgets-feature-select-item" value="fa-tags">fa-tags</div>
		<div class="organic-widgets-feature-select-item" value="fa-tasks">fa-tasks</div>
		<div class="organic-widgets-feature-select-item" value="fa-thumbs-down">fa-thumbs-down</div>
		<div class="organic-widgets-feature-select-item" value="fa-thumbs-up">fa-thumbs-up</div>
		<div class="organic-widgets-feature-select-item" value="fa-time">fa-time</div>
		<div class="organic-widgets-feature-select-item" value="fa-tint">fa-tint</div>
		<div class="organic-widgets-feature-select-item" value="fa-trash">fa-trash</div>
		<div class="organic-widgets-feature-select-item" value="fa-trophy">fa-trophy</div>
		<div class="organic-widgets-feature-select-item" value="fa-truck">fa-truck</div>
		<div class="organic-widgets-feature-select-item" value="fa-umbrella">fa-umbrella</div>
		<div class="organic-widgets-feature-select-item" value="fa-upload">fa-upload</div>
		<div class="organic-widgets-feature-select-item" value="fa-user">fa-user</div>
		<div class="organic-widgets-feature-select-item" value="fa-user-md">fa-user-md</div>
		<div class="organic-widgets-feature-select-item" value="fa-volume-up">fa-volume-up</div>
		<div class="organic-widgets-feature-select-item" value="fa-warning-sign">fa-warning-sign</div>
		<div class="organic-widgets-feature-select-item" value="fa-wrench">fa-wrench</div>
		<div class="organic-widgets-feature-select-item" value="fa-zoom-in">fa-zoom-in</div>
		<div class="organic-widgets-feature-select-item" value="fa-zoom-out">fa-zoom-out</div>
		<div class="organic-widgets-feature-select-item" value="fa-phone">fa-phone</div>
		<div class="organic-widgets-feature-select-item" value="fa-phone-sign">fa-phone-sign</div>
		<div class="organic-widgets-feature-select-item" value="fa-facebook">fa-facebook</div>
		<div class="organic-widgets-feature-select-item" value="fa-facebook-sign">fa-facebook-sign</div>
		<div class="organic-widgets-feature-select-item" value="fa-twitter">fa-twitter</div>
		<div class="organic-widgets-feature-select-item" value="fa-twitter-sign">fa-twitter-sign</div>
		<div class="organic-widgets-feature-select-item" value="fa-github">fa-github</div>
		<div class="organic-widgets-feature-select-item" value="fa-github-alt">fa-github-alt</div>
		<div class="organic-widgets-feature-select-item" value="fa-github-sign">fa-github-sign</div>
		<div class="organic-widgets-feature-select-item" value="fa-linkedin">fa-linkedin</div>
		<div class="organic-widgets-feature-select-item" value="fa-linkedin-sign">fa-linkedin-sign</div>
		<div class="organic-widgets-feature-select-item" value="fa-pinterest">fa-pinterest</div>
		<div class="organic-widgets-feature-select-item" value="fa-pinterest-sign">fa-pinterest-sign</div>
		<div class="organic-widgets-feature-select-item" value="fa-google-plus">fa-google-plus</div>
		<div class="organic-widgets-feature-select-item" value="fa-google-plus-sign">fa-google-plus-sign</div>
		<div class="organic-widgets-feature-select-item" value="fa-sign-blank">fa-sign-blank</div>
		<div class="organic-widgets-feature-select-item" value="fa-ambulance">fa-ambulance</div>
		<div class="organic-widgets-feature-select-item" value="fa-beaker">fa-beaker</div>
		<div class="organic-widgets-feature-select-item" value="fa-h-sign">fa-h-sign</div>
		<div class="organic-widgets-feature-select-item" value="fa-hospital">fa-hospital</div>
		<div class="organic-widgets-feature-select-item" value="fa-medkit">fa-medkit</div>
		<div class="organic-widgets-feature-select-item" value="fa-plus-sign-alt">fa-plus-sign-alt</div>
		<div class="organic-widgets-feature-select-item" value="fa-stethoscope">fa-stethoscope</div>
		<div class="organic-widgets-feature-select-item" value="fa-user-md">fa-user-md</div>

		<?php
	}

} // class Organic_Widgets_Subpage_Section_Widget

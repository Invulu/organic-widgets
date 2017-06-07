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

    $pattern = '/[^a-zA-Z0-9_]/';

    return preg_replace($pattern, '', (string) $input);

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
    $widget_id = $this->sanitize_js_variable( $widget_id );

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
  				var player'.$widget_id.';
  				player'.$widget_id." = new YT.Player('gp-player".$widget_id."', {
  					height: '1014',
  					width: '1920',
  					videoId: '".esc_html__( $video_id )."',
  					events: {
  						'onReady': onPlayerReady".$widget_id.",
  						'onStateChange': onPlayerStateChange".$widget_id."
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

  			echo '
  			// Mute and start playing video when ready
  				function onPlayerReady'.$widget_id."(event) {
  				event.target.mute();
  				event.target.playVideo();
  				var width = jQuery('li.slide').width();
  				var height = jQuery('li.slide').width() * (3/4);
  				event.target.a.style.width = width + 'px';
  				event.target.a.style.height = height + 'px';
  			}
  			// Fade out overlay image
  			function onPlayerStateChange".$widget_id."(event) {
  				if (event.data == YT.PlayerState.PLAYING) {
  						setTimeout( function(){
  							var width = jQuery('li.slide').width();
  							var height = jQuery('li.slide').width() * (3/4);
  							event.target.a.style.width = width + 'px';
  							event.target.a.style.height = height + 'px';
  							jQuery('.gp-video-bg-wrapper').find('iframe').fadeTo('slow', 1);
  							jQuery('.gp-video-bg-wrapper').find('video').fadeTo('slow', 1);
  						}
  					, 100);
  				}
  			}
  			";
  		} // Endif.


  			echo '} </script>';

  }

} // class Organic_Widgets_Subpage_Section_Widget

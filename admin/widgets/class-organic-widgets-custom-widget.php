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
	$pattern =
		'%^# Match any youtube URL
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
		$%x'
		;
	$result = preg_match( $pattern, $video_url, $matches );
	if ( $result ) {
		return $matches[1];
	}
	return false;
}


} // class Organic_Widgets_Subpage_Section_Widget

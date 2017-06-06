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


} // class Organic_Widgets_Subpage_Section_Widget

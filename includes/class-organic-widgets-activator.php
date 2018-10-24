<?php

/**
 * Fired during plugin activation
 *
 * @link       https://organicthemes.com
 * @since      1.0.0
 *
 * @package    Organic_Widgets
 * @subpackage Organic_Widgets/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Organic_Widgets
 * @subpackage Organic_Widgets/includes
 * @author     Organic Themes <info@organicthemes.com>
 */
class Organic_Widgets_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		// Check for previously saved organic widgets and add to widget areas.
		$previous_widgets = get_option( 'organic_widgets_saved_widgets' );

		if ( $previous_widgets ) {

			$new_widgets = get_option( 'sidebars_widgets' );

			// Loop through previous widgets.
			foreach ( $previous_widgets as $widget_area => $widgets ) {
				// If is Organic Widget Area and the area exists in the new widgets.
				if ( strpos( $widget_area, 'organic-widgets' ) != false ) {

					// Loop through widgets in area, and move to new area if not there already.
					foreach ( $widgets as $widget ) {
						if ( ! array_key_exists( $widget_area, $new_widgets ) ) {
							$new_widgets[ $widget_area ] = array();
						}
						if ( ! in_array( $widget, $new_widgets[ $widget_area ] ) ) {
							array_push( $new_widgets[ $widget_area ], $widget );
						}
					}
				}
			}

			// Update widgets in DB.
			update_option( 'sidebars_widgets', $new_widgets );

		} // End if $previous_widgets

	}

}

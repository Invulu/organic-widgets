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

		$previous_widget_assignments = get_option('organic_widgets_prev_widget_assigments');
		error_log(print_r($previous_widget_assignments,1));

		$current_widget_assignments = get_option('sidebars_widgets');
		error_log(print_r($current_widget_assignments,1));

		// get list of current custom widget areas that should be registered

		// for each of these areas, check the previous assignments for which widgets are assigned, and if the widget still exists, put it in the new area


		// update_option('organic_widgets_prev_widget_assigments', $new_widget_assignments );

	}

}

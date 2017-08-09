<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://organicthemes.com
 * @since      1.0.0
 *
 * @package    Organic_Widgets
 * @subpackage Organic_Widgets/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Organic_Widgets
 * @subpackage Organic_Widgets/includes
 * @author     Organic Themes <info@organicthemes.com>
 */
class Organic_Widgets_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

		$widget_assignments = get_option('sidebars_widgets');
		error_log(print_r($widget_assignments,1));

		update_option('organic_widgets_prev_widget_assigments', $widget_assignments );

	}

}

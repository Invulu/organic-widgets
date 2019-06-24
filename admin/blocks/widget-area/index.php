<?php
/**
 * BLOCK: Profile
 *
 * Gutenberg Custom Profile Block assets.
 *
 * @since   1.0.0
 * @package OPB
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register widget block type.
 */
register_block_type(
	'organic/widget-area',
	array(
		'attributes'      => array(
			'widget_area_title' => array(
				'type'    => 'string',
				'default' => '',
			),
		),
		'render_callback' => [ $this, 'render_widget_area' ],
	)
);

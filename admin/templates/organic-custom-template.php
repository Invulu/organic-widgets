<?php
/*
 * Template Name: Organic Custom
 * Description: The Page Template for Organic Custom Widgets
 */

/*-- Check for Overriding Template in theme --*/
if ( locate_template( 'organic-custom-template.php' ) != '' ) {

	// Load the overriding page template from the theme
	get_template_part( 'organic', 'custom-template' );

} else {

	// Load the default Custom Template from the plugin
	get_header(); ?>

	<!-- BEGIN .post class -->
	<div <?php post_class(); ?> id="page-<?php the_ID(); ?>">

			<!-- BEGIN .organic-ocw-container -->
			<div class="organic-ocw-container">

				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

				  <?php if ( is_active_sidebar( ORGANIC_WIDGET_PREFIX . 'page-'. get_the_ID() . '-widget-area' ) ) { ?>

				    <?php dynamic_sidebar( ORGANIC_WIDGET_PREFIX . 'page-'. get_the_ID() . '-widget-area'); ?>

				  <?php } else { ?>

						<!-- BEGIN .organic-widgets-card -->
						<div class="organic-widgets-card organic-widgets-no-content">

							<p class="text-center"><?php printf( wp_kses( __( 'This page has the "Organic Custom" page template applied. Begin <a href="%1$s">Adding Widgets</a> to the page within the WordPress Customizer.', ORGANIC_WIDGETS_18N ), array( 'a' => array( 'href' => array() ) ) ), esc_url( Organic_Widgets_Admin::get_customize_url() ) ); ?></p>

						<!-- END .organic-widgets-card -->
						</div>

				  <?php } ?>

				<?php endwhile; endif; ?>

			<!-- END .organic-ocw-container -->
			</div>

	<!-- END .post class -->
	</div>

  <?php get_footer();

}

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

						<!-- BEGIN .organic-widgets-content -->
						<div class="organic-widgets-content content">

							<!-- BEGIN .post-area -->
							<div class="post-area">

				    		<?php the_content(); ?>

							<!-- END .post-area -->
							</div>

						<!-- END .organic-widgets-content -->
						</div>

				  <?php } ?>

				<?php endwhile; endif; ?>

			<!-- END .sixteen columns -->
			</div>

	<!-- END .post class -->
	</div>

  <?php get_footer();

}

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

	<?php $thumb = ( '' != get_the_post_thumbnail() ) ? wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' ) : false; ?>

	<?php if ( has_post_thumbnail() ) { ?>

	<!-- BEGIN .row -->
	<div class="row">

		<div class="feature-img banner-img" style="background-image: url(<?php echo esc_url( $thumb[0] ); ?>);">
			<?php the_post_thumbnail(); ?>
		</div>

	<!-- END .row -->
	</div>

	<?php } ?>

	<!-- BEGIN .post class -->
	<div <?php post_class(); ?> id="page-<?php the_ID(); ?>">

			<!-- BEGIN .organic-ocw-container -->
			<div class="organic-ocw-container">

				<?php include( 'content/loop-page.php' ); ?>

			<!-- END .sixteen columns -->
			</div>

	<!-- END .post class -->
	</div>

  <?php get_footer();

}

<?php
/**
 * This template displays the page loop.
 *
 * @package    Organic_Widgets
 * @subpackage Organic_Widgets/admin/templates
 */

?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

	<!-- BEGIN .page-holder -->
	<div class="page-holder">

		<!-- BEGIN .article -->
		<article class="article">
      
        <?php if ( is_active_sidebar( ORGANIC_WIDGET_PREFIX . 'page-'. get_the_ID() . '-widget-area' ) ) { ?>

          <?php dynamic_sidebar( ORGANIC_WIDGET_PREFIX . 'page-'. get_the_ID() . '-widget-area'); ?>

        <?php } else { ?>

          <?php the_content(); ?>

        <?php } ?>

		<!-- END .article -->
		</div>

	<!-- END .page-holder -->
</article>

<?php endwhile; endif; ?>

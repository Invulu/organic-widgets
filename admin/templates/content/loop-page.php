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

			<?php if ( ! has_post_thumbnail() || '' == get_theme_mod( 'display_img_title_page', '1' ) ) { ?>
				<h1 class="headline"><?php the_title(); ?></h1>
			<?php } ?>

			<?php the_content( esc_html__( 'Read More', 'organic-origin' ) ); ?>

			<?php wp_link_pages(array(
				'before' => '<p class="page-links"><span class="link-label">' . esc_html__( 'Pages:', 'organic-origin' ) . '</span>',
				'after' => '</p>',
				'link_before' => '<span>',
				'link_after' => '</span>',
				'next_or_number' => 'next_and_number',
				'nextpagelink' => esc_html__( 'Next', 'organic-widgets' ),
				'previouspagelink' => esc_html__( 'Previous', 'organic-widgets' ),
				'pagelink' => '%',
				'echo' => 1,
				)
			); ?>

			<?php edit_post_link( esc_html__( '(Edit)', 'organic-widgets' ), '', '' ); ?>

		<!-- END .article -->
		</div>

	<!-- END .page-holder -->
</article>

	<?php if ( comments_open() || '0' != get_comments_number() ) comments_template(); ?>

<?php endwhile; else : ?>

	<!-- BEGIN .page-holder -->
	<div class="page-holder">

		<!-- BEGIN .article -->
		<article class="article">

			<?php get_template_part( 'content/content', 'none' ); ?>

		<!-- END .article -->
		</article>

	<!-- END .page-holder -->
	</div>

<?php endif; ?>

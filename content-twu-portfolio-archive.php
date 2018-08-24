<?php
/**
 * The template used for displaying Portfolio Archive view
 *
 * @package Illustratr
 */
?>


<header class="page-header">
	<?php twu_inspire_portfolio_title( '<h1 class="page-title">', '</h1>', true ); ?>

	<?php twu_inspire_portfolio_content( '<div class="taxonomy-description">', '</div>' ); ?>
</header><!-- .page-header -->

<div class="portfolio-wrapper">
	<?php /* Start the Loop */ ?>
	<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'content', 'twu-portfolio' ); ?>

	<?php endwhile; ?>
</div><!-- .portfolio-wrapper -->

<?php illustratr_paging_nav( $post->max_num_pages ); ?>
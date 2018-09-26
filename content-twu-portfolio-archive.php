<?php
/**
 * The template used for displaying Portfolio Archive view
 *
 * @package Illustratr
 */
?>





<header class="page-header">
	
		<h1 class="page-title"><?php _e( 'All My Artifacts', 'illustratr' );?> (<?php echo wp_count_posts('twu-portfolio')->publish?> total)</h1>


		<div class="taxonomy-description">
			<?php twu_inspire_portfolio_tagline()?>
		</div>

</header><!-- .page-header -->

<div class="portfolio-wrapper">
	<?php /* Start the Loop */ ?>
	<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'content', 'twu-portfolio' ); ?>

	<?php endwhile; ?>
</div><!-- .portfolio-wrapper -->

<?php illustratr_paging_nav( $post->max_num_pages ); ?>
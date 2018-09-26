<?php
/**
 * The template used for displaying Portfolio Archive view
 *
 * @package Illustratr
 */
?>





<header class="page-header">
	
		<h1><?php
				$term_obj =	get_queried_object(); // the term we need for this taxonomy

				$term = get_term( $term_obj->term_id, 'twu-portfolio-type' );

				$tax_count = twu_portfolio_tax_count('twu-portfolio-type', $term->slug);

				$plural = ( $tax_count == 1) ? '' : 's';

				echo $tax_count . ' Artifact' . $plural . ' of Type "' . $term->name . '"';
			?></h1>



	<?php twu_inspire_portfolio_content( '<div class="taxonomy-description">', '</div>' ); ?>

</header><!-- .page-header -->

<div class="portfolio-wrapper">
	<?php /* Start the Loop */ ?>
	<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'content', 'twu-portfolio' ); ?>

	<?php endwhile; ?>
</div><!-- .portfolio-wrapper -->

<?php illustratr_paging_nav( $post->max_num_pages ); ?>
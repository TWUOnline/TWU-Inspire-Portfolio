<?php
/**
 * Template Name: TWU Portfolio Front Page
 *
 * @package illustratr
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( ! get_theme_mod( 'illustratr_hide_portfolio_page_content' ) ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php if ( '' != get_the_post_thumbnail() ) : ?>
					<div class="entry-thumbnail">
						<?php the_post_thumbnail( 'illustratr-featured-image' ); ?>
					</div><!-- .entry-thumbnail -->
				<?php endif; ?>

				
				<header class="entry-header">
				<?php the_title( '<h1 class="entry-title">', '</h1>' );?>

				</header><!-- .entry-header -->
	
				<div class="entry-content">
					<?php the_content()?>
				</div><!-- .entry-content -->


				<?php edit_post_link( __( 'Edit', 'illustratr' ), '<div class="entry-meta"><span class="edit-link">', '</span></div>' ); ?>

			<?php endwhile; // end of the loop. ?>
		<?php endif; ?>

			<?php
				
				$showposts = 6; // hard coded, sue me

				$args = array(
					'post_type'      => 'twu-portfolio',
					'posts_per_page' => $showposts,
				);
				$project_query = new WP_Query ( $args );
				if ( post_type_exists( 'twu-portfolio' ) && $project_query -> have_posts() ) :
			?>
				<h2 style="text-align:center">Newest Artifacts</h2>
				<div class="portfolio-wrapper">

					<?php /* Start the Loop */ ?>
					<?php while ( $project_query -> have_posts() ) : $project_query -> the_post(); ?>

						<?php get_template_part( 'content', 'twu-portfolio' ); ?>

					<?php endwhile; ?>
					
					

				</div><!-- .portfolio-wrapper -->
				
				<?php 
						if ($project_query->found_posts > $showposts) {
							echo '<p style="text-align:center"><a href="' . get_post_type_archive_link( 'twu-portfolio' ) . '">see all artifacts</a></p>';
						}
					?>

				<?php
					
					wp_reset_postdata();
				?>

			<?php else : ?>

				<section class="no-results not-found">
					<header class="page-header">
						<h1 class="page-title"><?php _e( 'Nothing Found', 'illustratr' ); ?></h1>
					</header><!-- .page-header -->

					<div class="page-content">
						<?php if ( current_user_can( 'publish_posts' ) ) : ?>

							<p><?php printf( __( 'Ready to publish your first artifact? <a href="%1$s">Get started here</a>.', 'illustratr' ), esc_url( admin_url( 'post-new.php?post_type=twu-portfolio' ) ) ); ?></p>

						<?php else : ?>

							<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'illustratr' ); ?></p>
							<?php get_search_form(); ?>

						<?php endif; ?>
					</div><!-- .page-content -->
				</section><!-- .no-results -->

			<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
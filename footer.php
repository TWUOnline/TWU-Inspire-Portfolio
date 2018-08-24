<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Illustratr
 */
?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="footer-area clear">
			<?php
				if ( has_nav_menu( 'social' ) ) {
					wp_nav_menu( array(
						'theme_location'  => 'social',
						'container_class' => 'menu-social',
						'menu_class'      => 'clear',
						'link_before'     => '<span class="screen-reader-text">',
						'link_after'      => '</span>',
						'depth'           => 1,
					) );
				}
			?>
			<div class="site-info">
			
				<?php printf( __( '<strong>TWU Inspire</strong>- a Wordpress Portfolio Theme based on: %1$s', 'illustratr' ), '<a href="http://wordpress.com/themes/illustratr/" rel="designer">Illustratr</a>' ); ?>
			</div><!-- .site-info -->
		</div><!-- .footer-area -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
<?php
/* Functions to modify parent theme for TRU portfolios
                                                                 */


# -----------------------------------------------------------------
# Enqueue Scripts 'n Styles
# -----------------------------------------------------------------

function twu_portfolio_enqueues() {	 
 
    $parent_style = 'illustratr_style'; 
    
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );

}

add_action('wp_enqueue_scripts', 'twu_portfolio_enqueues');



# -----------------------------------------------------------------
# Portfolio Functions, taken off the jetpack
# -----------------------------------------------------------------

// headings for archives
function twu_inspire_portfolio_title( $before = '', $after = '', $is_archive=false ) {
	$title = '';

	if ( is_post_type_archive( 'twu-portfolio' ) ) {
		
		$title = post_type_archive_title( '', false );
	} elseif ( is_tax( 'twu-portfolio-type' ) || is_tax( 'twu-portfolio-tag' ) ) {
		$title = ( is_tax( 'twu-portfolio-tag' ) ) ? 'Artifacts Tagged "' : 'Artifacts of Type "';
		$title .= single_term_title( '', false ) . '"';
	}

	echo $before . $title . $after;
}

// content for the archives
function twu_inspire_portfolio_content( $before = '', $after = '' ) {

	if ( is_tax() && get_the_archive_description() ) {
		echo $before . get_the_archive_description() . $after;
	} else {
		$content = 'Please explore and provide feedback on my artifacts!';
		echo $before . $content . $after;
	}
}

function twu_inspire_post_classes( $classes ) {

	// Adds a class of empty-entry-meta to pages/projects without any entry meta.
	$comments_status = false;
	$tags_list = false;
	if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) {
		$comments_status = true;
	}
	if ( 'twu-portfolio' == get_post_type() ) {
		$tags_list = get_the_term_list( get_the_ID(), 'twu-portfolio-tag' );
	}
	if ( ! current_user_can( 'edit_posts' ) && 'post' != get_post_type() && ! $comments_status && ! $tags_list ) {
		$classes[] = 'empty-entry-meta';
	}
	// Adds a class of portfolio-entry to portfolio projects.
	if ( 'twu-portfolio' == get_post_type() ) {
		$classes[] = 'portfolio-entry';
	}

	return $classes;
}
add_filter( 'post_class', 'twu_inspire_post_classes' );


//borrow Jetpack scripts.

function twu_inspire_scripts() {
	if ( is_post_type_archive( 'twu-portfolio' ) || is_tax( 'twu-portfolio-type' ) || is_tax( 'twu-portfolio-tag' ) || is_page_template( 'portfolio-front.php' ) ) {
		wp_enqueue_script( 'illustratr-portfolio', get_template_directory_uri() . '/js/portfolio.js', array( 'jquery', 'masonry' ), '20140326', true );
	}
	if ( is_singular() && 'twu-portfolio' == get_post_type() ) {
		wp_enqueue_script( 'illustratr-portfolio-single', get_template_directory_uri() . '/js/portfolio-single.js', array( 'jquery', 'underscore' ), '20140329', true );
	}
	if ( is_page_template( 'portfolio-front.php' ) ) {
		wp_enqueue_script( 'illustratr-portfolio-page', get_template_directory_uri() . '/js/portfolio-page.js', array( 'jquery' ), '20140403', true );
	}

}
add_action( 'wp_enqueue_scripts', 'twu_inspire_scripts', 20 );

/* stop jetpack nags 
	h/t https://gist.github.com/digisavvy/174a8a65accce24d9bc8c8f2441e9bdb     */
	
function twu_portfolio_admin_theme_style() {

	wp_register_style( 'custom_wp_admin_css', get_stylesheet_directory_uri() . '/style-admin.css', false, '1.0.0' );
    wp_enqueue_style( 'custom_wp_admin_css' );

}

add_action('admin_enqueue_scripts', 'twu_portfolio_admin_theme_style');


function twu_portfolio_add_toolbar_items( ) {

	global $wp_admin_bar;
	
	$args = array(
		'id'    => 'twu-portfolio',
		'title' => 'TWU Create',
		'href'  => 'https://create.twu.ca/',
		'meta'  => array(
			'title' => __('TWU Create'),            
		),
	);
	
	$wp_admin_bar->add_menu( $args );

	$args = array(
		'id'    => 'portfolio',
		'parent' => 'twu-portfolio',
		'title' => 'E-Portfolio Help',
		'href'  => 'http://create.twu.ca/eportfolios',
		'meta'  => array(
			'title' => __('E-Portfolio Help'),
			'target' => '_blank',
			'class' => ''
		),
	);
	
	$wp_admin_bar->add_menu( $args );

	$args = array(
		'id'    => 'wordpress-start',
		'parent' => 'twu-portfolio',
		'title' => 'Getting Started With WordPress',
		'href'  => 'https://codex.wordpress.org/Getting_Started_with_WordPress',
		'meta'  => array(
			'title' => __('Getting Started With WordPress'),
			'target' => '_blank',
			'class' => ''
		),
	);
	
	$wp_admin_bar->add_menu( $args );

	$args = array(
		'id'    => 'colin',
		'parent' => 'twu-portfolio',
		'title' => 'Best Blog Ever',
		'href'  => 'http://merelearning.ca/',
		'meta'  => array(
			'title' => __('Best Blog Ever'),
			'target' => '_blank',
			'class' => ''
		),
	);
	
	$wp_admin_bar->add_menu( $args );

}

add_action( 'wp_before_admin_bar_render', 'twu_portfolio_add_toolbar_items', 999 );


# -----------------------------------------------------------------
# Misc Stuff
# useful wrenches and hammers
# -----------------------------------------------------------------

// remove the page template placed there for jetpack portfolios
// h/t https://gist.github.com/rianrietveld/11245571

function twu_inspire_remove_page_template( $pages_templates ) {
    unset( $pages_templates['page-templates/portfolio-page.php'] );
    unset( $pages_templates['portfolio-page.php'] );
return $pages_templates;
}

add_filter( 'theme_page_templates', 'twu_inspire_remove_page_template' );





// use 'wp_before_admin_bar_render' hook to also get nodes produced by plugins.
add_action( 'wp_before_admin_bar_render', 'twu_portfolio_adminbar' );

function twu_portfolio_adminbar() {

	// admin bar needs to be known
	global $wp_admin_bar;
	
	// remove all items from New Content menu
	$wp_admin_bar->remove_node('new-post');
	$wp_admin_bar->remove_node('new-media');
	$wp_admin_bar->remove_node('new-page');
	$wp_admin_bar->remove_node('new-user');
	
	// add back the new Post link
	$args = array(
		'id'     => 'new-post',    
		'title'  => 'Blog Post', 
		'parent' => 'new-content',
		'href'  => admin_url( 'post-new.php' ),
		'meta'  => array( 'class' => 'ab-item' )
	);
	$wp_admin_bar->add_node( $args );

	// add back the new Page 
	$args = array(
		'id'     => 'new-page',    
		'title'  => 'Page', 
		'parent' => 'new-content',
		'href'  => admin_url( 'post-new.php?post_type=page' ),
		'meta'  => array( 'class' => 'ab-item' )
	);
	$wp_admin_bar->add_node( $args );

}

?>
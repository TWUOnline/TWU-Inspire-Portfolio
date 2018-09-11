<?php
/* Functions to modify parent theme for TRU portfolios
                                                                 */

# -----------------------------------------------------------------
# Theme Setup
# -----------------------------------------------------------------



add_action( 'after_setup_theme', 'twu_inspire_setup');

function twu_inspire_setup() {
	
	//register support for portfolio features
	add_theme_support( 'twu-portfolio' );
}


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
# Portfolio Functions, borrowed from JetPack
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


//use Jetpack scripts.

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

 
function twu_portfolio_dashboard_widgets() {
	wp_add_dashboard_widget('twu_portfolio_admin', 'Your TWU Portfolio', 'twu_portfolio_make_dashboard_widget');
}

function twu_portfolio_make_dashboard_widget() {
	echo '<p>There are currently <strong>' . twu_artifact_count() . '</strong> artifacts in your portfolio.</p>
	<ul>
		<li><a href="' . get_post_type_archive_link( 'twu-portfolio' ) . '" target="_blank">See all artifacts</a><li>
		<li><a href="' . admin_url( 'edit.php?post_type=twu-portfolio') . '">Manage your artifacts</a></li>	
		<li><a href="' . admin_url( 'post-new.php?post_type=twu-portfolio') . '">Create a new artifact</a></li>
	
	 </ul>';
}

add_action('wp_dashboard_setup', 'twu_portfolio_dashboard_widgets');




# -----------------------------------------------------------------
# General TWU Portfolio Stuff
# -----------------------------------------------------------------


/* stop jetpack nags with css for admin
	h/t https://gist.github.com/digisavvy/174a8a65accce24d9bc8c8f2441e9bdb     */
	
function twu_portfolio_admin_theme_style() {

	wp_register_style( 'custom_wp_admin_css', get_stylesheet_directory_uri() . '/style-admin.css', false, '1.0.0' );
    wp_enqueue_style( 'custom_wp_admin_css' );

}

add_action('admin_enqueue_scripts', 'twu_portfolio_admin_theme_style');


/* Clean up the +New menu to put "Artifacts" at top, remove "Media" / "User"
   Add a custom menu for TWU (hard wired for now)                            */
   
function twu_portfolio_adminbar() {

	// admin bar needs to be known globally
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


	// add a top menu for the TWU Create links
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
			'title' => __('TWU E-Portfolio information and documentation'),
			'target' => '_blank',
			'class' => ''
		),
	);
	
	$wp_admin_bar->add_menu( $args );

	$args = array(
		'id'    => 'wordpress-guide',
		'parent' => 'twu-portfolio',
		'title' => 'WordPress Guide',
		'href'  => 'http://create.twu.ca/eportfolios/wordpress/',
		'meta'  => array(
			'title' => __('TWU Eportfolio WordPress Guide'),
			'target' => '_blank',
			'class' => ''
		),
	);
	
	$wp_admin_bar->add_menu( $args );

	$args = array(
		'id'    => 'wordpress-glossary',
		'parent' => 'twu-portfolio',
		'title' => 'WordPress Glossary',
		'href'  => 'https://www.wpglossary.net/',
		'meta'  => array(
			'title' => __('Definitions of words that you come in contact with when you use WordPress'),
			'target' => '_blank',
			'class' => ''
		),
	);

	$wp_admin_bar->add_menu( $args );
	
	
	$args = array(
		'id'    => 'theme-guide',
		'parent' => 'twu-portfolio',
		'title' => 'TWU Inspire Theme',
		'href'  => 'http://create.twu.ca/eportfolios/portfolio-themes/twu-inspire/',
		'meta'  => array(
			'title' => __('Using the  TWU Inspire theme'),
			'target' => '_blank',
			'class' => ''
		),
	);
	
	$wp_admin_bar->add_menu( $args );
}

add_action( 'wp_before_admin_bar_render', 'twu_portfolio_adminbar', 99 );

function twu_artifact_count() {
	return wp_count_posts('twu-portfolio')->publish;
}


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


// Load enhancement file to display admin notices.
require get_stylesheet_directory() . '/inc/twu-inspire-enhancements.php';


?>
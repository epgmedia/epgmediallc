<?php

require ( get_stylesheet_directory() . '/templates/epg-functions.php' );


function epg_form_styles() {
	wp_enqueue_style('epg-forms', get_stylesheet_directory_uri() . '/includes/timeoff.css');
	wp_enqueue_script('float-labels', get_stylesheet_directory_uri() . '/js/floatlabels.min.js', array("jquery"));
	wp_enqueue_script('it-request', get_stylesheet_directory_uri() . '/js/it-request.js', array("jquery", "float-labels"));
	wp_enqueue_script('html5-shiv', "//html5shiv.googlecode.com/svn/trunk/html5.js", array("jquery", "it-request"));
}
add_action('wp_enqueue_scripts', 'epg_form_styles');

function change_job_listing_slug( $args ) {
	$args['rewrite']['slug'] = _x( 'careers', 'Job permalink - resave permalinks after changing this', 'job_manager' );
	return $args;
}

add_filter( 'register_post_type_job_listing', 'change_job_listing_slug' );
<?php
/*
Plugin Name: Peelback Ad
Description: Peelback ad position
Version: 0.1.0
Author: chriswgerber
Author URI: http://www.chriswgerber.com
*/

//add_action('wp_enqueue_scripts', 'peelback_scripts');

function peelback_scripts() {
	wp_enqueue_script( 'peelbackjs',
	                   '//epgmedia.s3.amazonaws.com/web/EPG%20Media/jquery.peelback.js',
	                   array( 'jquery' ), FALSE, FALSE );
	wp_enqueue_script( 'peelbackad', plugins_url( '/peelback-ad.js', __FILE__ ),
	                   array( 'jquery', 'peelbackjs' ), FALSE, TRUE );
}
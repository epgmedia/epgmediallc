<?php
/*
Template Name: IT Request
*/
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

get_header(); ?>

<div id="content" class="grid col-940">

    <?php if ( empty( $_POST['email'] ) ) {

        include_once( get_stylesheet_directory() . '/includes/it-request-form.php');

    } else {

        include ( get_stylesheet_directory() . '/includes/it-request-email.php' );

    } ?>

</div><!-- end of #content -->

<?php get_footer(); ?>

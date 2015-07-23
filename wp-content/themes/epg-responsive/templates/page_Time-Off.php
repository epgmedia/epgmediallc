<?php
/*
Template Name: Time-Off Request
*/
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

get_header(); ?>

<div id="content" class="grid col-940">

    <?php if ( empty( $_POST['email'] ) ) {

        include ( get_stylesheet_directory() . '/includes/time-off-form.php' );

    } else {

        include ( get_stylesheet_directory() . '/includes/time-off-email.php' );

    } ?>

</div><!-- end of #content -->

<?php get_footer(); ?>
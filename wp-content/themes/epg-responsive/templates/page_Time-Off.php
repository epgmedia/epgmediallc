<?php
/*
Template Name: Time-Off Request
*/
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

get_header(); ?>

<div id="content" class="grid col-940">

    <?php if (empty($_POST)) {

        include ( get_stylesheet_directory() . '/templates/time-off-form.php' );

    } else {

        include ( get_stylesheet_directory() . '/templates/time-off-email.php' );

    } ?>

</div><!-- end of #content -->

<?php get_footer(); ?>
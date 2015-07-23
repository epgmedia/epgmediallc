<?php
/*
Template Name: Support Request
 */
if (!defined( 'ABSPATH' )) { exit; }

get_header(); ?>

    <div id="content" class="grid col-940">

        <?php if ( empty( $_POST['email'] ) ) {

            include ( get_stylesheet_directory() . '/includes/support-form.php' );

        } else {

            include ( get_stylesheet_directory() . '/includes/support-email.php' );

        } ?>

    </div><!-- end of #content -->

<?php get_footer();
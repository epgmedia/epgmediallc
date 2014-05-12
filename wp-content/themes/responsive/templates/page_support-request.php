<?php
/*
Template Name: Support Request
 */
if (!defined( 'ABSPATH' )) { exit; }

//include_once ( get_template_directory() . '/templates/test-data.php' );

get_header(); ?>

    <div id="content" class="grid col-940">

        <?php if (empty($_POST)) {

            include ( get_template_directory() . '/templates/support-form.php' );

        } else {

            include ( get_template_directory() . '/templates/support-email.php' );

        } ?>

    </div><!-- end of #content -->

<?php get_footer();
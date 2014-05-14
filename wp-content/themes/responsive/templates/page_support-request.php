<?php
/*
Template Name: Support Request
 */
if (!defined( 'ABSPATH' )) { exit; }

get_header(); ?>

    <div id="content" class="grid col-940">

        <p>
            <a href="https://trello.com/b/MNr7er6C" target="_blank">Web Help desk</a>
        </p>

        <?php if (empty($_POST)) {

            include ( get_template_directory() . '/templates/support-form.php' );

        } else {

            include ( get_template_directory() . '/templates/support-email.php' );

        } ?>

    </div><!-- end of #content -->

<?php get_footer();
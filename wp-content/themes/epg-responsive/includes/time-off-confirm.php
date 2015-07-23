<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

$_GET['submit'] = 'success';
?>
<h1>Time Off <span>request confirmation</span></h1>
<div class="timeOffWrap">
    <h2>Thank You</h2>
    <div class="innerwrap">
        <h3>Please verify the entries from your submission.</h3>
        <p>
            Date submitted: <span><?php _e( filter_input( INPUT_POST, 'date_submitted', FILTER_SANITIZE_SPECIAL_CHARS ) ); ?></span></p>
        <p>
            A copy of the following email was sent to you, <?php _e ( filter_input( INPUT_POST, 'supervisor', FILTER_SANITIZE_EMAIL ) ); ?> and H.R.:</p>
        <hr />
        <p>
            <?php _e( $email_body ); ?></p>
        <p>
            Thank You!</p>
        <p>
            <a href="javascript:history.go(-1);">To Return to form</a></p>
    </div>
</div>
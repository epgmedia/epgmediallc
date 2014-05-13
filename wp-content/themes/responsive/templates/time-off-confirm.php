<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

$_GET['submit'] = 'success';

?>
<h1>Time Off <span>request confirmation</span></h1>
<div class="timeOffWrap">
    <h2>Thank You</h2>
    <div class="innerwrap">
        <h3>
            Please verify the entries from your submission.
        </h3>
        <p>
            Date submitted: <span><?php echo $_POST['date_submitted']; ?></span>
        </p>
        <p>
            A copy of the following email was sent to you, <?php echo $_POST['supervisor']; ?> and H.R.:
        </p>
        <hr />
        <p>
            <?php echo $email_body; ?>
        </p>
        <p>
            Thank You!
        </p>
        <p>
            <a href="javascript:history.go(-1);">To Return to form</a>
        </p>
    </div>
</div>
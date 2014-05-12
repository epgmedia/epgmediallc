<?php
/*
 * Template name: IT Request Form Email
 */
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

get_header();

/**
 *
 * IT Request Form Email
 *
 * Form to send an email to necessary people for IT request
 *
 * @function Send emails to IT, VP Operations, Supervisor, Employee about request
 */
if(isset($_POST['email'])) {
    /*
     * Returns set of errors
     */
    function died($error) {
        // your error code can go here
        echo "We are very sorry, but there were error(s) found with the form you submitted. ";
        echo "These errors appear below.<br /><br />";
        echo $error."<br /><br />";
        echo "Please go back and fix these errors.<br /><br />";
        die();
    }
    /*
     * Cleans up email string
     */
    function clean_string($string) {
        $bad = array("content-type","bcc:","to:","cc:","href");
        return str_replace($bad,"",$string);
    }

    $error_message = "";

    // Set Global Variables
    if (
        !isset($_POST['date_submitted']) ||
        !isset($_POST['employee']) ||
        !isset($_POST['email']) ||
        !isset($_POST['phoneNumber']) ||
        !isset($_POST['supervisor']) ||
        !isset($_POST['location']) ||
        !isset($_POST['computerType']) ||
        !isset($_POST['shortReason']) ||
        !isset($_POST['reason'])
    ) {
        died('We are sorry, but there appears to be a problem with the form you submitted.');
    } else {
        $dateSubmitted  =   $_POST['date_submitted'];
        $employeeName   =   $_POST['employee'];
        $employeeEmail  =   $_POST['email'];
        $employeeNumber =   $_POST['phoneNumber'];
        $supervisor     =   $_POST['supervisor'];
        $location       =   $_POST['location'];
        $computerType   =   $_POST['computerType'];
        $shortReason    =   stripslashes($_POST['shortReason']);
        $requestReason  =   stripslashes($_POST['reason']);
    }

    /*
     * Preg Replace strings.
     */
    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
    $string_exp = "/^[A-Za-z .'-]+/";
    if(!preg_match($email_exp,$employeeEmail)) {
        $error_message .= 'The email address you entered does not appear to be valid.<br />';
    }
    if(!preg_match($email_exp,$supervisor)) {
        $error_message .= 'The supervisor email address does not appear to be valid.<br />';
    }
    if(!preg_match($string_exp,$employeeName)) {
        $error_message .= 'The name you entered does not appear to be valid.<br />';
    }
    if(strlen($requestReason) <= 2) {
        $error_message .= 'The reason you entered does not appear to be valid text.<br />';
    }
    if(strlen($error_message) > 0) {
        died($error_message);
    }
    $emailMessage .= <<<EmailData
<table width="600" align="center" cellpadding="10" style="border-top:1px solid black;border-bottom:1px solid black;">
    <tr>
        <td align="left">
            <h1>IT Request Form</h1>
            <p>Date submitted: $dateSubmitted <br /></p>
            <h2>From:</h2>
            <table border="0" cellpadding="2"><tr>
                <td align="left"><p>Employee Name:</p></td><td align="left"><p>$employeeName</p></td>
            </tr><tr>
                <td align="left"><p>Email:</p></td><td align="left"><p>$employeeEmail</p></td>
            </tr><tr>
                <td align="left"><p>Phone Number:</p></td><td align="left"><p>$employeeNumber</p></td>
            </tr><tr>
                <td align="left"><p>Office Location:</p></td><td align="left"><p>$location</p></td>
            </tr><tr>
                <td align="left"><p>Computer Type:</p></td><td align="left"><p>$computerType</p></td>
            </tr><tr>
                <td align="left"><p>Supervisor:</p></td><td align="left"><p>$supervisor</p></td>
                </tr>
            </table>
            <h2>The reason for this request:</h2>
            <p>$requestReason</p>
        </td>
    </tr>
</table>
EmailData;

    // Headers array
    $headers = array (
        'From: ' . $employeeEmail,
        'Content-Type: text/html',
        'Cc:' . $employeeName . '<' . $employeeEmail . '>',
        'Cc:' . $supervisor
    );

    if ($supervisor !== "jprusak@snowgoer.com") {
        $headers[] = 'Cc: John Prusak <jprusak@epgmediallc.com>';
    }
    // Send the email with headers
    mail(
        'CNT Admin <cntadmin@epgmediallc.com>',
        'IT Request - ' . $shortReason,
        $emailMessage,
        implode("\r\n", $headers)
    );
} ?>
    <h1>IT Request <span>confirmation</span></h1>
    <div class="timeOffWrap">
        <h2>Thank You</h2>
        <div class="innerwrap">
            <h3>
                Please verify the entries from your submission.
            </h3>
            <p>
                Date submitted: <span><?php echo $dateSubmitted; ?></span>
            </p>
            <p>
                A copy of the following email was sent to you, your supervisor and IT:
            </p>
            <p>
                <?php echo $emailMessage; ?>
            </p>
            <p>
                <?php echo $reason; ?>
            </p>
            <p>
                Your request has been sent to <span><?php echo $supervisor; ?></span>.
            </p>
            <p>
                Thank You!
            </p>
            <p>
                <a href="javascript:history.go(-1);">To Return to form</a>
            </p>
        </div>
    </div>

<?php get_footer(); ?>
<?php
/*
Template Name: Time-Off Confirmation
*/

/**
 *
 * Time-Off Request Form Email
 *
 * Form to send an email to necessary people for Time-Off request
 *
 * @function Send emails to TimeOff@epgmediallc.com, Supervisor, and self
 */
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

get_header();

if(isset($_POST['email']))
{
    // your error code can go here
    function died($error)
    {
        echo "We are very sorry, but there were error(s) found with the form you submitted. ";
        echo "These errors appear below.<br /><br />";
        echo $error."<br /><br />";
        echo "Please go back and fix these errors.<br /><br />";
        die();
    }
	function clean_string($string)
	{
        $bad = array("content-type","bcc:","to:","cc:","href");
        return str_replace($bad,"",$string);
    }

    // validation expected data exists
    if(
        !isset($_POST['employee']) ||
        !isset($_POST['date_submitted']) ||
        !isset($_POST['pay_type']) ||
     	!isset($_POST['datefrom']) ||
    	!isset($_POST['dateto']) ||
    	!isset($_POST['reason']) ||
      	!isset($_POST['email']) ||
    	!isset($_POST['requesting']) ||
        !isset($_POST['supervisor'])
        )
    {
        died('We are sorry, but there appears to be a problem with the form you submitted.');
    } else {
        $employee       = $_POST['employee'];
        $date_submitted = $_POST['date_submitted'];
        $pay_type       = $_POST['pay_type'];
        $datefrom       = $_POST['datefrom'];
        $dateto         = $_POST['dateto'];
        $reason         = clean_string($_POST['reason']);
        $email_from     = $_POST['email'];
        $requesting     = $_POST['requesting'];
        $supervisor     = $_POST['supervisor'];
    }

    // set vars
    // setting up error message
    $error_message  = "";
    $email_exp      = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
    $string_exp     = "/^[A-Za-z .'-]+$/";

	if(!preg_match($email_exp,$email_from)) {
		$error_message .= 'The Email Address you entered does not appear to be valid.<br />';
	}
	if(!preg_match($email_exp,$supervisor)) {
		$error_message .= 'The Supervisor Email Address does not appear to be valid.<br />';
	}
    if(!preg_match($string_exp,$employee)) {
        $error_message .= 'The First Name you entered does not appear to be valid.<br />';
    }
    if(strlen($reason) < 2) {
    	$error_message .= 'The Reason you entered does not appear to be valid text.<br />';
    }
    if(strlen($error_message) > 0) {
		died($error_message);
    }
    //creating email message
    $email_message  = "Form details below.\n\n";
	$email_message .= <<<EmailData
<table width="600" align="center" cellpadding="10" style="border-top:1px solid black;border-bottom:1px solid black;">
    <tr>
        <td align="left">
            <h1>Time-Off Request Form</h1>
            <p>Date submitted: $date_submitted <br /></p>
            <h2>From:</h2>
            <table border="0" cellpadding="2"><tr>
                <td align="left"><p>Employee Name:</p></td><td align="left"><p>$employee</p></td>
            </tr><tr>
                <td align="left"><p>Email:</p></td><td align="left"><p>$email_from</p></td>
            </tr><tr>
                <td align="left"><p>Date submitted:</p></td><td align="left"><p>$date_submitted</p></td>
            </tr>
            </table>
            <p>Requesting $requesting hours of $pay_type time off.</p>
            <p>Beginning $datefrom to $dateto.</p>
            <h2>The reason for this request:</h2>
            <p>$reason</p>
        </td>
    </tr>
</table>
EmailData;

    $email_to       = 'EPG Time-Off Request <timeoff@epgmediallc.com>';
    $email_subject  = 'SCHEDULED AND UNSCHEDULED TIME OFF REQUEST FORM';

    // Headers array
    $headers = array (
        'From: ' . $email_from,
        'Reply-To: '.$email_from,
        'Content-Type: text/html',
        'Cc:' . $employee . '<' . $email_from . '>',
        'Cc:' . $supervisor,
        'X-Mailer: PHP/' . phpversion()
    );

    // Send the email with headers
    mail(
        $email_to,
        $email_subject,
        $email_message,
        implode("\r\n", $headers)
    );
    ?>
    <h1>Time Off<span>request confirmation</span></h1>
    <div class="timeOffWrap">
        <h2>Thank You</h2>
        <div class="innerwrap">
            <h3>
                Please verify the entries from your submission.
            </h3>
            <p>
                Date submitted: <span><?php echo $date_submitted; ?></span>
            </p>
            <p>
                Thank you <span><?php echo $employee; ?></span>. Your email address is: <span><?php echo $email_from; ?></span>.
            </p>
            <p>
                You are requesting <?php echo $requesting; ?> hours of <span><?php echo $pay_type; ?></span> time off
                from <span><?php echo $datefrom; ?></span> to <span><?php echo $dateto; ?></span>.
            <h3>
                The reason for this request:
            </h3>
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
<?php } else {
    header('Location:http://www.epgmediallc.com/time-off-request/');
}
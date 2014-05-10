<?php
/*
 * Template name: Support Request Form Email
 */
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

include_once( ABSPATH . '/wp-includes/class-phpmailer.php' );

get_header();

/**
 *
 * Support Request Form Email
 *
 * Form to send an email to necessary people for support request
 *
 * @function Send emails to Trello, creating a new card for a bug/idea/feature
 */
if(isset($_POST['email'])) {
    /*
     * Returns set of errors
     */
    function died($error) {
        // your error code can go here
        ?>
        <p>
            We are very sorry, but there were error(s) found with the form you submitted. These errors appear below.
        </p>
        <p>
            <?php echo $error; ?>
        </p>
        <p>
            Please go back and fix these errors.
        </p>
        <?php
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
        !isset($_POST['employee']) ||
        !isset($_POST['email']) ||
        !isset($_POST['phoneNumber']) ||
        !isset($_POST['shortReason']) ||
        !isset($_POST['reason'])
    ) {
        died('We are sorry, but there appears to be a problem with the form you submitted.');
    } else {
        $employeeName   = $_POST['employee'];
        $employeeEmail  = $_POST['email'];
        $employeeNumber = $_POST['phoneNumber'];
        $brand          = $_POST['brand'];
        $realm          = $_POST['realm'];
        $issuetype      = $_POST['issuetype'];
        $subject        = stripslashes($_POST['shortReason']);
        $requestReason  = stripslashes($_POST['reason']);
    }

    /*
     * Preg Replace strings.
     */
    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
    $string_exp = "/^[A-Za-z .'-]+/";
    if(!preg_match($email_exp,$employeeEmail)) {
        $error_message .= 'The email address you entered does not appear to be valid.<br />';
    }
    if(!preg_match($string_exp,$employeeName)) {
        $error_message .= 'The name you entered does not appear to be valid.<br />';
    }
    if(strlen($subject) <= 2) {
        $error_message .= 'The reason you entered does not appear to be valid text.<br />';
    }
    if(strlen($error_message) > 0) {
        died($error_message);
    }
    /** Determines the label via $issuetype */
    switch ($issuetype) :
        case 'Bug':
            $subject .= ' #Bug/Issue';
            break;
        case 'Issue':
            $subject .= ' #Bug/Issue';
            break;
        case 'Request':
            $subject .= ' #Request/Idea';
            break;
        case 'Idea':
            $subject .= ' #Request/Idea';
            break;
        default:
            $subject .= '';
            break;
    endswitch;

    if (isset($realm)) {
        $subject .= ' #' . $realm;
    }

    $email_to = 'christophergerber+o8ixesre9a8zmcwzldaf@boards.trello.com';

    $emailMessage = <<<EmailData
#### Requested By:

* Name: **[$employeeName]($employeeEmail)**
* Email: <$employeeEmail>
* Phone Number: $employeeNumber

------------------------------

## Subject:
$requestReason

## Brand: $brand

EmailData;

    $mail = new PHPMailer();

    //Deal with the email
    $mail->From = $employeeEmail; // from

    $mail->AddAddress($email_to); // to address

    $mail->Subject = $subject; // subject

    $mail->Body = $emailMessage; // body

    if (isset($_FILES)) {
        $mail->AddAttachment($_FILES['file']['tmp_name'],$_FILES['file']['name']); // attach uploaded file
    }


} ?>
    <h1>Support Request <span>confirmation</span></h1>
    <div class="timeOffWrap">
        <h2>Thank You</h2>
        <div class="innerwrap">
            <h3>
                Your request has been received.
            </h3>
            <p>
                Thank You!
            </p>
            <p>
                <a href="javascript:history.go(-1);">To Return to form</a>
            </p>
        </div>
    </div>

<?php get_footer(); ?>
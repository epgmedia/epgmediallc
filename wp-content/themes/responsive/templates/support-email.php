<?php
/**
* Support Request Form Email
* Form to send an email to necessary people for support request
*/
if (!defined( 'ABSPATH' )) { exit; }

include_once ( ABSPATH . '/wp-includes/class-phpmailer.php' );

/** The Mail */
$mail = new epg_phpmailer();
$mail->IsHTML(FALSE);
//$mail->IsSMTP();
$mail->ContentType = 'text/plain';
$mail->AddAddress('christophergerber+o8ixesre9a8zmcwzldaf@boards.trello.com');
$mail->from_address($_POST['email']);

/** email_subject function */
$mail->email_subject($_POST['shortReason'], $_POST['issuetype'], $_POST['realm']);

/** Email Content */
$employee = (isset($_POST['employee']) ? $_POST['employee'] : NULL);
$email = (isset($_POST['email']) ? $_POST['email'] : NULL);
$phoneNumber = (isset($_POST['phoneNumber']) ? $_POST['phoneNumber'] : NULL);
$reason = stripslashes((isset($_POST['reason']) ? $_POST['reason'] : NULL));
$brand = (isset($_POST['brand']) ? $_POST['brand'] : NULL);

$mail->Body = <<<email_body
#### Requested By:

* Name: **[$employee]($email)**
* Email: <$email>
* Phone Number: $phoneNumber

------------------------------

## Subject:
$reason

------------------------------
**Brand: $brand**
email_body;

/** Attachments, if there are any */
if (isset($_FILES)) {
    $mail->multiple_attachments($_FILES);
}

if(!$mail->Send()) {

    echo "There was an error sending the email: " . $mail->ErrorInfo;

} else {

    include( get_template_directory() . '/templates/support-confirm.php');

}
<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

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
) {
    died('We are sorry, but there appears to be a problem with the form you submitted.');
}

/** Recipients */
$email_addresses = array(
    'to' => 'timeoff@epgmediallc.com',
    'su' => $_POST['supervisor'],
    'cc' => $_POST['email']
);

/** The Mail */
$mail = new epg_phpmailer();
$mail->IsHTML();
//$mail->IsSMTP();
// Validate the email addresses
foreach ( $email_addresses as $type => $email ) {
    if($mail->validateAddress($email) === FALSE) {
        unset($email_addresses[$type]);
    }
}

/** Recipients */
if (in_array('timeoff@epgmediallc.com', $email_addresses)) {
    $mail->AddAddress( 'timeoff@epgmediallc.com', 'EPG Time-Off Request' );
}
if (in_array($_POST['supervisor'], $email_addresses)) {
    $mail->AddCC( $_POST['supervisor'] );
}
if (in_array($_POST['email'], $email_addresses)) {
    $mail->AddCC( $_POST['email'], $_POST['employee'] );
}
if (in_array($_POST['email'], $email_addresses)) {
    $mail->from_address( $_POST['email'], $_POST['employee'] );
}

/** Subject */
$mail->Subject = 'SCHEDULED AND UNSCHEDULED TIME OFF REQUEST FORM';

/** Email Content */
$email_body = get_include_contents(
    get_template_directory() . '/templates/time-off-message.php'
);
$mail->msgHTML($email_body);

if(!$mail->Send()) {

    echo "There was an error sending the email: " . $mail->ErrorInfo;

} else {

    include( get_template_directory() . '/templates/time-off-confirm.php');

}
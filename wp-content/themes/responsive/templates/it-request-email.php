<?php
if (!defined( 'ABSPATH' )) { exit; }

include_once ( ABSPATH . '/wp-includes/class-phpmailer.php' );

/** vars for later */
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
}

/** Recipients */
$email_addresses = array(
    'to' => 'cntadmin@epgmediallc.com',
    'ma' => 'jprusak@epgmediallc.com',
    'su' => $_POST['supervisor'],
    'cc' => $_POST['email']
);

/** The Mail */
$mail = new epg_phpmailer();
$mail->IsHTML();
$mail->IsSMTP();
// Validate the email addresses
foreach ( $email_addresses as $type => $email ) {
    if($mail->validateAddress($email) === FALSE) {
        unset($email_addresses[$type]);
    }
}

/** Recipients */
if (in_array('cntadmin@epgmediallc.com', $email_addresses)) {
    $mail->AddAddress( 'cntadmin@epgmediallc.com', 'CNT Admin' );
}
$jp = 'jprusak@epgmediallc.com';
if (in_array($jp, $email_addresses)) {
    $mail->AddCC( 'jprusak@epgmediallc.com', 'John Prusak' );
}
if ($_POST['supervisor'] != $jp) {
    $mail->AddCC( $_POST['supervisor'] );
}
if (in_array($_POST['email'], $email_addresses)) {
    $mail->AddCC( $_POST['email'], $_POST['employee'] );
}
if (in_array($_POST['email'], $email_addresses)) {
    $mail->from_address( $_POST['email'], $_POST['employee'] );
}

/** Subject */
$mail->Subject = 'IT Request - ' . $_POST['shortReason'];

/** Email Content */
$email_body = get_include_contents(get_template_directory() . '/templates/it-request-message.php');
$mail->msgHTML($email_body);

if(!$mail->Send()) {

    echo "There was an error sending the email: " . $mail->ErrorInfo;

} else {

    include( get_template_directory() . '/templates/it-request-confirm.php');

}
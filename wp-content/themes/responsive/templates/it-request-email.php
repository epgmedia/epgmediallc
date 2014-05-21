<?php
if (!defined( 'ABSPATH' )) { exit; }

include_once ( ABSPATH . '/wp-includes/class-phpmailer.php' );

/** vars for later */
if (
    !isset($_POST['date_submitted']) ||
    !isset($_POST['employee']) ||
	!isset($_POST['email']) ||
    !isset($_POST['supervisor']) ||
    !isset($_POST['shortReasonText']) ||
	!isset($_POST['shortReasonItem']) ||
    !isset($_POST['reason']) ||
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
/$mail->IsSMTP();
// Validate the email addresses
foreach ( $email_addresses as $type => $email ) {
    if($mail->validateAddress($email) === FALSE) {
        unset($email_addresses[$type]);
    }
}

/** Recipients */
$mail->AddAddress( 'cntadmin@epgmediallc.com', 'CNT Admin' );
$mail->AddCC( 'jprusak@epgmediallc.com', 'John Prusak' );
$jp = 'jprusak@epgmediallc.com';
if ( $_POST['supervisor'] != $jp ) {
    $mail->AddCC( $_POST['supervisor'] );
}
if ( in_array($_POST['email'], $email_addresses ) ) {
    $mail->AddCC( $_POST['email'], $_POST['employee'] );
}
if ( in_array($_POST['email'], $email_addresses ) ) {
    $mail->from_address( $_POST['email'], $_POST['employee'] );
}

$short_reason = 'IT Request';

if ( isset( $_POST['shortReasonText'] ) ) {
	$short_reason .= ' - ' . $_POST['shortReasonText'];
}

if ( isset( $_POST['shortReasonItem'] ) ) {
	$short_reason .= ' - ' . $_POST['shortReasonItem'];
}

/** Subject */
$mail->Subject = $short_reason;

/** Email Content */
$email_body = get_include_contents( get_template_directory() . '/templates/it-request-message.php' );
$mail->msgHTML( $email_body );

/** Image Attachments */
if (isset($_FILES)) {
	$mail->multiple_attachments($_FILES);
}

/** Send the email */
if(!$mail->Send()) {

    echo "There was an error sending the email: " . $mail->ErrorInfo;

} else {

    include( get_template_directory() . '/templates/it-request-confirm.php');

}
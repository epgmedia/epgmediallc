<?php
/**
 * IT Request Form Email
 * Form to send an email to necessary people for IT request
 */
if (!defined( 'ABSPATH' )) { exit; }

/** Checks if values are set before continuing */
if (
	!isset($_POST['date_submitted']) ||
	!isset($_POST['employee']) ||
	!isset($_POST['email']) ||
	!isset($_POST['supervisor']) ||
	( !isset($_POST['shortReasonText']) || !isset($_POST['shortReasonItem']) ) ||
	!isset($_POST['reason'])
) {
	EPG_Forms::died('You are missing some required fields.');
}

include_once( ABSPATH . '/wp-includes/class-phpmailer.php' );

/**
 * The Mail
 *
 * Sends email to requestor, supe, JP, and RJ.
 */
$mail = new epg_phpmailer();
$mail->IsHTML(TRUE);

/** To and From */
$mail->setFrom( $_POST['email'], $_POST['employee'] );
$email_addresses = array(
    array(
		'kind' => 'to',
		'address' => 'cntadmin@epgmediallc.com',
		'name' => 'CNTAdmin'
	),
    array(
		'kind' => 'cc',
		'address' => $_POST['supervisor']
	),
	array(
		'kind' => 'cc',
		'address' => 'jprusak@snowgoer.com',
		'name' => 'John Prusak'
	),
	array(
		'kind' => 'cc',
		'address' => 'mminor@specialtyim.com',
		'name' =>  'Marion Minor'
	),
    array(
		'kind' => 'bcc',
		'address' => $_POST['email'],
		'name' =>  $_POST['employee']
	),
);
$mail->it_request_recipients( $email_addresses );

/** Subject */
$short_reason = 'IT Request';
if ( isset( $_POST['shortReasonText'] ) ) {
	$short_reason .= ': ' . $_POST['shortReasonText'];
}
if ( isset( $_POST['shortReasonItem'] ) ) {
	$short_reason .= ' - ' . $_POST['shortReasonItem'];
}
$mail->Subject = $short_reason;

/** Email Content */
$email_body = get_include_contents( get_stylesheet_directory() . '/templates/it-request-message.php' );
$mail->msgHTML( $email_body );

/** Image Attachments */
if (isset($_FILES)) {
	$mail->multiple_attachments($_FILES);
}

/** Send the email */
if(!$mail->Send()) {

    echo "There was an error sending the email: " . $mail->ErrorInfo;

} else {

    include( get_stylesheet_directory() . '/templates/it-request-confirm.php');

}
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
/**
 * The Mail
 *
 * Sends email to requestor, supe, JP, and RJ. And Marion
 */
$mail = new epg_phpmailer();
$mail->IsHTML(TRUE);
/** To and From */
$mail->setFrom(
	filter_input( INPUT_POST, 'email', FILTER_SANITIZE_EMAIL ),
	filter_input( INPUT_POST, 'employee', FILTER_SANITIZE_SPECIAL_CHARS )
);
$email_addresses = array(
    array(
		'kind' => 'to',
		'address' => 'cntadmin@epgmediallc.com',
		'name' => 'CNTAdmin'
	),
    array(
		'kind' => 'cc',
		'address' => filter_input( INPUT_POST, 'supervisor', FILTER_SANITIZE_EMAIL )
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
	    filter_input( INPUT_POST, 'email', FILTER_SANITIZE_EMAIL ),
	    filter_input( INPUT_POST, 'employee', FILTER_SANITIZE_SPECIAL_CHARS )
	),
);
$mail->multiple_request_recipients( $email_addresses );
/** Subject */
$subject = 'IT Request';
if ( isset( $_POST['shortReasonText'] ) ) {
	$subject .= ': ' . filter_input( INPUT_POST, 'shortReasonText', FILTER_SANITIZE_SPECIAL_CHARS );
}
if ( isset( $_POST['shortReasonItem'] ) ) {
	$subject .= ' - ' . filter_input( INPUT_POST, 'shortReasonItem', FILTER_SANITIZE_SPECIAL_CHARS );
}
$mail->Subject = $subject;
$email_body = $mail->include_html( '/includes/it-request-message.php' );
/** Image Attachments */
if (isset($_FILES)) {
	$mail->multiple_attachments($_FILES);
}
/** Send the email */
if( ! $mail->Send() ) {
    echo "There was an error sending the email: " . $mail->ErrorInfo;
} else {
    include( get_stylesheet_directory() . '/includes/it-request-confirm.php');
}
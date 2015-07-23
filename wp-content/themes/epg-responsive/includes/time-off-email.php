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
    EPG_Forms::died('We are sorry, but there appears to be a problem with the form you submitted.');
}
/** The Mail */
$mail = new epg_phpmailer();
$mail->IsHTML(TRUE);
// Validate the email addresses
$mail->setFrom(
	filter_input( INPUT_POST, 'email', FILTER_SANITIZE_EMAIL ),
	filter_input( INPUT_POST, 'employee', FILTER_SANITIZE_EMAIL )
);
$email_addresses = array(
	array(
		'kind' => 'to',
		'address' => 'timeoff@epgmediallc.com',
		'name' => 'EPG Time-Off Request'
	),
	array(
		'kind' => 'cc',
		'address' => filter_input( INPUT_POST, 'supervisor', FILTER_SANITIZE_EMAIL )
	),
	array(
		'kind' => 'bcc',
		'address' => filter_input( INPUT_POST, 'email', FILTER_SANITIZE_EMAIL ),
		'name' =>   filter_input( INPUT_POST, 'employee', FILTER_SANITIZE_EMAIL )
	),
);
$mail->multiple_request_recipients( $email_addresses );
/** Subject */
$mail->Subject = 'SCHEDULED AND UNSCHEDULED TIME OFF REQUEST FORM';
/** Email Content */
$email_body = $mail->include_html( '/includes/time-off-message.php' );
if(!$mail->Send()) {
    echo "There was an error sending the email: " . $mail->ErrorInfo;
} else {
    include( get_stylesheet_directory() . '/includes/time-off-confirm.php');
}
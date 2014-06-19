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

/** The Mail */
$mail = new epg_phpmailer();
$mail->IsHTML(TRUE);
$mail->IsSMTP();
// Validate the email addresses
$mail->setFrom( $_POST['email'], $_POST['employee'] );
$email_addresses = array(
	array(
		'kind' => 'to',
		'address' => 'timeoff@epgmediallc.com',
		'name' => 'EPG Time-Off Request'
	),
	array(
		'kind' => 'cc',
		'address' => $_POST['supervisor']
	),
	array(
		'kind' => 'bcc',
		'address' => $_POST['email'],
		'name' =>  $_POST['employee']
	),
);
$mail->it_request_recipients( $email_addresses );

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
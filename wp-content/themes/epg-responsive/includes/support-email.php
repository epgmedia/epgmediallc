<?php
/**
 * Support Request Form Email
 * Form to send an email to necessary people for support request
 *
 * Sends plain-text version to Trello and HTML to users
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$mail              = new epg_phpmailer();
$mail->ContentType = 'text/plain';
$employee          = ( isset( $_POST['employee'] ) ? filter_input( INPUT_POST, 'employee', FILTER_SANITIZE_SPECIAL_CHARS ) : null );
$email             = ( isset( $_POST['email'] ) ? filter_input( INPUT_POST, 'email', FILTER_SANITIZE_EMAIL ) : null );
$phoneNumber       = ( isset( $_POST['phoneNumber'] ) ? filter_input( INPUT_POST, 'phoneNumber', FILTER_SANITIZE_SPECIAL_CHARS ) : null );
$shortReason       = ( isset( $_POST['shortReason'] ) ? filter_input( INPUT_POST, 'shortReason' ) : null );
$reason            = ( isset( $_POST['reason'] ) ? filter_input( INPUT_POST, 'reason' ) : null );
$brand             = ( isset( $_POST['brand'] ) ? filter_input( INPUT_POST, 'brand', FILTER_SANITIZE_SPECIAL_CHARS ) : null );
$realm             = ( isset( $_POST['realm'] ) ? filter_input( INPUT_POST, 'realm', FILTER_SANITIZE_SPECIAL_CHARS ) : null );
$issue_type        = ( isset( $_POST['issue_type'] ) ? filter_input( INPUT_POST, 'issue_type', FILTER_SANITIZE_SPECIAL_CHARS ) : null );
$mail->AddAddress( 'christophergerber+o8ixesre9a8zmcwzldaf@boards.trello.com' );
// From Name
$mail->setFrom( $mail->from_address( filter_input( INPUT_POST, 'email', FILTER_SANITIZE_EMAIL ) ), $employee );
// Subject
$mail->email_subject( $shortReason, $issue_type, $realm );
// Trello Email Body
$mail->Body = <<<email_body
# Details:

$reason

------------------------------
**Requested By:**

* Name: **[$employee]($email)**
* Email: <$email>
* Phone Number: $phoneNumber

------------------------------
**Brand: $brand - $realm**
email_body;
$mail->createBody();
// Attachments, if there are any
if ( isset( $_FILES ) ) {
	$mail->multiple_attachments( $_FILES );
}
// First Send
if ( ! $mail->Send() ) {
	echo "There was an error sending the email: " . $mail->ErrorInfo;
} else {
	// Second message to send for further communication
	$user_mail = new epg_phpmailer();
	$user_mail->IsHTML( true );
	$user_mail->IsSMTP();
	$user_mail->setFrom( $email, $employee );
	$email_addresses = array(
		array(
			'kind'    => 'to',
			'address' => 'digital@epgmediallc.com',
			'name'    => 'Digital'
		)
	);
	$user_mail->multiple_request_recipients( $email_addresses );
	$user_mail->email_subject( $shortReason, $issue_type );
	$email_data = array(
		'reason'      => $reason,
		'shortReason' => $shortReason,
		'email'       => $email,
		'employee'    => $employee,
		'phoneNumber' => $phoneNumber,
		'brand'       => $brand,
		'realm'       => $realm,
		'issue_type'  => $issue_type,
	);
	$user_mail->include_html( '/includes/support-message.php', $email_data );
	$user_mail->Send();
	include( get_stylesheet_directory() . '/includes/support-confirm.php' );
}
<?php
/**
* Support Request Form Email
* Form to send an email to necessary people for support request
*/
if (!defined( 'ABSPATH' )) { exit; }
?>
<h4>
	<a href="https://trello.com/b/MNr7er6C/epg-web-requests">Support Page</a>
</h4>
<hr />
<h1>
	Subject:
</h1>
<h4>
	<?php echo stripslashes( $_POST['issuetype'] ); ?> - <?php echo stripslashes( $_POST['shortReason'] ); ?>
</h4>
<p>
	<?php echo stripslashes( esc_textarea( wpautop( $reason ) ) ); ?>
</p>
<hr />
<h2>
	Requested By:
</h2>
<dl>
	<dt>
		Name:
	</dt>
	<dd>
		<a href="<?php echo $email; ?>"><?php echo $employee; ?></a>
	</dd>
	<dt>
		Email:
	</dt>
	<dd>
		<?php echo $email; ?>
	</dd>
	<dt>
		Phone Number:
	</dt>
	<dd>
		<?php echo $phoneNumber; ?>
	</dd>
</dl>
<hr />
<p>
	Brand: <?php echo $brand; ?> - <?php echo $realm; ?>
</p>

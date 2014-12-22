<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

include( 'class.epg_forms.php' );
?>
<div class="epg_form_wrap">
    <form enctype="multipart/form-data" onsubmit="return validate()" method="POST" name="it-request-form" action="">
        <h2>
            IT REQUEST FORM
        </h2>
		<div class="innerwrap">
			<p>
				Please fill out all areas that apply to your request. Any incomplete forms will be sent back. Your cooperation is appreciated.
			</p>
			<h2>Contact Info</h2>
			<p>
				Please enter your information below.
			</p>
			<div class="header">
				<h3><span>Date Submitted:</span></h3>
				<input class="" type="date" maxLength="8" name="date_submitted" value="<?php echo date('Y-m-d'); ?>">
			</div>
            <h3>Full Name:</h3>
            <input autofocus class="floatlabel full-width" type="text" maxLength="35" name="employee" placeholder="Enter your name" >
            <h3>E-mail Address:</h3>
            <input class="floatlabel full-width" type="email" name="email" placeholder="Enter your email address" data-label="Make sure you enter your email address correctly">
            <h3>Phone Number: <em>optional</em></h3>
            <input class="floatlabel full-width" type="text" name="phoneNumber" placeholder="Enter your phone number" data-label="Be sure to include your area code">
            <h3>Office Location: <em>optional</em></h3>
            <input class="floatlabel full-width" type="text" maxlength="40" name="location" placeholder="Office Location" data-label="Ex. Plymouth, Minn.">
			<h3>Supervisor:</h3>
			<select name="supervisor" class="full-width">
				<?php EPG_Forms::epg_form_options( 'Select Supervisor' ); ?>
			</select>
        </div>
        <div class="innerwrap">
			<h2>Details</h2>
				<h3>Subject of the Request</h3>
				<input class="floatlabel full-width" type="text" maxlength="35" name="shortReasonText" placeholder="Short Description of Request" data-label='Ex. "Printer not working" - Limit: 35 Characters'>
				<h3>Or choose from the list:</h3>
				<select name="shortReasonItem" class="full-width">
					<option selected disabled value="none">Type of Issue</option>
					<option value="Internet">Internet</option>
					<option value="Email">Email</option>
					<option value="Printers">Printers</option>
					<option value="Malware">Malware</option>
					<option value="Telephones">Telephones</option>
					<option value="Remote Access">Remote Access</option>
					<option value="Files">Files (Saving/Transfering)</option>
					<option value="Other">Other Request</option>
				</select>
				<h3>Description of Request:</h3>
				<textarea name="reason" placeholder="Please describe the request. Any information included here will speed up support." class="floatlabel full-width"></textarea>
			<h2>Additional</h2>
			<p>
				These fields are optional. Please include any materials that will help troubleshoot the request.
			</p>
			<div class="header">
				<h3>Computer Type: <em>optional</em></h3>
				<input class="floatlabel" type="radio" name="computerType" value="Windows"> Windows
				<input class="floatlabel" type="radio" name="computerType" value="Mac"> Mac
			</div>
			<div class="header">
				<h3>Supporting Attachments <em>optional</em></h3>
				<input class="floatlabel" type="file" name="file[]" id="file" multiple />
			</div>
        </div>
		<div class="innerwrap">
			<div class="header important-submit">
				<input type="submit" value="Submit your request">&nbsp;&nbsp;&nbsp;<input type="reset" value="Clear form">
			</div>
		</div>
    </form>
</div>

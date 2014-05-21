<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
?>
<div class="epg_form_wrap">
    <form enctype="multipart/form-data" onsubmit="return validate()" method="POST" name="MyForm" action="">
        <h2>
            IT REQUEST FORM
        </h2>
        <div class="innerwrap">
            <p>
                Please fill out all areas that apply to your request. Any incomplete forms will be sent back. Your cooperation is appreciated.
            </p>
		</div>
		<div class="innerwrap">
			<h2>Contact Info</h2>
			<p>
				Please enter your information below.
			</p>
			<div class="header">
				<h3><span>Date Submitted:</span></h3>
				<input class="" type="date" maxLength="8" name="date_submitted" value="<?php echo date('Y-m-d'); ?>" disabled>
			</div>
            <div class="header half-width">
                <h3>Full Name:</h3>
                <input autofocus type="text" maxLength="35" name="employee" class="floatlabel full-width" placeholder="Enter your name" >
            </div>
            <div class="header half-width">
                <h3>E-mail Address:</h3>
                <input class="floatlabel full-width" type="email" name="email" placeholder="Enter your email address" data-label="Make sure you enter your email address correctly">
            </div>
            <div class="header half-width">
                <h3>Phone Number: <em>optional</em></h3>
                <input class="floatlabel full-width" type="text" name="phoneNumber" placeholder="Enter your phone number" data-label="Be sure to include your area code">
            </div>
            <div class="header half-width">
                <h3>Office Location: <em>optional</em></h3>
                <input class="floatlabel full-width" type="text" maxlength="40" name="location" placeholder="Office Location" data-label="Ex. Plymouth, Minn.">
            </div>
			<div class="header half-width">
				<h3>Supervisor:</h3>
				<select name="supervisor" class="full-width">
					<option selected disabled value="none">Select Supervisor</option>
					<option value="acollins@boatingindustry.com">Amy Collins</option>
					<option value="dmcmahon@powersportsbusiness.com">Dave McMahon</option>
					<option value="mminor@specialtyim.com">Marion Minor</option>
					<option value="jpatterson@epgmediallc.com">Jeff Patterson</option>
					<option value="cperschmann@epgmediallc.com">Cherri Perschmann</option>
					<option value="jjuda@specialtyim.com">Joanne Juda-Prainito</option>
					<option value="jprusak@snowgoer.com">John Prusak</option>
					<option value="troorda@epgmediallc.com">Terry Roorda</option>
					<option value="aschmieg@epgmediallc.com">Angela Schmieg</option>
					<option value="jsweet@boatingindustry.com">Jonathan Sweet</option>
					<option value="mtuttle@ridermagazine.com">Mark Tuttle</option>
					<option value="bwohlman@epgmediallc.com">Bernadette Wohlman</option>
				</select>
			</div>
        </div>
        <div class="innerwrap">
			<h2>Details</h2>
			<div class="header half-width">
				<h3>What is the nature of the request?</h3>
				<input class="floatlabel" type="text" maxlength="35" name="shortReasonText" placeholder="Short Description of Request" data-label="Ex. Printer Problem - Limited to 35 Characters">
			</div>
			<div class="header half-width center">
				<h3>Or choose from the list:</h3>
				<select name="shortReasonItem" class="full-width">
					<option selected disabled value="none">Type of Issue</option>
					<option value="">Internet</option>
					<option value="">Email</option>
					<option value="">Printers</option>
					<option value="">Malware</option>
					<option value="">Telephones</option>
					<option value="">Remote Access</option>
					<option value="">Files (Saving/Transfering)</option>
					<option value="">Other (Please describe)</option>
				</select>
			</div>
			<div class="header">
				<h3>Description of Request:</h3>
				<textarea name="reason" placeholder="Please describe the request. Any information included here will speed up support." class="floatlabel full-width"></textarea>
			</div>
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

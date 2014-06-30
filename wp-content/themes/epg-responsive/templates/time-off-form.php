<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
?>
<div class="epg_form_wrap">
    <form enctype="multipart/form-data" onsubmit="return validate()" method="POST" name="MyForm" action="">
        <h2>
            TIME OFF REQUEST FORM
        </h2>
        <div class="innerwrap">
            <p>
                Each employee must submit this request form to his/her manager at least five (5) working days prior
                to the "planned" day(s) off. Requested time off is subject to manager's approval, and priority is
                given on a "<span>First Come First Serve Basis</span>".
            </p>
            <div class="header half-width">
                <h3>Employee Name:</h3>
                <input class="floatlabel full-width" type="text" maxLength="21" name="employee" placeholder="Enter your name">
            </div>
            <div class="header half-width">
                <h3>Your E-mail Account:</h3>
                <input class="floatlabel full-width" type="text" name="email" placeholder="Enter your email address" data-label="Make sure you enter your email address correctly">
            </div>
			<div class="header half-width">
				<h3>Send To:</h3>
				<select name="supervisor">
					<option selected value="none">Select Supervisor</option>
					<option value="acollins@boatingindustry.com">Amy Collins</option>
					<option value="aesham@specialtyim.com">Andrew Esham</option>
					<option value="dmcmahon@powersportsbusiness.com">Dave McMahon</option>
					<option value="mminor@specialtyim.com">Marion Minor</option>
					<option value="jpatterson@epgmediallc.com">Jeff Patterson</option>
					<option value="cperschmann@epgmediallc.com">Cherri Perschmann</option>
					<option value="jjuda@specialtyim.com">Joanne Juda-Prainito</option>
					<option value="jprusak@snowgoer.com">John Prusak</option>
					<option value="troorda@epgmediallc.com">Terry Roorda</option>
					<option value="aschmieg@epgmediallc.com">Angela Schmieg</option>
					<option value="ssutherland@thunderpress.net">Stuart Sutherland</option>
					<option value="jsweet@boatingindustry.com">Jonathan Sweet</option>
					<option value="mtuttle@ridermagazine.com">Mark Tuttle</option>
					<option value="dvoll@ridermagazine.com">Dave Voll</option>
					<option value="bwohlman@epgmediallc.com">Bernadette Wohlman</option>
				</select>
			</div>
			<div class="header half-width">
				<h3>Date Submitted:</h3>
				<input class="floatlabel full-width date_submitted" type="text" maxLength="8" value="01/01/14" name="date_submitted" placeholder="Format: mm/dd/yy">
			</div>
			<h2>
				REQUEST
			</h2>
			<h3>
				SCHEDULED AND UNSCHEDULED
			</h3>
			<p>
				If you have <span>not yet accrued</span> or have used all of your <strong>PAID TIME OFF</strong>,
				you may request <strong>UNPAID TIME OFF</strong>. This is subject to management approval.
			</p>
			<p>
				Please fill out all areas that apply to your request. Any incomplete forms will be sent back. Your cooperation is appreciated
			</p>
			<div class="header half-width">
				<h3>Number of hour(s) requested:</h3>
				<input class="floatlabel full-width" type="text" maxLength="4" name="requesting" placeholder="Number of hours" data-label="Example: 7.5">
			</div>
			<div class="header half-width">
				<h3>Please indicate time-off type:</h3>
				<select name="pay_type" class="full-width">
					<option selected disabled>Select</option>
					<option value="vacation">Vacation</option>
					<option value="float">Floating Holiday</option>
					<option value="sick">Sick</option>
					<option value="unpaid">Unpaid</option>
				</select>
			</div>
			<div class="header full-width">
				<h3>Date(s) Requested (Please indicate month, day and year)</h3>
			</div>
			<div class="header half-width">
				From: <input class="floatlabel full-width" type="text" maxLength="8" name="datefrom" placeholder="Example: 03/24/14" data-label="Format: mm/dd/yy">
			</div>
			<div class="header half-width">
				To: <input class="floatlabel full-width" type="text" maxLength="8" name="dateto" placeholder="Example: 03/24/14" data-label="Format: mm/dd/yy">
			</div>
			<div class="header full-width">
				<h3>Reason for request:</h3>
				<textarea name="reason" placeholder="Reason for request..." class="floatlabel"></textarea>
			</div>
			<div class="header important-submit">
				<h3>
					<input type="submit" value="Submit your request"> <input type="reset" value="Clear form">
				</h3>
			</div>
        </div>
    </form>
</div>
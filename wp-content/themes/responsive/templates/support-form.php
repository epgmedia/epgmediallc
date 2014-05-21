<div class="epg_form_wrap">

    <form enctype="multipart/form-data" onsubmit="return validate()" method="POST" name="MyForm" action="">
        <h2>
            SUPPORT REQUEST
        </h2>
        <div class="innerwrap">
			<p>
				<a href="https://trello.com/b/MNr7er6C" target="_blank">Web Help Desk</a>
			</p>
            <p>
                Please fill out all areas that apply to your request. Your cooperation is appreciated.
            </p>
			<div class="header half-width">
				<h3>Employee Name:</h3>
				<input autofocus type="text" maxLength="29" name="employee" class="floatlabel" placeholder="Enter your name" >
			</div>
			<div class="header half-width">
				<h3>Your E-mail Address:</h3>
				<input class="floatlabel" type="email" name="email" placeholder="Enter your email address" data-label="Make sure you enter your email address correctly">
			</div>
            <div class="header">
                <h3>Phone Number: <em>optional</em></h3>
                <input class="floatlabel" type="text" name="phoneNumber" placeholder="Enter your phone number" data-label="Be sure to include your area code">
            </div>
        </div>
        <h2>Description</h2>
        <div class="innerwrap">
            <p>
                Please fill out as many fields as possible. The more information that you provide, the easier it
                will be to troubleshoot.
            </p>
			<div class="header half-width">
				<h3>Brief Description:</h3>
				<input class="floatlabel" type="text" maxLength="35" name="shortReason" placeholder="What is the issue?" data-label="Short description of request (Limit 35 characters)">
			</div>
			<div class="header half-width">
				<h3>This is related to: <em>optional</em></h3>
				<p>
					<input type="radio" name="realm" value="Web" /> Website <input type="radio" name="realm" value="Email" /> Email
				</p>
			</div>
			<div class="header half-width">
            	<h3>Brand: <em>optional</em></h3>
            	<select name="brand">
					<option selected disabled>Choose a brand</option>
					<option>EPG Media and Specialty Information</option>
					<option>Arbor Age</option>
					<option>Beverage Dynamics</option>
					<option>Boating Industry</option>
					<option>Cheers</option>
					<option>Fuel Oil News</option>
					<option>Landscape & Irrigation</option>
					<option>Outdoor Power Equipment</option>
					<option>Powersports Business</option>
					<option>Rider</option>
					<option>Snow Goer</option>
					<option>SportsTurf</option>
					<option>StateWays</option>
					<option>Thunder Press</option>
				</select>
			</div>
			<div class="header half-width">
				<h3>Type of Request: <em>optional</em></h3>
				<select name="issuetype">
					<option selected disabled>Pick One</option>
					<option>Bug</option>
					<option>Issue</option>
					<option>Request</option>
					<option>Idea</option>
				</select>
			</div>
			<div class="header full-width">
            	<p>
                	Describe what the issue is, steps that caused the issue, and any other additional information that
                	could help find a solution.
            	</p>
            	<textarea name="reason" placeholder="Please describe the issue" class="floatlabel"></textarea>
			</div>
			<div class="header">
            	<h3>Supporting Attachments: <em>optional</em></h3>
            	<input class="floatlabel" type="file" name="file[]" id="file" multiple />
				<p>
					Attach any screenshots or other files that could help with your request.
				</p>
			</div>
			<div class="header important-submit">
            <h3>
                <input type="submit" value="Submit your request"> <input type="reset" value="Clear form">
            </h3>
        </div>
    </form>
</div>
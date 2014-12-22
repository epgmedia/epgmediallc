<div class="epg_form_wrap">
    <form enctype="multipart/form-data" onsubmit="return validate()" method="POST" name="MyForm" action="">

        <h2>Web Support Request</h2>

        <div class="innerwrap">

			<p>
				<a href="https://trello.com/b/MNr7er6C" target="_blank">Web Help Desk</a></p>

            <p>
                Please fill out all areas that apply to your request. Your cooperation is appreciated.</p>

	        <h3>Name:</h3>
			<p>
				<input autofocus class="floatlabel full-width" type="text" maxLength="36" name="employee" placeholder="Enter your name" ></p>

	        <h3>E-mail Address:</h3>
			<p>
				<input class="floatlabel full-width" type="email" name="email" placeholder="Enter your email address" data-label="Make sure you enter your email address correctly"></p>

	        <h2>Description</h2>
            <p>
                Please fill out as many fields as possible. The more information that you provide, the easier it
                will be to troubleshoot.</p>

	        <h3>Subject:</h3>
	        <p>
		        <input class="floatlabel full-width" type="text" maxLength="35" name="shortReason" placeholder="Briefly Describe Your Request" data-label="Short description of request (Limit 35 Characters)"></p>

	        <h3>Description</h3>
	        <p>
		        Describe what the issue is, steps that caused the issue, and any other additional information that
		        could help find a solution.</p>

	        <p>
		        <textarea name="reason" placeholder="Please describe the issue, including any steps needed to reproduce or examples." class="floatlabel"></textarea></p>

	        <h3><em>Optional Fields</em></h3>
	        <p>
		        The below fields are optional, but can go a long way towards helping to assist
	            you with your request.</p>

	        <h4>Brand: <em>optional</em></h4>
	        <p>
		        <select name="brand">
			        <option selected disabled>Choose a brand</option>
			        <option>EPG Media and Specialty Information</option>
			        <option>Arbor Age</option>
			        <option>Beverage Dynamics</option>
			        <option>Beverage Insights</option>
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
		        </select></p>

	        <h4>Type of Request: <em>optional</em></h4>
	        <p>
		        <select name="issuetype">
			        <option selected disabled>Pick One</option>
			        <option>Bug</option>
			        <option>Issue</option>
			        <option>Enhancement</option>
			        <option>Question</option>

		        </select></p>

	        <h3>This is related to: <em>optional</em></h3>
			<p>
				<input type="radio" name="realm" value="Web" /> Website <input type="radio" name="realm" value="Email" /> Email</p>

	        <h3>Supporting Attachments: <em>optional</em></h3>
	        <p>
		        <input class="floatlabel" type="file" name="file[]" id="file" multiple /></p>

	        <p>
				Attach any screenshots or other files that could help with your request.
			</p>

	        <div class="header important-submit">
	            <h3>
	                <input type="submit" value="Submit your request"> <input type="reset" value="Clear form">
	            </h3>
            </div>

        </div>
    </form>
</div>
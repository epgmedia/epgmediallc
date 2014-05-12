<div class="techRequestWrap">
    <form enctype="multipart/form-data" onsubmit="return validate()" method="POST" name="MyForm" action="">
        <h2>
            SUPPORT REQUEST
        </h2>
        <div class="innerwrap">
            <p>
                Please fill out all areas that apply to your request. Any incomplete forms will be sent back. Your cooperation is appreciated.
            </p>
            <div>
                <div class="header half-width">
                    <h3>Employee Name:</h3>
                    <input autofocus type="text" maxLength="29" name="employee" class="floatlabel" placeholder="Enter your name" >
                </div>
                <div class="header half-width">
                    <h3>Your E-mail Address:</h3>
                    <input class="floatlabel" type="email" name="email" placeholder="Enter your email address" data-label="Make sure you enter your email address correctly">
                </div>
            </div>
            <div class="header">
                <h3>Phone Number:</h3>
                <input class="floatlabel" type="text" name="phoneNumber" placeholder="Enter your phone number" data-label="Be sure to include your area code">
            </div>
        </div>
        <h2>DESCRIPTION</h2>
        <div class="innerwrap">
            <p>
                Please fill out as many fields as possible. The more information that you provide, the easier it
                will be to troubleshoot.
            </p>
            <h3>Brand</h3>
            <select name="brand">
                <option selected disabled>Choose a brand</option>
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
            <h3>This is related to</h3>
            <p>
                <input type="radio" name="realm" value="Web" /> a website <br />
                <input type="radio" name="realm" value="Email" /> email
            </p>
            <h3>Type of Request</h3>
            <select name="issuetype">
                <option selected disabled>Pick One</option>
                <option>Bug</option>
                <option>Issue</option>
                <option>Request</option>
                <option>Idea</option>
            </select>
            <h3>Brief Description:</h3>
            <input class="floatlabel" type="text" max-length="48" name="shortReason" placeholder="What is the issue?" data-label="Short description of request">
            <h3>More information:</h3>
            <p>
                Describe what the issue is, steps that caused the issue, and any other additional information that
                could help find a solution.
            </p>
            <textarea name="reason" placeholder="About the issue." class="floatlabel"></textarea>
            <h3>Supporting Attachments</h3>
            <input class="floatlabel" type="file" name="file[]" id="file" multiple />
            <h3>
                <input type="submit" value="Submit your request"> <input type="reset" value="Clear form">
            </h3>
        </div>
    </form>
</div>
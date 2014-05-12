<?php

/*
Template Name: IT Request
*/
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

get_header(); ?>

<div id="content" class="grid col-940">
	<div class="techRequestWrap">
		<form onsubmit="return validate()" method="POST" name="MyForm" action="<?php get_site_url(); ?>/it-request-confirmation/">
            <h2>
                IT REQUEST FORM
            </h2>
            <div class="innerwrap">
                <p>
                    Please fill out all areas that apply to your request. Any incomplete forms will be sent back. Your cooperation is appreciated.
                </p>
                <div class="header">
                    <h3>Date Submitted:</h3>
                    <input class="floatlabel date_submitted" type="text" maxLength="8" name="date_submitted" placeholder="Today's Date - Format: mm/dd/yy">
                </div>
                <div class="header">
                    <h3>Employee Name:</h3>
                    <input autofocus type="text" maxLength="29" name="employee" class="floatlabel" placeholder="Enter your name" >
                </div>
                <div class="header">
                    <h3>Your E-mail Account:</h3>
                    <input class="floatlabel" type="email" name="email" placeholder="Enter your email address" data-label="Make sure you enter your email address correctly">
                </div>
                <div class="header">
                    <h3>Phone Number:</h3>
                    <input class="floatlabel" type="text" name="phoneNumber" placeholder="Enter your phone number" data-label="Be sure to include your area code">
                </div>
                <div class="header">
                    <h3>Supervisor:</h3>
                    <select name="supervisor">
                        <option selected value="none">Select Supervisor</option>
                        <option value="mminor@specialtyim.com">Marion Minor</option>
                        <option value="acollins@boatingindustry.com">Amy Collins</option>
                        <option value="cperschmann@epgmediallc.com">Cherri Perschmann</option>
                        <option value="dmcmahon@powersportsbusiness.com">Dave McMahon</option>
                        <option value="jjuda@specialtyim.com">Joanne Juda-Prainito</option>
                        <option value="jpatterson@epgmediallc.com">Jeff Patterson</option>
                        <option value="jprusak@snowgoer.com">John Prusak</option>
                        <option value="troorda@epgmediallc.com">Terry Roorda</option>
                        <option value="aschmieg@epgmediallc.com">Angela Schmieg</option>
                        <option value="jsweet@boatingindustry.com">Jonathan Sweet</option>
                        <option value="mtuttle@ridermagazine.com">Mark Tuttle</option>
                        <option value="bwohlman@epgmediallc.com">Bernadette Wohlman</option>
                    </select>
                </div>
                <div class="header">
                    <h3>Please enter your office location:</h3>
                    <input class="floatlabel" type="text" max-length="30" name="location" placeholder="Office Location" data-label="Ex. Plymouth, Minn.">
                </div>
            </div>
            <h2>ABOUT YOUR ISSUE:</h2>
            <div class="innerwrap">
                <div class="header">
                    <h3>Computer Type:</h3>
                    <input class="floatlabel" type="radio" name="computerType" value="Windows"> Windows
                    <input class="floatlabel" type="radio" name="computerType" value="Mac"> Mac
                </div>
                <h3>Issue:</h3>
                <input class="floatlabel" type="text" max-length="48" name="shortReason" placeholder="What is your request related to?" data-label="Short description of request">
                <h3>Reason for Request:</h3>
                <textarea name="reason" placeholder="Reason for request..." class="floatlabel"></textarea>
                <h3>
                    <input type="submit" value="Submit your request"> <input type="reset" value="Clear form">
                </h3>
            </div>
        </form>
    </div>

</div><!-- end of #content -->

<?php get_footer(); ?>

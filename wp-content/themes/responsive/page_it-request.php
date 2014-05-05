<?php
/*
Template Name: IT Request
*/
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv="X-UA-Compatible" content="chrome=1">
	<title>IT Request</title>
	<link href="<?php bloginfo('template_directory');?>/timeoff.css" rel="stylesheet" type="text/css">
	<script src="<?php bloginfo('template_directory');?>/js/jquery-1.10.2.js" type="text/javascript"></script>
	<script src="<?php bloginfo('template_directory');?>/js/floatlabels.min.js" type="text/javascript"></script>
	<!-- <link href="timeoff.css" rel="stylesheet" type="text/css">
	<script src="jquery-1.10.2.js" type="text/javascript"></script>
	<script src="floatlabels.min.js" type="text/javascript"></script> -->
	<!--[if lt IE 9]>
        <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>



<body>
	<h1>EPG Media, LLC <span>it request form</span></h1>


	<div class="techRequestWrap">
		<form onsubmit="return validate()" method="POST" name="MyForm" action="<?php get_site_url(); ?>/it-request-confirmation/">
        <h2>IT REQUEST FORM</h2>
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
                    <option value="m.ch.adams@gmail.com">Mark Adams</option>
                    <option value="acollins@boatingindustry.com">Amy Collins</option>
                    <option value="bhammer@epgmediallc.com">Barb Hammer</option>
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

	<script>
        /*
            Float Labels
        */
        $('input.floatlabel').floatlabel({
            labelEndTop:0
        });
        /*
            Document Focus
        */
        $(document).ready(function() {
            $('form:first *:input[type!=hidden]:first').focus();
        });


        /*
         * Set Date submitted Variable
         */
		var today = new Date()
		var month = today.getMonth()
		var day = today.getDate()
		var year = today.getFullYear()
		var s = "/"
		var monthname;
		   if (month == 0) monthname = "01";
		   if (month == 1) monthname = "02";
		   if (month == 2) monthname = "03";
		   if (month == 3) monthname = "04";
		   if (month == 4) monthname = "05";
		   if (month == 5) monthname = "06";
		   if (month == 6) monthname = "07";
		   if (month == 7) monthname = "08";
		   if (month == 8) monthname = "09";
		   if (month == 9) monthname = "10";
		   if (month == 10) monthname = "11";
		   if (month == 11) monthname = "12";

		var dayname;
		   if (day == 01) day = "01";
		   if (day == 02) day = "02";
		   if (day == 03) day = "03";
		   if (day == 04) day = "04";
		   if (day == 05) day = "05";
		   if (day == 06) day = "06";
		   if (day == 07) day = "07";
		   if (day == 08) day = "08";
		   if (day == 09) day = "09";

		var yearname;
		   if (year == 2000) yearname = "00";
		   if (year == 2001) yearname = "01";
		   if (year == 2002) yearname = "02";
		   if (year == 2003) yearname = "03";
		   if (year == 2004) yearname = "04";
		   if (year == 2005) yearname = "05";
		   if (year == 2006) yearname = "06";
		   if (year == 2007) yearname = "07";
		   if (year == 2008) yearname = "08";
		   if (year == 2009) yearname = "09";
		   if (year == 2010) yearname = "10";
		   if (year == 2011) yearname = "11";
		   if (year == 2012) yearname = "12";
		   if (year == 2013) yearname = "13";
		   if (year == 2014) yearname = "14";

        var populate_date = monthname + s + day + s + yearname;

        $(".date_submitted").val(populate_date);

		//document.Myform.date_submitted.value = monthname + s + day + s + yearname;

        /**
         *
         * @TODO update to return for any form
         *
         * @returns {boolean}
         */

        function validate() {
            if (!checktextbox(document.Myform.employee,"Please enter your First and Last name!"))
                return false;
            if (!checkdate(document.Myform.date_submitted))
                return false;
            if(!check_paytype())
                return false;
            if(!checktextbox(document.Myform.requesting,"Please enter Time Off requested!"))
                return false;
            if (!checkdate(document.Myform.datefrom))
		        return false;
		    if (!checkdate(document.Myform.dateto))
		        return false;
		    if(!checktextbox(document.Myform.reason,"Please enter Comment!"))
		        return false;
		    if(!checktextbox(document.Myform.email,"Please enter Your E-mail address!"))
		        return false;
		    if(document.Myform.supervisor.value == "none"){
                alert("Please select your Supervisor");
                document.Myform.supervisor.focus();
                    return false;
            }
		}

		// ************ check for empty Textboxes **********
		function checktextbox(textbox,message){
            if (textbox.value == ""){
                alert(message);
                textbox.focus();
                return false;
            } else
                return true;
		}

		// *********** check for valid Date entries **********
		function checkdate(date){
            var valid_date = 0;
            if (date.value.length !=8)
                valid_date = -1;
            if (date.value.charAt(2) != "/" || date.value.charAt(5) != "/")
                valid_date = -1;
            if (valid_date == -1){
                alert("Please enter date in this format 09/15/00");
                date.focus();
                return false;
		    } else
                return true;
		}

		// ************** is Pay Type selected ***************
		function check_paytype(){
            var paytype = -1
            for (i=0; i<document.Myform.pay_type.length; i++){
                if (document.Myform.pay_type[i].checked)
                paytype = i;
		    }
            if (paytype == -1){
                alert("You must select paid, unpaid or sick");
                return false;
		    } else
                return true;
		}

		// ************** is Day or Hour selected ************
		function check_dayhour(){
            var dayhour = -1
            for (c=0; c<document.Myform.days_or_hours.length; c++){
                if (document.Myform.days_or_hours[c].checked)
                    dayhour = c;
            }
            if (dayhour == -1){
                alert("You must enter number of hours!");
                return false;
            } else
                return true;
        }
	</script>


</body>
</html>

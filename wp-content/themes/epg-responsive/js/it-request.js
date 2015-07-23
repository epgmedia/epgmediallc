jQuery(document).ready(function ($) {

    /*
    Float Labels
    */
    $('input.floatlabel').floatlabel({
        labelStartTop: '10px',
        labelEndTop: '0',
        transitionDuration: 0.1
    });
    /*
    Document Focus
    */
    $(document).ready(function() {
        $('form:first *:input[type!=hidden]:first').focus();
    });

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

});
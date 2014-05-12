<?php
/**
 * User: Chris Gerber
 * Date: 5/12/14
 */

include_once ( ABSPATH . '/wp-includes/class-phpmailer.php' );

class epg_phpmailer extends phpmailer {
    // Set default variables for all new objects


    public function from_address($string) {
        $this->From = $string;
        $bad = array("content-type","bcc:","to:","cc:","href");
        return str_replace($bad,"",$this->From);
    }

    public function email_subject ($subject, $issuetype, $realm) {
        $subject = $subject;
        /** Determines the label via $issuetype */
        switch ($issuetype) :
            case 'Bug':
                $subject .= ' #Bug/Issue';
                break;

            case 'Issue':
                $subject .= ' #Bug/Issue';
                break;

            case 'Request':
                $subject .= ' #Request/Idea';
                break;

            case 'Idea':
                $subject .= ' #Request/Idea';
                break;

            default:
                $subject .= '';
                break;

        endswitch;

        if (isset($realm)) {
            $subject .= ' #' . $realm;
        }

        if (strlen($subject) > 1) {
            $this->Subject = stripslashes($subject);
        }



        return FALSE;
    }

    public function multiple_attachments($files) {
        $i = 0;
        foreach ($files as $num => $file) {
            $this->AddAttachment(
                $files['file']['tmp_name'][$i],
                $files['file']['name'][$i]
            );
            $i++;
        }
    }

}


function get_include_contents($filename, $data) {
    extract($data);
    if (is_file($filename)) {
        ob_start();
        include $filename;
        return ob_get_clean();
    }

    return false;
}

/*
 * Returns set of errors
 */
function died($error) {
    // your error code can go here
    ?>
    <p>
        We are very sorry, but there were error(s) found with the form you submitted. These errors appear below.
    </p>
    <p>
        <?php echo $error; ?>
    </p>
    <p>
        Please go back and fix these errors.
    </p>
    <?php
    die();
}

/*
 * Cleans up email string
 */

function prepare_email_vars() {

    $error_message = "";

    // Set Global Variables
    if (
        !isset($_POST['employee']) ||
        !isset($_POST['email']) ||
        !isset($_POST['phoneNumber']) ||
        !isset($_POST['shortReason']) ||
        !isset($_POST['reason'])
    ) {
        died('We are sorry, but there appears to be a problem with the form you submitted.');
    } else {
        $employeeName   = $_POST['employee'];
        $employeeEmail  = $_POST['email'];
        $employeeNumber = $_POST['phoneNumber'];
        $brand          = $_POST['brand'];
        $realm          = $_POST['realm'];
        $issuetype      = $_POST['issuetype'];
        $subject        = stripslashes($_POST['shortReason']);
        $requestReason  = stripslashes($_POST['reason']);
    }

    /*
     * Preg Replace strings.
     */
    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
    $string_exp = "/^[A-Za-z .'-]+/";
    if(!preg_match($email_exp,$employeeEmail)) {
        $error_message .= 'The email address you entered does not appear to be valid.<br />';
    }
    if(!preg_match($string_exp,$employeeName)) {
        $error_message .= 'The name you entered does not appear to be valid.<br />';
    }
    if(strlen($subject) <= 2) {
        $error_message .= 'The reason you entered does not appear to be valid text.<br />';
    }
    if(strlen($error_message) > 0) {
        died($error_message);
    }

}

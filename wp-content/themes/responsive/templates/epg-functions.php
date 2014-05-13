<?php
/**
 * User: Chris Gerber
 * Date: 5/12/14
 */

include_once ( ABSPATH . '/wp-includes/class-phpmailer.php' );

class epg_phpmailer extends phpmailer {

    /** Clean the "From" address */
    public function from_address($string) {
        $this->From = $string;
        $bad = array("content-type","bcc:","to:","cc:","href");
        return str_replace($bad,"",$this->From);
    }

    /** Creates the full email subject */
    public function email_subject ($subject, $issuetype, $realm) {
        $subject_line = '';

        /** Default Subject / Title */
        if ( strlen($subject) > 0) {
            $subject_line = $subject;
        }

        /** Set the Issue Label */
        $subject_line = $this->issue_type_label($issuetype, $subject_line);

        /** Set type label */
        if (isset($realm)) {
            $subject_line .= ' #' . $realm;
        }

        /** If string isn't blank, prepare for title */
        if (strlen($subject) > 1) {
            $this->Subject = stripslashes($subject);
        }

        /** Otherwise, return failure */
        return FALSE;
    }

    protected function issue_type_label($issuetype, $subject_line) {
        /** Determines the label via $issuetype */
        switch ($issuetype) :
            case 'Bug':
                $subject_line .= ' #Bug/Issue';
                break;

            case 'Issue':
                $subject_line .= ' #Bug/Issue';
                break;

            case 'Request':
                $subject_line .= ' #Request/Idea';
                break;

            case 'Idea':
                $subject_line .= ' #Request/Idea';
                break;

            default:
                $subject_line .= '';
                break;

        endswitch;

        return $subject_line;
    }


    public function multiple_attachments($files) {
        foreach ( $files['file']['tmp_name'] as $key => $val ) {
            $name = $files['file']['name'][$key];
            $path = $val;

            $this->AddAttachment($path, $name);
        }
    }
}

function get_include_contents($filename, $data = array()) {
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
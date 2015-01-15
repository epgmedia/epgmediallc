<?php
/**
 * Time-Off Request Form Email
 *
 * Form to send an email to necessary people for Time-Off request
 *
 * @function Send emails to TimeOff@epgmediallc.com, Supervisor, and self
 */
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

?>
<p>
    Form details below.
</p>

<table width="600" align="center" cellpadding="10" style="border-top:1px solid black;border-bottom:1px solid black;">

    <tr>

        <td align="left">

            <h1>
                Time-Off Request Form
            </h1>

            <p>
                Date submitted: <?php echo $_POST['date_submitted']; ?>
                <br />
            </p>

            <h2>
                From:
            </h2>

            <table border="0" cellpadding="2">
                <tr>
                    <td align="left">
                        Employee Name:
                    </td>
                    <td align="left">
                        <?php echo $_POST['employee']; ?>
                    </td>
                </tr>
                <tr>
                    <td align="left">
                        Email:
                    </td>
                    <td align="left">
                        <?php echo $_POST['email']; ?>
                    </td>
                </tr>
                <tr>
                    <td align="left">
                        Date submitted:
                    </td>
                    <td align="left">
                        <?php echo $_POST['date_submitted']; ?>
                    </td>
                </tr>
            </table>

            <p>
                Requesting <?php echo $_POST['requesting']; ?> hours of <?php echo $_POST['pay_type']; ?> time off.
            </p>

            <p>
                Beginning <?php echo $_POST['datefrom']; ?> to <?php echo $_POST['dateto']; ?>.
            </p>

            <h2>
                The reason for this request:
            </h2>

            <p>
                <?php echo stripslashes($_POST['reason']); ?>
            </p>

        </td>

    </tr>

</table>

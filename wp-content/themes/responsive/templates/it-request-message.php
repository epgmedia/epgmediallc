<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
?>

<table width="600" align="center" cellpadding="10" style="border-top:1px solid black;border-bottom:1px solid black;">

    <tr>

        <td align="left">

            <h1>
                IT Request Form
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
                        <p>Employee Name:</p>
                    </td>
                    <td align="left">
                        <p><?php echo $_POST['employee']; ?></p>
                    </td>
                </tr>
                <tr>
                    <td align="left">
                        <p>Email:</p>
                    </td>
                    <td align="left">
                        <p><?php echo $_POST['email']; ?></p>
                    </td>
                </tr>
                <tr>
                    <td align="left">
                        <p>Phone Number:</p>
                    </td>
                    <td align="left">
                        <p><?php echo $_POST['phoneNumber']; ?></p>
                    </td>
                </tr>
                <tr>
                    <td align="left">
                        <p>Office Location:</p>
                    </td>
                    <td align="left">
                        <p><?php echo $_POST['location']; ?></p>
                    </td>
                </tr>
                <tr>
                    <td align="left">
                        <p>Computer Type:</p>
                    </td>
                    <td align="left">
                        <p><?php echo $_POST['computerType']; ?></p>
                    </td>
                </tr>
                <tr>
                    <td align="left">
                        <p>Supervisor:</p>
                    </td>
                    <td align="left">
                        <p><?php echo $_POST['supervisor']; ?></p>
                    </td>
                </tr>
            </table>

            <h2>
                The reason for this request:
            </h2>

            <p>
                <?php echo stripslashes($_POST['reason']); ?>
            </p>

        </td>

    </tr>

</table>
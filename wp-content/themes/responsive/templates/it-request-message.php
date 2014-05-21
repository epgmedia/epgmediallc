<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
?>

<table width="600" align="center" cellpadding="10" style="border-top:1px solid black;border-bottom:1px solid black;">
    <tr>
        <td align="left">
            <h3>
                IT Request Form
            </h3>
            <p>
                Date submitted: <?php echo $_POST['date_submitted']; ?>
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
                        <p>Email:</p>
                    </td>
                    <td align="left">
                        <?php echo $_POST['email']; ?>
                    </td>
                </tr>
                <tr>
                    <td align="left">
                        Phone Number:
                    </td>
                    <td align="left">
                        <?php echo $_POST['phoneNumber']; ?>
                    </td>
                </tr>
                <tr>
                    <td align="left">
                        Office Location:
                    </td>
                    <td align="left">
                        <?php echo $_POST['location']; ?>
                    </td>
                </tr>
                <tr>
                    <td align="left">
                        Computer Type:
                    </td>
                    <td align="left">
                        <?php echo $_POST['computerType']; ?>
                    </td>
                </tr>
                <tr>
                    <td align="left">
                    	Supervisor:
                    </td>
                    <td align="left">
                        <?php echo $_POST['supervisor']; ?>
                    </td>
                </tr>
            </table>
            <h2>
                Description:
            </h2>
            <p>
                <?php echo stripslashes($_POST['reason']); ?>
            </p>
        </td>
    </tr>
</table>
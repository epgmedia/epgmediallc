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
                Date submitted: <?php echo filter_input( INPUT_POST, 'date_submitted', FILTER_SANITIZE_SPECIAL_CHARS ); ?>
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
                        <?php echo filter_input( INPUT_POST, 'employee', FILTER_SANITIZE_SPECIAL_CHARS ); ?>
                    </td>
                </tr>
                <tr>
                    <td align="left">
                        <p>Email:</p>
                    </td>
                    <td align="left">
                        <?php echo filter_input( INPUT_POST, 'email', FILTER_SANITIZE_EMAIL ); ?>
                    </td>
                </tr>
                <tr>
                    <td align="left">
                        Phone Number:
                    </td>
                    <td align="left">
                        <?php echo filter_input( INPUT_POST, 'phoneNumber', FILTER_SANITIZE_SPECIAL_CHARS ); ?>
                    </td>
                </tr>
                <tr>
                    <td align="left">
                        Office Location:
                    </td>
                    <td align="left">
                        <?php echo filter_input( INPUT_POST, 'location', FILTER_SANITIZE_SPECIAL_CHARS ); ?>
                    </td>
                </tr>
                <tr>
                    <td align="left">
                        Computer Type:
                    </td>
                    <td align="left">
                        <?php echo filter_input( INPUT_POST, 'computerType', FILTER_SANITIZE_SPECIAL_CHARS ); ?>
                    </td>
                </tr>
                <tr>
                    <td align="left">
                    	Supervisor:
                    </td>
                    <td align="left">
                        <?php echo filter_input( INPUT_POST, 'supervisor', FILTER_SANITIZE_EMAIL ); ?>
                    </td>
                </tr>
            </table>
            <h2>
                Description:
            </h2>
            <p>
                <?php echo wpautop( esc_textarea( filter_input( INPUT_POST, 'reason' ) ) ); ?>
            </p>
        </td>
    </tr>
</table>
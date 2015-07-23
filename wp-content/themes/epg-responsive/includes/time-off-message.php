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
    Form details below.</p>

<table width="600" align="center" cellpadding="10" style="border-top:1px solid black;border-bottom:1px solid black;">
    <tr>
        <td align="left">
            <h1>Time-Off Request Form</h1>
            <p>
                Date submitted: <?php _e( filter_input( INPUT_POST, 'date_submitted', FILTER_SANITIZE_SPECIAL_CHARS ) ); ?>
                <br /></p>
            <h2>From:</h2>
            <table border="0" cellpadding="2">
                <tr>
                    <td align="left">
                        Employee Name:
                    </td>
                    <td align="left">
                        <?php _e( filter_input( INPUT_POST, 'employee', FILTER_SANITIZE_SPECIAL_CHARS ) ); ?>
                    </td>
                </tr>
                <tr>
                    <td align="left">
                        Email:
                    </td>
                    <td align="left">
                        <?php _e( filter_input( INPUT_POST, 'email', FILTER_SANITIZE_EMAIL ) ); ?>
                    </td>
                </tr>
                <tr>
                    <td align="left">
                        Date submitted:
                    </td>
                    <td align="left">
                        <?php _e( filter_input( INPUT_POST, 'date_submitted', FILTER_SANITIZE_SPECIAL_CHARS ) ); ?>
                    </td>
                </tr>
            </table>
            <p>
                Requesting <?php _e( filter_input( INPUT_POST, 'requesting', FILTER_SANITIZE_SPECIAL_CHARS ) ); ?>
                hours of <?php _e( filter_input( INPUT_POST, 'pay_type', FILTER_SANITIZE_SPECIAL_CHARS ) ); ?> time off.</p>
            <p>
                Beginning <?php _e( filter_input( INPUT_POST, 'datefrom', FILTER_SANITIZE_SPECIAL_CHARS ) ); ?>
                to <?php _e( filter_input( INPUT_POST, 'dateto', FILTER_SANITIZE_SPECIAL_CHARS ) ); ?>.</p>
            <h2>The reason for this request:</h2>
            <p>
                <?php wpautop( esc_html( filter_input( INPUT_POST, 'reason' ) ) ); ?></p>
        </td>
    </tr>
</table>

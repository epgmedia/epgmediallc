<?php

    /*
     * Template Name: Background-Wallpaper Example
     */

?>
<!DOCTYPE html>
<html class="no-js">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Background Wallpaper Example</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body style="width:1130px; height:auto; margin:0 auto; padding:0;">

        <div style="margin:0; padding:0;background-size:cover;background-image: url('/wp-content/uploads/2014/02/background.gif');position:fixed;top:0;left:0;width:100%;height:100%;background-position: 50% 0%;">
            <a href="?message=AdClick" target="_blank">
                <img src="/wp-content/uploads/2014/02/blank.gif" style="width:100%;height:100%;" />
            </a>
        </div>

        <!-- Add your site or application content here -->
        <div style="width:960px; height:1600px; background-color:gray; margin:0; padding:0; text-align:center;position: relative;opacity:0.7;border:4px solid black;color:white;">
            <h1 style="Padding:10px;">Body and Sidebar Area</h1>
            <p style="Padding:10px;">This area scrolls while the background is fixed.</p>
        </div>
    </body>
</html>

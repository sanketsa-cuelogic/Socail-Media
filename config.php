<?php
// Define the needed keys
$strConsumerKey = "Your API Key";
$strConsumerSecret = "Your API Secret Key";
// The callback URL is the script that gets called after the user authenticates with tumblr
// In this example, it would be the included callback.php
$strCallbackUrl = "http://localhost/tumblrdemo/callback.php";

define('CONSUMER_KEY', $strConsumerKey);
define('CONSUMER_SECRET', $strConsumerSecret);
define('CALLBACK', $strCallbackUrl);
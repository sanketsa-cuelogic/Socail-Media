<?php
// This script is a simple example of how to send a user off to authentication using Tumblr's OAuth
// Start a session.  This is necessary to hold on to  a few keys the callback script will also need
session_start();

// Include the TumblrOAuth library
include_once('config.php');
include_once('API/tumblroauth.php');

// Let's begin.  First we need a Request Token.  The request token is required to send the user to Tumblr's login page.
// Create a new instance of the TumblrOAuth library.
$ObjTumAuth = new TumblrOAuth(CONSUMER_KEY, CONSUMER_SECRET);

// Ask Tumblr for a Request Token.  Specify the Callback URL here too (although this should be optional)
$objArrRequest = $ObjTumAuth->getRequestToken(CALLBACK);

// Store the request token and Request Token Secret as out callback.php script will need this
$_SESSION['request_token'] = $strToken = $objArrRequest['oauth_token'];
$_SESSION['request_token_secret'] = $objArrRequest['oauth_token_secret'];

// Check the HTTP Code.  It should be a 200 (OK), if it's anything else then something didn't work.
switch ($ObjTumAuth->http_code) {
    case 200:
        // Ask Tumblr to give us a special address to their login page
        $strUrl = $ObjTumAuth->getAuthorizeURL($strToken);
        // Redirect the user to the login URL given to us by Tumblr
        header('Location: ' . $strUrl);
        // That's it for our side.  The user is sent to a Tumblr Login page and asked to authroize our app.  After that, Tumblr sends the user back to our
        // Callback URL (callback.php) along with some information we need to get an access token.
        break;
    default:
        // Give an error message
        echo 'Could not connect to Tumblr. Refresh the page or try again later.';
        exit;
}

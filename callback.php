<?php

include_once('clsTumblr.php');
//Creating object
$objCls = new ClsTumblerAPI;
//Get Oauth Token
$objCls->fnGetOauthToken();

// Get User Information
$objCls->fnGetUserInformation();

// Get Profile Image
$objCls->fnGetProfileImage();

// Get User Blog Feed
$objCls->fnGetUserBlogFeed(1);

//Post Test or Image On Tumblr Blog 
$objCls->fnPostMessageOnBlog(2);

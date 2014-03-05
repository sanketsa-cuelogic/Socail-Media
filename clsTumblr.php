<?php
// Start a session, load the library
session_start();
include_once('config.php');
include_once('API/tumblroauth.php');
class ClsTumblerAPI
{
    private $strConsumerKey;
    private $strConsumerSecret;
    private $strOauthKey;
    private $strOauthSecret;
    private $objTumblrOauth;
    private $strUserBlog;

    //Constructor of class
    function __construct()
    {
        $this->strConsumerKey = CONSUMER_KEY;
        $this->strConsumerSecret = CONSUMER_SECRET;
    }

    /**
    Function getting Oauth Token
     */
    public function fnGetOauthToken()
    {
        $objTumbOuth = new TumblrOAuth($this->strConsumerKey, $this->strConsumerSecret, $_SESSION['request_token'], $_SESSION['request_token_secret']);
        // Ok, let's get an Access Token. We'll need to pass along our oauth_verifier which was given to us in the URL.
        $arrAccessToken = $objTumbOuth->getAccessToken($_REQUEST['oauth_verifier']);
        // We're done with the Request Token and Secret so let's remove those.
        $this->strOauthKey = $arrAccessToken['oauth_token'];
        $this->strOauthSecret = $arrAccessToken['oauth_token_secret'];
        /*unset($_SESSION['request_token']);
        unset($_SESSION['request_token_secret']);*/

        // Start a new instance of TumblrOAuth, overwriting the old one .
        // This time it will need our Access Token and Secret instead of our Request Token and Secret
        $this->objTumblrOauth = new TumblrOAuth($this->strConsumerKey, $this->strConsumerSecret, $this->strOauthKey, $this->strOauthSecret);
    }

    /** Getting user information
     */
    public function fnGetUserInformation()
    {
        //API Function For Getting User Information
        $objArrUserInfo = $this->objTumblrOauth->get('user/info');
        if (is_object($objArrUserInfo->response)) {
            //array of all users blogs
            $arrBLogs = $objArrUserInfo->response->user->blogs;
            foreach ($arrBLogs as $arrBl) {
                //echo $this->strUserBlog;
                //BLog Url
                $this->strUserBlog = trim(str_replace('/', '', str_replace('http://', '', $arrBl->url)));
            }
            //foreach
        }
        //if
    }//end function

    /**
     * Function for Getting Tumblr Profile Image
     */
    public function fnGetProfileImage()
    {
        //You can get a blog's avatar in 9 different sizes. The default size is 64x64.
        $strPhotoUrl = 'http://api.tumblr.com/v2/blog/' . $this->strUserBlog . '/avatar/512';
        echo 'Profile Image : <br> <img src="' . $strPhotoUrl . '">';
    }

    // Public Profile

    /**
     * Function User Blog Feed
     *
     * @param int $intPage
     */
    public function fnGetUserBlogFeed($intPage = 1)
    {
        $intStart = 0;
        $intLimit = 10;

        if ($intPage > 1) {
            $intStart = ($intPage - 1) * $intLimit;
        }

        $arrParam = array(
            'offset' => $intStart,
            'limit' => $intLimit,
            'api_key' => $this->strConsumerKey // App_key
        );
        //Url For Post Message
        $strBlogUrl = "blog/" . $this->strUserBlog . "/posts"; // Call post method
        $arrProfileUpdates = $this->objTumblrOauth->get($strBlogUrl, $arrParam);

        //Checking if post exist or not
        if (is_array($arrProfileUpdates->response->posts)) {

            foreach ($arrProfileUpdates->response->posts as $arrPosts) {
                //echo "<pre>";print_r($arrPosts);
            } //foreach

        }
        // if

    }

    /**
     * Function for Post Message on Tumblr Blog
     *
     * @param $intFlag
     */
    public function fnPostMessageOnBlog($intFlag)
    {
        //format - if body or caption contains any HTML tag
        if ($intFlag == 1) {
            //Post Text Message
            $arrMessage = array('type' => 'regular', 'title' => 'Testing ', 'body' => 'Details', 'format' => 'html');
        } else {
            //Post Photo with caption
            $strPhotoPath = 'http://4images.in/wp-content/uploads/2013/12/Siberian-Tiger-Running-Through-Snow-Tom-Brakefield-Getty-Images-200353826-001.jpg';

            $arrMessage = array('type' => 'photo', 'caption' => 'Photo details', 'source' => $strPhotoPath, 'format' => 'html');
        }
        $strPostUrl = "blog/" . $this->strUserBlog . "/post";

        $arrPost = $this->objTumblrOauth->post($strPostUrl, $arrMessage);

        // when posting message fail then  given error
        if ($arrPost->response->id == "") {
            $strErrorMessage = 'Message not posted';
        } else {
            $strErrorMessage = 'Message posted';
        }

        echo "<br>" . $strErrorMessage;
    }

}



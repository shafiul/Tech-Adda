<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Twitter
 *
 * @author Exam
 */
require_once "twitter/twitter.php";

abstract class Twitter {

    //put your code here
    public static function GetStaticTweetButton() {
        $html =
                "<a href=\"http://twitter.com/share\" class=\"twitter-share-button\" data-count=\"none\">Tweet</a>
        <script type=\"text/javascript\" src=\"http://platform.twitter.com/widgets.js\"></script>";
        return $html;
    }

    public static function GetLoginButton() {
        global $IMAGE_URL, $USER_URL;

        @session_start();
        /* Build TwitterOAuth object with client credentials. */
        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
        /* Get temporary credentials. */
        $request_token = $connection->getRequestToken(OAUTH_CALLBACK);

        /* Save temporary credentials to session. */
        $_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
        $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
        $url = $connection->getAuthorizeURL($token);
        $imageUrl = IMAGE_URL;
        return "<div><a href='$url'><img id='tweetLoginButtonId' class='pointer' src='$imageUrl/loginTwitter.png' ></img></div></a>";
    }

    public static function Login() {
        @session_start();
        /* If the oauth_token is old redirect to the connect page. */
        if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
            $_SESSION['oauth_status'] = 'oldtoken';
            @session_start();
            session_destroy();
        }
        /* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

        /* Request access tokens from twitter */
        $access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

        /* Save the access tokens. Normally these would be saved in a database for future use. */
        $_SESSION['access_token'] = $access_token;

        /* Remove no longer needed request tokens */

        $oauthToken = $_SESSION['oauth_token'];
        $oauthSecreet = $_SESSION['oauth_token_secret'];

        //EchoPre($_SESSION);
        unset($_SESSION['oauth_token']);
        unset($_SESSION['oauth_token_secret']);

        if (200 == $connection->http_code) {
            /* The user has been verified and the access tokens can be saved for future use */
            $userInfo = $connection->get('account/verify_credentials');
            
            $dbUserInfo = App::getRepository('User')->getUesrByTwitterId($userInfo->id);
            //ViewHelper::NicePrint($userInfo);            
            //$userInfo->id;
            $twitterUserId = $dbUserInfo['twitter_id'];            
//            ViewHelper::NicePrint($dbUserInfo);
//            ViewHelper::NicePrint($userInfo);
//            exit;
            if(!empty($dbUserInfo['twitter_id']) &&
                    $dbUserInfo['twitter_id']!="") {
//                echo $userInfo->id;
//                echo $dbUserInfo['twitter_id'];
//                echo "1";
//                exit;
                //Already Registered Via Twitter....                
                $_SESSION['user'] = $dbUserInfo;                
            } else {                                
                //echo $userInfo->name;
                 App::getRepository('User')->createByName($userInfo->name,$userInfo->id);                 
                 $newUser = true;
            }

            return $newUser;
        } else {
            /* Save HTTP status for error dialog on connnect page. */
            session_start();
            session_destroy();
            return array();
        }
    }

}

?>

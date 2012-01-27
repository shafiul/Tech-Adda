<?php

class Login extends Controller {

    function index() {

        try {
            $loginType = $this->input('loginType', '', false, 'google');
            if($loginType == 'google')
                $this->LoginWithGoogle();
            else
                $this->LoginWithTwitter();
            
        } catch (ErrorException $e) {

            $this->msgExit('Oops! Something went wrong, please try again.', MSG_ERROR);
            // store into log
//            echo $e->getMessage();
        }


        // crate view
        $this->loadView('login');
    }

    function LoginWithGoogle() {
        $openid = new LightOpenID('localhost');

        if (!$openid->mode) {
            if ($_GET['page'] == 'login') {
                $openid->identity = 'https://www.google.com/accounts/o8/id';
                $openid->required = array('contact/email');
                $openid->optional = array('nickname');
                header('Location: ' . $openid->authUrl());
            }
        } elseif ($openid->mode == 'cancel') {
            $this->setMsg('User has cancelled authentication!', MSG_WARNING);
        } else {

            if ($openid->validate()) {

                $authData = $openid->getAttributes();

                $_SESSION['user']['openid'] = $openid->identity;
                $_SESSION['user']['email'] = $authData['contact/email'];
                $this->setMsg('<b>Successfully authenticated!</b>. Welcome ' . $authData['contact/email'], MSG_SUCCESS);

                $userRepository = App::getRepository('User');
                $newUser = false;

                // check if the user is already added
                if (!$userRepository->getUserByEmail($_SESSION['user']['email'])) {
                    // creating new user
                    $userRepository->create($_SESSION['user']['email']);
                    $newUser = true;
                }
                // getting the user

                $user = $userRepository->getUserByEmail($_SESSION['user']['email']);
                // authenticating - storing into session
                $_SESSION['user'] = $user;

                if ($newUser)
                    $this->msgExit('New user registration was successful! Please update your username', MSG_SUCCESS, '?page=username');
                else{   
                    $userName = $user['email'];
                    if($user['name']!="")
                        $userName = $user['name'];
                        
                    $this->msgExit('Welcome, ' . $userName, MSG_SUCCESS, '?page=home');
                }
            } else {
                $this->setMsg('User was not authenticated!', MSG_ERROR);
            }

            header('Location: ' . ViewHelper::url('', true));
        }
    }
    
    function LoginWithTwitter()
    {
//        ViewHelper::NicePrint($_POST);
//        ViewHelper::NicePrint($_SESSION);
        $status = Twitter::Login();
        $user = $_SESSION['user'];
        if($status){            
            $this->msgExit('New user registration was successful!', MSG_SUCCESS, '?page=username');
        }
        else{
            $this->msgExit('Welcome, ' . $user['name'], MSG_SUCCESS, '?page=home');
        }

    }

}

?>

<?php

/*
 * 
 * Author : Md. Ibrahim Rashid
 * Email : ibrahim12@gmail.com
 * 
 * 
 */

abstract class Security {

    public static function IsReCaptchaValid(){
         global $CAPTCHA_PRIVATE;
         $resp = recaptcha_check_answer ($CAPTCHA_PRIVATE,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);

          if (!$resp->is_valid) {
              
            // What happens when the CAPTCHA was entered incorrectly
            return "Verification code invalid. Try again.";
          } else {
            return true;
          }
    }
    
    public static function GetReCaptchaHtml()
    {
        global $CAPTCHA_PUBLIC;
        return recaptcha_get_html($CAPTCHA_PUBLIC);
    }
  
   
    
  
}

?>
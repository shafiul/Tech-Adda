<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Auth
 *
 * @author shafiul
 */
class Auth {
    //put your code here
    
    function validate($userType=''){
        if(isset($_SESSION['user'])){
            return $_SESSION['user'];
        }else{
            return false;
        }
    }
    
}

?>

<?php


class Error extends Controller{
    
    function index(){
        
        $this->msgExit('Oops, something went wrong! We have logged the error and working on it.
<br /><br />We are extremely sorry for the inconveniences.             
', MSG_ERROR, '?page=home');
        
    }
}


?>

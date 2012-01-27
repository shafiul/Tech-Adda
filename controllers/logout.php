<?php

class Logout extends Controller {

    function index() {
        unset($_SESSION['user']);
        $this->msgExit('You are no longer logged in!', MSG_INFO, '?page=home');
    }

}

?>

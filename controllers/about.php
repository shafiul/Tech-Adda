<?php

class About extends Controller {

    function index() {

        $data['js'] = array(
            'js/jquery-validate.js',
            'js/jquery-validate-extra.js',
            'js/jquery-tablesorter.js'
        );
        $this->loadView('about', $data);
    }

}

?>

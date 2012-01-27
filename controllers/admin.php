<?php

class Admin extends Controller {

    function index() {

        $data['sidebars'] = array('admin', 'addEvent');
        // Prepare view
        $this->loadView('admin/admin', $data);
    }

}

?>

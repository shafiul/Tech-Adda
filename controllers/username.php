<?php

class Username extends Controller {

    function index() {

        $user = $this->authenticateUser();

        if ($_POST) {
            $validator = new FormValidation($this);
            $validator->requiredElements = array('username');
            $result = $validator->prepare();

            $errors = array();

            if ($validator->error)
                $errors[] = $validator->error;

//            var_dump(strlen($result['username']));
            
            if (strlen($result['username']) > 25)
                $errors[] = 'Username should not exceed 25 characters';

            if (!empty($errors)) {
                $this->setMsg(implode('<br />', $errors), MSG_ERROR);
            } else {
                $userRep = App::getRepository('User');
                $dbResult = $userRep->updateUsername($result['username'], $user['user_id']);
                
                if ($dbResult){
                    $_SESSION['user']['name'] = $result['username'];
//                    var_dump($_SESSION['user']);
//                    exit();
                    $this->setMsg('Successfully updated your name.', MSG_SUCCESS);
                }
            }
        }



        $data;
//        $data['sidebars'] = array('admin', 'addEvent');
        // Prepare view
        $this->loadView('username', $data);
    }

}

?>

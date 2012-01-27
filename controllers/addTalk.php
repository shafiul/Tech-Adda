<?php

class AddTalk extends Controller {

    function index() {

//        ViewHelper::NicePrint($_POST);
//        ViewHelper::printAsArray($_POST);
        //---- validation-----
        $this->authenticateUser();


        $eventId;
        if (!empty($_POST)) {

            $_SESSION['POST'] = $_POST;
            // validate user input
            $validator = new FormValidation($this);
            $validator->requiredElements = array('title', 'summary', 'speaker', 'eventId');
            $validator->optionalElements = array('slideLink');
            $result = $validator->prepare();

            $eventId = $result['eventId'];



            //----------------------Validations Start---------------------------

            $errorMsg = array();

            if ($validator->error != NULL) {
                $errorMsg[] = $validator->error;
            } else {

                if (!$validator->IsInteger($result['eventId']))
                    $errorMsg[] = "Invalid Event.";

                if (!$validator->CheckSize($result['title'], 5, 180))
                    $errorMsg[] = "Invalid Title.Minimum 5 letter and maximmum 180 letter";

                if (!$validator->IsAlphaNumericDashSpaceComma($result['title']))
                    $errorMsg[] = "Invalid Title .Only numbers ,letters ,dash,space and comma are allowed ";

                if (!$validator->CheckSize($result['speaker'], 5, 40))
                    $errorMsg[] = "Invalid speaker.Minimum 5 letter and maximmum 40 letter";

                if (!$validator->IsAlphaSpace($result['speaker']))
                    $errorMsg[] = "Invalid speaker.Only letters and spaces are allowed ";

                if (!empty($result['slideLink']) && !$validator->IsWebsiteValid($result['slideLink']))
                    $errorMsg[] = "Please give valid Slide Link.";
            }



            //----------------------Validations End----------------------------

            $sucessUrl = '?page=event&id=' . $result['eventId'];
            if (!empty($errorMsg)) {
                $errorMsg = implode("<br />", $errorMsg);
                $this->setMsg($errorMsg, MSG_ERROR);
                //$this->msgExit($errorMsg, MSG_ERROR);
            } else {
                $result['event_id'] = $result['eventId'];
                unset($result['eventId']);
                $result['slide_link'] = $result['slideLink'];
                unset($result['slideLink']);


                $talkId = App::getRepository(TABLE_TALKS)->create($result);
                $this->msgExit('Successfully added a Talk!', MSG_SUCCESS, $sucessUrl);
            }
        } else {
            $eventId = $this->input('id');
        }

        $data['event'] = App::getRepository('Event')->getEventById($eventId);

        $data['js'] = array('js/jquery-validate.js', 'js/jquery-validate-extra.js');
        $data['talkAction'] = '?page=addTalk';
        $this->loadView('addTalk', $data);
    }

}

?>

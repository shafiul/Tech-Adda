<?php

class AddComment extends Controller {

    function index() {
//        ViewHelper::NicePrint($_POST);
//        ViewHelper::printAsArray($_POST);
//        exit;
        // validation
        $user = $this->authenticateUser();
        
        if (!empty($_POST)) {
            // validate user input

            $validator = new FormValidation($this);
            $validator->requiredElements = array('body','talkId');    
            $result = $validator->prepare();
            
            $errorMsg = array();
            
            //----------------------Validations Start---------------------------
            if($validator->error != NULL){
                $errorMsg[] = $validator->error;
            }else{           
                if (!$validator->IsInteger($result['talkId']))                    
                    $errorMsg[] = "Invalid Talk.";                
            }

            //----------------------Validations End----------------------------

            
            
            $sucessUrl = '?page=talk&id=' . $result['talkId'];
            if(!empty($errorMsg))
            {
                $errorMsg = implode("<br />",$errorMsg);
                //$this->setMsg($errorMsg, MSG_ERROR);
                $this->msgExit($errorMsg, MSG_ERROR);
            }else{            
                $data['talk_id'] = $result['talkId'];
                $data['body'] = $result['body'];
                $commentId = App::getRepository(TABLE_COMMENTS)->create($data);
                $this->msgExit('Successfully added a comment!', MSG_SUCCESS,$sucessUrl);
            }
            
        }        
        
        
        
        // Prepare View
        $data['comments'] = App::getRepository(TABLE_COMMENTS)->getCommentsByTalk($result['talkId']);        
        $data['js'] = array('js/jquery-validate.js', 'js/jquery-validate-extra.js');
        $this->loadView('talk', $data);
    }

}

?>

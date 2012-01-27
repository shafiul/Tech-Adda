<?php

class AddEvent extends Controller {

    function index() {
        // validation
        $this->authenticateUser();

        if (!empty($_POST)) {

            $_SESSION['POST'] = $_POST;
            // validate user input

            $validator = new FormValidation($this);
            $validator->requiredElements = array('title', 'category_ids', 'summary', 'location', 'href',
                'start_date', 'end_date');
//        $validator->optionalElements = array('summary');
            $result = $validator->prepare();
            

//            var_dump($result['category_ids']);
//            exit();

            if ($validator->error) {
                $this->setMsg($validator->error, MSG_ERROR);
            } else {

                // file validation
                $file = new Files();
                $file->name = 'logo';
                $file->targetDir = 'files';
                $file->ExtWhitelist = array('png', 'gif', 'jpg', 'jpeg');
                $file->targetFileName = rand();
                $file->sizeLimit = 1024 * 2 * 1024; // 2 MB
                $fileUploadResult = $file->upload();

//            var_dump($fileUploadResult);
//            exit();

                if (!$fileUploadResult[0]) {
                    $this->setMsg('File Upload Error: ' . $fileUploadResult[1], MSG_ERROR);
                } else {
                    if ($fileUploadResult[2])
                        $result['logo'] = $fileUploadResult[2];

                    $catsArr = $result['category_ids'];
                    unset($result['category_ids']);
                    
                    $eventId = App::getRepository('Event')->create($result);
                    // handle categories

                    $evCatRepo = App::getRepository('EventCategory');
                    $evCatRepo->insertAll($catsArr, $eventId);


                    $this->msgExit('Successfully added event!', MSG_SUCCESS, '?page=event&id=' . $eventId);
                }
            }
        }

        $data['categories'] = App::getRepository('Category')->getAllCategories();

        // Prepare View

        $data['js'] = array('js/jquery-validate.js', 'js/jquery-validate-extra.js');
        $this->loadView('addEvent', $data);
    }

}

?>

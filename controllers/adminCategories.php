<?php

class adminCategories extends Controller {

    function index() {

        $this->authenticateUser();

        $mode = $this->input('mode', '', false);

        switch ($mode) {
            case 'addCategory':
                $validator = new FormValidation($this);
                $validator->requiredElements = array('title');
                $result = $validator->prepare();

                $errors = array();

                if ($validator->error)
                    $errors[] = $validator->error;

                if (!$validator->IsAlphaNumericDashComma($result['title']))
                    $errors[] = 'Invalid Character!';

                if (!empty($errors)) {
                    $this->setMsg(implode('<br />', $errors), MSG_ERROR);
                } else {
                    $catRep = App::getRepository('Category');
                    $dbResult = $catRep->create(array(
                        'title' => $result['title']
                            ));

                    if ($dbResult === false)
                        $this->setMsg('The category already exists!', MSG_ERROR);
                    else
                        $this->setMsg('New category created successfully!', MSG_SUCCESS);
                }

                break;
            case 'deleteCategory':


                $validator = new FormValidation($this);
                $validator->requiredElements = array('categoryId');
                $result = $validator->prepare();

                $errors = array();

                if ($validator->error)
                    $errors[] = $validator->error;

                if (!$validator->IsInteger($result['categoryId']))
                    $errors[] = 'Invalid Category!';

                if (!empty($errors)) {
                    $this->setMsg(implode('<br />', $errors), MSG_ERROR);
                } else {
                    $catRep = App::getRepository('Category');
                    $dbResult = $catRep->delete(array(
                        'category_id' => $result['categoryId']
                            ));

                    if ($dbResult)
                        $this->setMsg('Successfully deleted the category.', MSG_SUCCESS);
                }

                break;
        }

        $data['sidebars'] = array('admin', 'addEvent');
        // Prepare view
        $this->loadView('admin/categories', $data);
    }

}

?>

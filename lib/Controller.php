<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Controller
 *
 * @author shafiul
 */
class Controller {

    private $viewLoaded = false;
    private $authentication = null;

    function loadView($viewfile, $data = array()) {

        $pagePrefix = APPPATH . '/pages/';

        $viewPath = $pagePrefix . 'views/' . $viewfile . '.php';
        if (!file_exists($viewPath)) {
            //trigger_error('View not found!', E_USER_ERROR);            
            App::logMsg($viewPath . ' view not found!', "Error");
            $this->msgExit("Something went wrong.", E_USER_ERROR);
        }
        $this->viewLoaded = true;

        //adding default sidebar content        
        $data['categories'] = App::getRepository('Category')->getAllCategories();

        //
        foreach ($data as $key => $val) {
            $$key = $val;
        }

        //sidebar blocks
//        $sidebars;
        if (isset($sidebars)) {
            $sidebars = (is_array($sidebars) ? $sidebars : array($sidebars));
        } else {
            $sidebars = array('categories', 'addEvent');
        }

        //Css
//        $css;
        if (isset($css)) {
            $css = (is_array($css) ? $css : array($css));
        } else {
            $css = array();
        }

        //JS
//        $js;
        if (isset($js)) {
            $js = (is_array($js) ? $js : array($js));
        } else {
            $js = array();
        }

        //        $js;
        if (isset($afterjs)) {
            $afterjs = (is_array($afterjs) ? $afterjs: array($afterjs));
        } else {
            $afterjs = array();
        }



        //loading header view
        require_once $pagePrefix . 'views/header.php';

        //Loading main content
        require_once $viewPath;


        //loading sidebar
        if (count($sidebars) > 0) {
            echo '
                </div>
                <div  class="span4">';
            foreach ($sidebars as $sideblock) {
                require_once $pagePrefix . 'sidebars/' . $sideblock . '.php';
            }
        }


        //loading footer view
        require_once $pagePrefix . 'views/footer.php';
    }

    function isViewLoaded() {
        return $this->viewLoaded;
    }

    /**
     * Sets a flash message for displaying
     * @param type $msg
     * @param type $type 
     */
    function setMsg($msg, $type) {
        $_SESSION['flash']['type'] = $type;
        $_SESSION['flash']['message'] = $msg;
    }

    /**
     * Sets flash messsage and redirects to an url
     * @param type $msg
     * @param type $type
     * @param type $urlToRedirect 
     */
    function msgExit($msg, $type, $urlToRedirect='') {
//        var_dump($_SERVER['HTTP_REFERER']);
//        var_dump(ViewHelper::url('home', true));
//        exit();
        if (empty($urlToRedirect))
            $urlToRedirect = (isset($_SERVER['HTTP_REFERER'])) ? ($_SERVER['HTTP_REFERER']) : (ViewHelper::url('', true));
        else
            $urlToRedirect = ViewHelper::url($urlToRedirect, true);
        $this->setMsg($msg, $type);

        header('Location: ' . $urlToRedirect);
        exit();
    }

    /**
     * Returns user submitted input, validated
     * @param type $data
     * @param type $length
     * @param type $required
     * @param type $defaultValue
     * @return type 
     */
    public function input($data, $length="", $required = true, $defaultValue = "") {
        if ($required) {
            if (empty($_GET["$data"])) {
                $this->msgExit('Required field <b>' . $data . '</b> is empty!', MSG_ERROR);
            } else if (!empty($length) && strlen($_GET["$data"]) > $length) {
                $this->msgExit('Required field <b>' . $data . '</b> is too large! (Max <b>' . $length . '</b> chars allowed).', MSG_ERROR);
            }
        }
        $return = (empty($_GET[$data])) ? ($defaultValue) : (htmlspecialchars($_GET["$data"]));
        return $return;
    }

    /**
     * Wrapper to authentication
     * @param type $type
     * @param type $redirectIfNotLoggedIn
     * @return type 
     */
    public function authenticateUser($type='', $redirectIfNotLoggedIn=true) {
        $this->authentication = new Auth();

        if (($validationResult = $this->authentication->validate()) === false) {
            if ($redirectIfNotLoggedIn) {
                $this->msgExit('Access Denied: please log in to continue!', MSG_ERROR);
            }
        }
        return $validationResult;
    }

}

?>

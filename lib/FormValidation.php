<?php

/*
 * Author : Md. Ibrahim Rashid
 * Email : ibrahim12@gmail.com
 * 
 * 
 * 
 */

class FormValidation {

    /**
     *
     * @param type $elementsName 
     */
    private $controller = null;
//    private $xss = null;
    public $requiredElements = array();
    public $optionalElements = array();
    public $result = array();
    
    public $error = null;
    public $errorStringsJoiner = '<br />';

    public function __construct($controller=null) {
        $this->controller = $controller;
    }

    /**
     * Prepare all form elements
     */
    public function prepare($preventXSS=true) {
        $this->error = null;
        $errors = array();
        foreach ($this->requiredElements as $i) {
            if (empty($_POST[$i])){
//                die('there');
                $errors[] = 'Field <b>' . $i . '</b> is required!';
            }else{
//                die('here');
                $this->result[$i] = Xss::Clean($_POST[$i]);
            }
        }
        if(!empty($errors))
            $this->error = implode($this->errorStringsJoiner, $errors);
        foreach ($this->optionalElements as $i) {
            $this->result[$i] = Xss::Clean($_POST[$i]);
        }
        return $this->result;
    }
    
//    public function prepare($preventXSS=true) {
//        foreach ($this->requiredElements as $i) {
//            if (empty($_POST[$i]))
//                $this->controller->msgExit('Required field <b>' . $i . '</b> not found!', MSG_ERROR);
//            $this->result[$i] = Xss::Clean($_POST[$i]);
//        }
//        foreach ($this->optionalElements as $i) {
//            $this->result[$i] = Xss::Clean($_POST[$i]);
//        }
//        return $this->result;
//    }

    public function DeepTrim($var) {
        if (is_array($var)) {
            foreach ($var as $key => $val) {
                $var[$key] = FormValidation::DeepTrim($val);
            }
            return $var;
        }
        return trim($var);
    }

    public function AllTrim($test) {
        return trim(ereg_replace(' +', ' ', $test));
    }

    public function AddDeepSafeSlashes($array) {
        if (!get_magic_quotes_gpc()) {
            if (is_array($array)) {
                foreach ($array as $key => $val) {
                    $array[$key] = FormValidation::AddDeepSafeSlashes($val);
                }
                return $array;
            }
            else
                return addslashes($array);
        }
        return $array;
    }

    public function CleanFormData($array) {
        $array = Xss::Clean($array);
        $array = FormValidation::DeepTrim($array);
        $array = FormValidation::AddDeepSafeSlashes($array);
        return $array;
    }

    public function DeepStripSlashes($array) {

        if (is_array($array)) {
            foreach ($array as $key => $val) {
                $array[$key] = FormValidation::DeepStripSlashes($val);
            }
            return $array;
        }
        else
            return stripslashes($array);
    }

    public function CheckSize($str, $min, $max) {
        $len = strlen($str);
//        var_dump($len);
//        exit();
        if ($len < $min || $len > $max)
            return false;
        return true;
    }

    public function IsEmailValid($email) {

        if (preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,30}$/", $email))
            return true;
        return false;
    }

    public function IsWebsiteValid($website) {

        if (preg_match("#^(http|https)://([A-Z0-9][A-Z0-9_-]*(?:.[A-Z0-9][A-Z0-9_-]*)+):?(d+)?/?#i", $website))
            return true;
        return false;
    }

    public function IsPhoneValid($phone) {

        if (preg_match("/^\([0-9][0-9]\) [0-9][0-9][0-9][0-9]-[0-9][0-9][0-9][0-9]$/", $phone))
            return true;
        return false;
    }

    public function CheckUsername($username) {

        if (preg_match("/^[a-zA-Z0-9_.-]+$/", $username))
            return true;
        return false;
    }

    public function NonEmptyValidate($array) {
        foreach ($array as $value) {
            if (!isset($_POST[$value]) || is_null($_POST[$value]) || $_POST[$value] == "")
                return $value . " cannot be empty.";
        }
        return 1;
    }

    /*     * Below Functions are taken from 
     * * ----------------------------------
     * * ExpressionEngine Dev Team CI Package
     * * Copyright (c) 2008 - 2011, EllisLab, Inc.
     * * http://codeigniter.com/user_guide/license.html
     * ------------------------------------
     */

    // --------------------------------------------------------------------

    /**
     * Minimum Length
     *
     * @access  public
     * @param   string
     * @param   value
     * @return  bool
     */
    public function IsMinLength($str, $val) {
        if (preg_match("/[^0-9]/", $val)) {
            return FALSE;
        }

        if (function_exists('mb_strlen')) {
            return (mb_strlen($str) < $val) ? FALSE : TRUE;
        }

        return (strlen($str) < $val) ? FALSE : TRUE;
    }

// --------------------------------------------------------------------

    /**
     * Max Length
     *
     * @access  public
     * @param   string
     * @param   value
     * @return  bool
     */
    public function IsMaxLength($str, $val) {
        if (preg_match("/[^0-9]/", $val)) {
            return FALSE;
        }

        if (function_exists('mb_strlen')) {
            return (mb_strlen($str) > $val) ? FALSE : TRUE;
        }

        return (strlen($str) > $val) ? FALSE : TRUE;
    }

// --------------------------------------------------------------------

    /**
     * Exact Length
     *
     * @access  public
     * @param   string
     * @param   value
     * @return  bool
     */
    public function IsExactLength($str, $val) {
        if (preg_match("/[^0-9]/", $val)) {
            return FALSE;
        }

        if (function_exists('mb_strlen')) {
            return (mb_strlen($str) != $val) ? FALSE : TRUE;
        }

        return (strlen($str) != $val) ? FALSE : TRUE;
    }


// --------------------------------------------------------------------

    /**
     * Alpha
     *
     * @access  public
     * @param   string
     * @return  bool
     */
    public function IsAlpha($str) {
        return (!preg_match("/^([a-z])+$/i", $str)) ? FALSE : TRUE;
    }

// --------------------------------------------------------------------

    /**
     * AlphaSpace
     *
     * @access  public
     * @param   string
     * @return  bool
     */
    public function IsAlphaSpace($str) {
        return (!preg_match("/^([a-z\ ])+$/i", $str)) ? FALSE : TRUE;
    }
    

// --------------------------------------------------------------------

    /**
     * Alpha-numeric Space
     *
     * @access  public
     * @param   string
     * @return  bool
     */
    public function IsAlphaNumericSpace($str) {
        return (!preg_match("/^([a-z0-9\ ])+$/i", $str)) ? FALSE : TRUE;
    }

// --------------------------------------------------------------------

    /**
     * Alpha-numeric Dot Space
     *
     * @access  public
     * @param   string
     * @return  bool
     */
    public function IsAlphaNumericDotSpace($str) {
        return (!preg_match("/^([a-z0-9\ .])+$/i", $str)) ? FALSE : TRUE;
    }

// --------------------------------------------------------------------
    /**
     * Alpha-numeric 
     *
     * @access  public
     * @param   string
     * @return  bool
     */
    public function IsAlphaNumeric($str) {
        return (!preg_match("/^([a-z0-9])+$/i", $str)) ? FALSE : TRUE;
    }

// --------------------------------------------------------------------

    /**
     * Alpha-numeric with underscores and dashes
     *
     * @access  public
     * @param   string
     * @return  bool
     */
    public function IsAlphaNumericDash($str) {
        return (!preg_match("/^([-a-z0-9_-])+$/i", $str)) ? FALSE : TRUE;
    }

// --------------------------------------------------------------------

    /**
     * Alpha-numeric with underscores , dashes amd coma
     *
     * @access  public
     * @param   string
     * @return  bool
     */
    public function IsAlphaNumericDashComma($str) {
        return (!preg_match("/^([-a-z0-9_,-])+$/i", $str)) ? FALSE : TRUE;
    }

// --------------------------------------------------------------------

    /**
     * Alpha-numeric with underscores , dashes , spaces amd coma
     *
     * @access  public
     * @param   string
     * @return  bool
     */
    public function IsAlphaNumericDashSpaceComma($str) {
        return (!preg_match("/^([-a-z0-9\ _,-])+$/i", $str)) ? FALSE : TRUE;
    }

// --------------------------------------------------------------------

    /**
     * Alpha-numeric with underscores , dashes , spaces ,%,amd coma
     *
     * @access  public
     * @param   string
     * @return  bool
     */
    public function IsAlphaNumericDashSpaceCommaPercent($str) {
        return (!preg_match("/^([-a-z0-9\ _%,-])+$/i", $str)) ? FALSE : TRUE;
    }

// --------------------------------------------------------------------

    /**
     * Alpha-numeric with underscores , dashes , underscore , dot
     *
     * @access  public
     * @param   string
     * @return  bool
     */
    public function IsAlphaNumericDotDashUnderscore($str) {
        return (!preg_match("/^([-a-z0-9_.-])+$/i", $str)) ? FALSE : TRUE;
    }

// --------------------------------------------------------------------

    /**
     * Numeric
     *
     * @access  public
     * @param   string
     * @return  bool
     */
    public function IsNumeric($str) {
        return (bool) preg_match('/^[\-+]?[0-9]*\.?[0-9]+$/', $str);
    }

// --------------------------------------------------------------------

    /**
     * Number
     *
     * @access  public
     * @param   string
     * @return  bool
     */
    public function IsNumber($str) {
        return (!preg_match("/^([0-9])+$/", $str)) ? FALSE : TRUE;
    }

// --------------------------------------------------------------------

    /**
     * Is Numeric
     *
     * @access    public
     * @param    string
     * @return    bool
     */
    public function IsNumericDefualt($str) {
        return (!is_numeric($str)) ? FALSE : TRUE;
    }

// --------------------------------------------------------------------

    /**
     * Integer
     *
     * @access  public
     * @param   string
     * @return  bool
     */
    public function IsInteger($str) {
        return (bool) preg_match('/^[\-+]?[0-9]+$/', $str);
    }

// --------------------------------------------------------------------

    /**
     * Is a Natural number  (0,1,2,3, etc.)
     *
     * @access  public
     * @param   string
     * @return  bool
     */
    public function IsNatural($str) {
        return (bool) preg_match('/^[0-9]+$/', $str);
    }

// --------------------------------------------------------------------

    /**
     * Is a Natural number, but not a zero  (1,2,3, etc.)
     *
     * @access  public
     * @param   string
     * @return  bool
     */
    public function IsNaturalNoZero($str) {
        if (!preg_match('/^[0-9]+$/', $str)) {
            return FALSE;
        }

        if ($str == 0) {
            return FALSE;
        }

        return TRUE;
    }

// --------------------------------------------------------------------

    /**
     * Valid Base64
     *
     * Tests a string for characters outside of the Base64 alphabet
     * as defined by RFC 2045 http://www.faqs.org/rfcs/rfc2045
     *
     * @access  public
     * @param   string
     * @return  bool
     */
    public function IsValidBase64($str) {
        return (bool) !preg_match('/[^a-zA-Z0-9\/\+=]/', $str);
    }

    // --------------------------------------------------------------------

    public function IsMinDate($date, $minDate) {
        if (strtotime($date) < strtotime($minDate))
            return false;
        return true;
    }

    // --------------------------------------------------------------------
    public function IsMaxDate($date, $maxDate) {
        if (strtotime($date) > strtotime($maxDate))
            return false;
        return true;
    }

}

?>
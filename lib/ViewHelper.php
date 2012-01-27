<?php

/**
 * ViewHelper
 *
 * Provides various helper method for working with views.
 *
 */
class ViewHelper {

    protected static $config;

    public static function setConfig($config) {
        self::$config = $config;
    }

    public static function config($key) {
        return self::$config->get($key);
    }
    
    public static function niceURL($path=''){
//        $path = '?page=my&id=1&cat=2';
        if((strpos($path, '?page=')) === 0){
            // replace
            $path = str_replace('?page=', '', $path);
        }
        $path = preg_replace('/&/', '?', $path, 1);
//        var_dump($path);
//        exit();
        return $path;
    }

    public static function url($path = '', $return = false) {
        if(NICE_URL_ENABLED && !empty ($path))
            $path = self::niceURL ($path);
        if ($return) {
            return self::$config->get('url.base') . $path;
        } else {
            echo self::$config->get('url.base') . $path;
        }
    }

    public static function alertMessage($content, $type = 'warning') {
        echo <<<MSG
        <div class="alert-message $type">
            <a class="close" href="#">×</a>
            <p>$content</p>
        </div>
MSG;
    }

    public static function flushMessage() {
        if (!empty($_SESSION['flash']['message'])) {
            $type = isset($_SESSION['flash']['type']) ? $_SESSION['flash']['type'] : 'warning';
            ViewHelper::alertMessage($_SESSION['flash']['message'], $type);
            unset($_SESSION['flash']);
        }
    }

    public static function formatDate($date) {
        return date("d M Y", strtotime($date));
    }

    public static function NicePrint($data) {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }

    public static function includeForm($form, $data = array()) {
        foreach ($data as $key => $value) {
            $$key = $value;
        }
        include APPPATH . '/pages/forms/' . $form . '.php';
    }

    public static function getInput($key, $method='post') {
        if ($method == 'post') {
            return isset($_POST[$key]) ? $_POST[$key] : "";
        } else if ($method == 'session') {
            return isset($_SESSION['POST'][$key]) ? $_SESSION['POST'][$key] : "";
        } else if ($method == 'get') {
            return isset($_GET[$key]) ? $_GET[$key] : "";
        }
    }

    public static function printAsArray($data) {
        $str = "array(";
        foreach ($data as $key => $val) {
            $str .= "'$key',";
        }
        $str = substr($str, 0, strlen($str) - 1);
        $str .= ");";
        echo $str;
    }
  
    public static function getPagination($numItems,$url,$perPage,$curPage=1) {        
        $paginationHtml = "<div class='pagination'><ul>";
        $numPages=ceil(floatval($numItems)/$perPage);                
        for($i=1;$i<=$numPages;++$i)
        {
            if($i==$curPage)
                $paginationHtml .= "<li class='active'><a href='javascript:void(0);'>$i</a></li>";                
            else
                $paginationHtml .= "<li ><a href=\"$url&pageNo=$i\">$i</a></li>";                
        }        
        
        $paginationHtml .= "</ul></div>";
        
        return $paginationHtml;
    }

}
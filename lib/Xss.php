<?php

/*
 * Developer : Md. Ibrahim Rashid
 * Email : ibrahim12@gmail.com
 * Adopted & Modified from 
 * 
 * 
 * ExpressionEngine Dev Team CI Package
 * Copyright (c) 2008 - 2011, EllisLab, Inc.
 * http://codeigniter.com/user_guide/license.html
 * 
 * 
 * 
 */

// Need to add CSRF protection 

abstract class Xss {

    public static $xssHash = "";
    /* never allowed, string replacement */
    public static $neverAllowedStr = array(
        'document.cookie' => '',
        'document.write' => '',
        '.parentNode' => '',
        '.innerHTML' => '',
        'window.location' => '',
        '-moz-binding' => '',
        '<!--' => '&lt;!--',
        '-->' => '--&gt;',
        '<![CDATA[' => '&lt;![CDATA['
    );

    /* never allowed, regex replacement */
    public static $neverAllowedRegex = array(
        "javascript\s*:" => '',
        "expression\s*(\(|&\#40;)" => '', // CSS and IE
        "vbscript\s*:" => '', // IE, surprise!
        "Redirect\s+302" => ''
    );
    public static $explodedWords = array(
        'javascript', 'expression', 'vbscript', 'script',
        'applet', 'alert', 'document', 'write', 'cookie', 'window'
    );


 

    // If the file being uploaded is an image, then we should have no problem with XSS attacks (in theory), but
    // IE can be fooled into mime-type detecting a malformed image as an html file, thus executing an XSS attack on anyone
    // using IE who looks at the image.  It does this by inspecting the first 255 bytes of an image.  To get around this
    // CI will itself look at the first 255 bytes of an image to determine its relative safety.  This can save a lot of
    // processor power and time if it is actually a clean image, as it will be in nearly all instances _except_ an
    // attempted XSS attack.
    public static function IsImageXssClean($tmpPath) {
        $file = "";
        if (($file = @fopen($tmpPath, 'rb')) === FALSE) // "b" to force binary
        {
            return FALSE; // Couldn't open the file, return FALSE
        }

        $openingBytes = fread($file, 256);
        fclose($file);

        // These are known to throw IE into mime-type detection chaos
        // <a, <body, <head, <html, <img, <plaintext, <pre, <script, <table, <title
        // title is basically just in SVG, but we filter it anyhow

        if ( ! preg_match('/<(a|body|head|html|img|plaintext|pre|script|table|title)[\s>]/i', $openingBytes))
        {
                return TRUE; // its an image, no "triggers" detected in the first 256 bytes, we're good
        }
        if (($data = @file_get_contents($file)) === FALSE)
        {
           return FALSE;
        }
    }

    public static function Clean($str, $isImage=false) {

        /*
         * Is the string an array?
         *
         */
        //echo  $str."<br />";
        
        if (is_array($str)) {
            while (list($key) = each($str)) {
                $str[$key] = Xss::Clean($str[$key]);
            }
            return $str;
        }
        
//Removes Invisible Characters                
//=========================================================================                
        
        $str = Xss::RemoveInvisibleChars($str);
        //echo "<br>Invisible Chars Removed : $str<br>";    
        
// Validates Entities in URLs        
//=========================================================================        

        $str = Xss::ValidateEntities($str);
        //echo "<br>Enitity Validated : $str<br>";    
        
// URL Decodes        
//=========================================================================                
        
        $str = Xss::UrlDecode($str);
        //echo "<br>Url Decoded : $str<br>";    
        
// Convert character entities to ASCII         
//=========================================================================                
        $str = Xss::ConvertCharEntitiesToASCII($str);
        //echo "<br>Chars2Entity : $str<br>";    
        
// Remove Invisible Characters Again!                 
//=========================================================================                
        
        $str = Xss::RemoveInvisibleChars($str);
        //echo "<br>RemoveInvisibleChars : $str<br>";    

// Convert all tabs to spaces        
//=========================================================================         
        
        $str = Xss::ConvertTabsToSpace($str);
        //echo "<br>Tabs2Space : $str<br>";    

//Capture converted string for later comparison                
//=========================================================================                        
        $convertedString = $str;
        
// Remove Strings that are never allowed        
//=========================================================================        

        $str = Xss::DoNeverAllowed($str);
        //echo "<br>RemoveNeverAllowedString : $str<br>";    
        
// Makes PHP tags safe        
//=========================================================================        
        
        $str = Xss::MakePhpTagSafe($str, $isImage);
        //echo "<br>MakePhpTagSafe : $str<br>";    

// Compact any exploded words        
//=========================================================================                
        
        $str = Xss::CompactExplodedWords($str);
        //echo "<br>CompactExplodedWords : $str<br>";    

// Remove disallowed Javascript in links or img tags
//=========================================================================                
        
        $str = Xss::RemoveDisallowedJsImgTags($str);
        //echo "<br>RemoveJsImgTags : $str<br>";    

// Remove evil attributes such as style, onclick and xmlns
//=========================================================================                
        
        $str = Xss::RemoveEvilAttributes($str, $isImage);
        //echo "<br>RemoveEvilAttr : $str<br>";    

// Sanitize naughty HTML elements        
//=========================================================================                
        
        $str = Xss::SanitizeNaughtyHtmlElements($str);
        //echo "<br>SanitizedNaughtyHtml : $str<br>";    

// Sanitize naughty scripting elements
//=========================================================================                
        
        $str = Xss::SanitizeNaughtyScriptingElements($str);
        //echo "<br>SanitizedNaughtyScript : $str<br>";    

// Final clean up        
//=========================================================================        
        
        // This adds a bit of extra precaution in case
        // something got through the above filters
        
        $str = Xss::DoNeverAllowed($str);
        //echo "<br>RemoveNeverAllowed : $str<br>";    

        

/*
 * Images are Handled in a Special Way
 * - Essentially, we want to know that after all of the character 
 * conversion is done whether any unwanted, likely XSS, code was found.  
 * If not, we return TRUE, as the image is clean.
 * However, if the string post-conversion does not matched the 
 * string post-removal of XSS, then it fails, as there was unwanted XSS 
 * code found and removed/changed during processing.
 */
//=========================================================================                
        
        if ($isImage === TRUE) {
            return ($str == $convertedString) ? TRUE : FALSE;
        }
//=========================================================================                
        
//      echo "<br>$str<br>";
        return $str;
    }
    
    
    
    // --------------------------------------------------------------------XSS HELPERS----------------------------------//

    /**
     * Sanitize Naughty HTML
     *
     * Callback function for xss_clean() to remove naughty HTML elements
     *
     * @param	array
     * @return	string
     */
    public static function SanitizeNaughtyHtml($matches) {
        // encode opening brace
        $str = '&lt;' . $matches[1] . $matches[2] . $matches[3];

        // encode captured opening or closing brace to prevent recursive vectors
        $str .= str_replace(array('>', '<'), array('&gt;', '&lt;'), $matches[4]);

        return $str;
    }

    /*
     * Remove Evil HTML Attributes (like evenhandlers and style)
     *
     * It removes the evil attribute and either:
     * 	- Everything up until a space
     * 		For example, everything between the pipes:
     * 		<a |style=document.write('hello');alert('world');| class=link>
     * 	- Everything inside the quotes 
     * 		For example, everything between the pipes:
     * 		<a |style="document.write('hello'); alert('world');"| class="link">
     *
     * @param string $str The string to check
     * @param boolean $is_image TRUE if this is an image
     * @return string The string with the evil attributes removed
     */

    public static function RemoveEvilAttributes($str, $isImage) {
        // All javascript event handlers (e.g. onload, onclick, onmouseover), style, and xmlns
        $evilAttributes = array('on\w*', 'style', 'xmlns');

        if ($isImage === TRUE) {
            /*
             * Adobe Photoshop puts XML metadata into JFIF images, 
             * including namespacing, so we have to allow this for images.
             */
            unset($evilAttributes[array_search('xmlns', $evilAttributes)]);
        }

        do {
            $str = preg_replace(
                    "#<(/?[^><]+?)([^A-Za-z\-])(" . implode('|', $evilAttributes) . ")(\s*=\s*)([\"][^>]*?[\"]|[\'][^>]*?[\']|[^>]*?)([\s><])([><]*)#i", "<$1$6", $str, -1, $count
            );
        } while ($count);

        return $str;
    }

    // --------------------------------------------------------------------

    /**
     * Filter Attributes
     *
     * Filters tag attributes for consistency and safety
     *
     * @param	string
     * @return	string
     */
    public static function FilterAttributes($str) {
        $out = '';

        if (preg_match_all('#\s*[a-z\-]+\s*=\s*(\042|\047)([^\\1]*?)\\1#is', $str, $matches)) {
            foreach ($matches[0] as $match) {
                $out .= preg_replace("#/\*.*?\*/#s", '', $match);
            }
        }

        return $out;
    }

    /**
     * JS Link Removal
     *
     * Callback function for xss_clean() to sanitize links
     * This limits the PCRE backtracks, making it more performance friendly
     * and prevents PREG_BACKTRACK_LIMIT_ERROR from being triggered in
     * PHP 5.2+ on link-heavy strings
     *
     * @param	array
     * @return	string
     */
    public static function JsLinkRemoval($match) {
        
        //EchoPre($match);
        //$match = StripSlashesDeep($match);
        $attributes = Xss::FilterAttributes(str_replace(array('<', '>'), '', $match[1]));                
        
        
        $match =  str_replace($match[1], 
                preg_replace("#href=\".*?(alert\(|alert&\#40;|javascript\:|livescript\:|mocha\:|charset\=|window\.|document\.|\.cookie|<script|<xss|base64\s*,)\"#si",
                        "", $attributes), $match[0]);
        
//        EchoPre($match);
//        EchoPre($attributes);
//        EchoPre($match[0]);
        return $match;
    }

    // --------------------------------------------------------------------

    /**
     * JS Image Removal
     *
     * Callback function for xss_clean() to sanitize image tags
     * This limits the PCRE backtracks, making it more performance friendly
     * and prevents PREG_BACKTRACK_LIMIT_ERROR from being triggered in
     * PHP 5.2+ on image tag heavy strings
     *
     * @param	array
     * @return	string
     */
    public static function JsImgRemoval($match) {
        $attributes = Xss::FilterAttributes(str_replace(array('<', '>'), '', $match[1]));

        return str_replace($match[1], preg_replace("#src=.*?(alert\(|alert&\#40;|javascript\:|livescript\:|mocha\:|charset\=|window\.|document\.|\.cookie|<script|<xss|base64\s*,)#si", "", $attributes), $match[0]);
    }

    /**
     * Compact Exploded Words
     *
     * Callback function for xss_clean() to remove whitespace from
     * things like j a v a s c r i p t
     *
     * @param	type
     * @return	type
     */
    public static function StripExplodedWordsWhiteSpaces($matches) {
        return preg_replace('/\s+/s', '', $matches[1]) . $matches[2];
    }

    /**
     * Do Never Allowed
     *
     * A utility function for xss_clean()
     *
     * @param 	string
     * @return 	string
     */
    public static function DoNeverAllowed($str) {
        foreach (Xss::$neverAllowedStr as $key => $val) {
            $str = str_replace($key, $val, $str);
        }

        foreach (Xss::$neverAllowedRegex as $key => $val) {
            $str = preg_replace("#" . $key . "#i", $val, $str);
        }

        return $str;
    }

    /**
     * HTML Entities Decode
     *
     * This function is a replacement for html_entity_decode()
     *
     * In some versions of PHP the native function does not work
     * when UTF-8 is the specified character set, so this gives us
     * a work-around.  More info here:
     * http://bugs.php.net/bug.php?id=25670
     *
     * NOTE: html_entity_decode() has a bug in some PHP versions when UTF-8 is the
     * character set, and the PHP developers said they were not back porting the
     * fix to versions other than PHP 5.x.
     *
     * @param	string
     * @param	string
     * @return	string
     */
    public static function EntityDecode($str, $charset='UTF-8') {
        if (stristr($str, '&') === FALSE)
            return $str;

        // The reason we are not using html_entity_decode() by itself is because
        // while it is not technically correct to leave out the semicolon
        // at the end of an entity most browsers will still interpret the entity
        // correctly.  html_entity_decode() does not convert entities without
        // semicolons, so we are left with our own little solution here. Bummer.

        if (function_exists('html_entity_decode') &&
                (strtolower($charset) != 'utf-8')) {
            $str = html_entity_decode($str, ENT_COMPAT, $charset);
            $str = preg_replace('~&#x(0*[0-9a-f]{2,5})~ei', 'chr(hexdec("\\1"))', $str);
            return preg_replace('~&#([0-9]{2,4})~e', 'chr(\\1)', $str);
        }

        // Numeric Entities
        $str = preg_replace('~&#x(0*[0-9a-f]{2,5});{0,1}~ei', 'chr(hexdec("\\1"))', $str);
        $str = preg_replace('~&#([0-9]{2,4});{0,1}~e', 'chr(\\1)', $str);

        // Literal Entities - Slightly slow so we do another check
        if (stristr($str, '&') === FALSE) {
            $str = strtr($str, array_flip(get_html_translation_table(HTML_ENTITIES)));
        }

        return $str;
    }

    /**
     * HTML Entity Decode Callback
     *
     * Used as a callback for XSS Clean
     *
     * @param	array
     * @return	string
     */
    public static function DecodeEntity($match) {
        global $SYSTEM_CHARSET;
        return Xss::EntityDecode($match[0], strtoupper($SYSTEM_CHARSET));
    }

    /**
     * Attribute Conversion
     *
     * Used as a callback for XSS Clean
     *
     * @param	array
     * @return	string
     */
    public static function ConvertAttribute($match) {
        return str_replace(array('>', '<', '\\'), array('&gt;', '&lt;', '\\\\'), $match[0]);
    }

    /**
     * Random Hash for protecting URLs
     *
     * @return	string
     */
    public static function XssHash() {
        if (Xss::$xssHash == '') {
            if (phpversion() >= 4.2) {
                mt_srand();
            } else {
                mt_srand(hexdec(substr(md5(microtime()), -8)) & 0x7fffffff);
            }

            Xss::$xssHash = md5(time() + mt_rand(0, 1999999999));
        }

        return Xss::$xssHash;
    }

    public static function ValidateEntities($str) {
        /*
         * Protect GET variables in URLs
         */
        // 901119URL5918AMP18930PROTECT8198

        $str = preg_replace('|\&([a-z\_0-9\-]+)\=([a-z\_0-9\-]+)|i', Xss::$xssHash . "\\1=\\2", $str);

        /*
         * Validate standard character entities
         *
         * Add a semicolon if missing.  We do this to enable
         * the conversion of entities to ASCII later.
         *
         */
        $str = preg_replace('#(&\#?[0-9a-z]{2,})([\x00-\x20])*;?#i', "\\1;\\2", $str);

        /*
         * Validate UTF16 two byte encoding (x00)
         *
         * Just as above, adds a semicolon if missing.
         *
         */
        $str = preg_replace('#(&\#x?)([0-9A-F]+);?#i', "\\1\\2;", $str);

        /*
         * Un-Protect GET variables in URLs
         */
        $str = str_replace(Xss::$xssHash, '&', $str);

        return $str;
    }

    public static function RemoveInvisibleChars($str,$urlEncoded = TRUE) {
        $nonDisplayables = array();
        // every control character except newline (dec 10)
        // carriage return (dec 13), and horizontal tab (dec 09)

        if ($urlEncoded) {
            $nonDisplayables[] = '/%0[0-8bcef]/'; // url encoded 00-08, 11, 12, 14, 15
            $nonDisplayables[] = '/%1[0-9a-f]/'; // url encoded 16-31
        }

        $nonDisplayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S'; // 00-08, 11, 12, 14-31, 127

        do {
            $str = preg_replace($nonDisplayables, '', $str, -1, $count);
        } while ($count);

        return $str;
    }
    
    
    // --------------------------------------------------------------------XSS HELPERS----------------------------------//
    
    // --------------------------------------------------------------------XSS CLEAN HELPERS----------------------------------//

    /*
     * URL Decode
     *
     * Just in case stuff like this is submitted:
     *
     * <a href="http://%77%77%77%2E%67%6F%6F%67%6C%65%2E%63%6F%6D">Google</a>
     *
     * Note: Use rawurldecode() so it does not remove plus signs
     *
     */

    public static function UrlDecode($str) {

        return rawurldecode($str);
    }

    /*
     * Convert character entities to ASCII
     *
     * This permits our tests below to work reliably.
     * We only convert entities that are within tags since
     * these are the ones that will pose security problems.
     *
     */

    public static function ConvertCharEntitiesToASCII($str) {

        $str = preg_replace_callback("/[a-z]+=([\'\"]).*?\\1/si", array('Xss', 'ConvertAttribute'), $str);

        $str = preg_replace_callback("/<\w+.*?(?=>|<|$)/si", array('Xss', 'DecodeEntity'), $str);

        return $str;
    }

    /*
     * Convert all tabs to spaces
     *
     * This prevents strings like this: ja	vascript
     * NOTE: we deal with spaces between characters later.
     * NOTE: preg_replace was found to be amazingly slow here on 
     * large blocks of data, so we use str_replace.
     */

    public static function ConvertTabsToSpace($str) {

        if (strpos($str, "\t") !== FALSE) {
            $str = str_replace("\t", ' ', $str);
        }
        return $str;
    }

    /*
     * Makes PHP tags safe
     *
     * Note: XML tags are inadvertently replaced too:
     *
     * <?xml
     *
     * But it doesn't seem to pose a problem.
     */

    public static function MakePhpTagSafe($str, $isImage) {

        if ($isImage === TRUE) {
            // Images have a tendency to have the PHP short opening and 
            // closing tags every so often so we skip those and only 
            // do the long opening tags.
            $str = preg_replace('/<\?(php)/i', "&lt;?\\1", $str);
        } else {
            $str = str_replace(array('<?', '?' . '>'), array('&lt;?', '?&gt;'), $str);
        }
        return $str;
    }

    /*
     * Compact any exploded words
     *
     * This corrects words like:  j a v a s c r i p t
     * These words are compacted back to their correct state.
     */

    public static function CompactExplodedWords($str) {

        foreach (Xss::$explodedWords as $word) {
            $temp = '';

            for ($i = 0, $wordlen = strlen($word); $i < $wordlen; $i++) {
                $temp .= substr($word, $i, 1) . "\s*";
            }

            // We only want to do this when it is followed by a non-word character
            // That way valid stuff like "dealer to" does not become "dealerto"
            $str = preg_replace_callback('#(' . substr($temp, 0, -3) . ')(\W)#is', array('Xss', 'StripExplodedWordsWhiteSpaces'), $str);
        }
        return $str;
    }

    /*
     * Remove disallowed Javascript in links or img tags
     * We used to do some version comparisons and use of stripos for PHP5, 
     * but it is dog slow compared to these simplified non-capturing 
     * preg_match(), especially if the pattern exists in the string
     */

    public static function RemoveDisallowedJsImgTags($str) {

        do {
            $original = $str;

            if (preg_match("/<a/i", $str)) {
                $str = preg_replace_callback("#<a\s+([^>]*?)(>|$)#si", array('Xss', 'JsLinkRemoval'), $str);
            }

            if (preg_match("/<img/i", $str)) {
                $str = preg_replace_callback("#<img\s+([^>]*?)(\s?/?>|$)#si", array($this, 'JsImgRemoval'), $str);
            }

            if (preg_match("/script/i", $str) OR preg_match("/xss/i", $str)) {
                $str = preg_replace("#<(/*)(script|xss)(.*?)\>#si", '', $str);
            }
        } while ($original != $str);

        unset($original);
        return $str;
    }

    /*
     * Sanitize naughty HTML elements
     *
     * If a tag containing any of the words in the list
     * below is found, the tag gets converted to entities.
     *
     * So this: <blink>
     * Becomes: &lt;blink&gt;
     */

    public static function SanitizeNaughtyHtmlElements($str) {

        $naughty = 'alert|applet|audio|basefont|base|behavior|bgsound|blink|body|embed|expression|form|frameset|frame|head|html|ilayer|iframe|input|isindex|layer|link|meta|object|plaintext|style|script|textarea|title|video|xml|xss';
        $str = preg_replace_callback('#<(/*\s*)(' . $naughty . ')([^><]*)([><]*)#is', array('Xss', 'SanitizeNaughtyHtml'), $str);

        return $str;
    }

    /*
     * Sanitize naughty scripting elements
     *
     * Similar to above, only instead of looking for
     * tags it looks for PHP and JavaScript commands
     * that are disallowed.  Rather than removing the
     * code, it simply converts the parenthesis to entities
     * rendering the code un-executable.
     *
     * For example:	eval('some code')
     * Becomes:		eval&#40;'some code'&#41;
     */

    public static function SanitizeNaughtyScriptingElements($str) {

        
        $str = preg_replace('#(alert|cmd|passthru|eval|exec|expression|system|fopen|fsockopen|file|file_get_contents|readfile|unlink)(\s*)\((.*?)\)#si', "\\1\\2&#40;\\3&#41;", $str);
        return $str;
    }
    
    // --------------------------------------------------------------------XSS CLEAN HELPERS----------------------------------//

}
?>
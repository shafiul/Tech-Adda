<?php

define('FILE_IFEXIST_FAIL', 1);
define('FILE_IFEXIST_OVERWRITE',2);
define('FILE_IFEXIST_RENAME',3);

define('FILE_EXT_NOEXT',4);
define('FILE_EXT_PRESERVE',5);
define('FILE_EXT_NEW',6);

define('FILE_MULEXT_FAIL',7);       ///< Upload fails
define('FILE_MULEXT_PRESERVE',8);   ///< Keep all extentions as is.
define('FILE_MULEXT_FILTER',9);     ///< Delete all extentions but the last one.


/**
 * \brief File uploading made-easy
 * 
 * File upload helper via web forms. Call this function after you've uploaded a file via web form
 * 
 * @author Shafiul Azam
 * 
 */


class Files{
    
    public $name;                       ///< "name" attribute of the <input> tag used for the file
    public $targetDir;                  ///< directory to which the file would be copied, WITHOUT ANY TRAILING SLASH (/)
    public $targetFileName = null;      ///< if non-empty, file would be renamed to this name (WITHOUT EXTENTION)
    public $sizeLimit = null;           ///< if non-empty, this number specifies the maximum number of BYTES the file can be in size
    public $AllowedMimeArr = null;      ///< Array of mime type strings, if non-empty the file must be one of these mime type.
    public $ExtWhitelist = null;        ///< Array of allowed extention strings. You should provide at least one element in the array otherwise upload will fail.
    public $targetFilePermission;       ///< Permissions of uploaded file in a Unix server
    
    public $ifFileExists;               ///< What to do if target file already exists. See constatnts
    public $ifFileExistsAppendName = null;       ///< If target file already exists & $ifFileExists set to constant FILE_IFEXIST_RENAME, append my value to the filename. If null (not provided): it uses current date time.
    
    public $targetFileExtType;          ///< How to define EXTENTION of the target file. See Constants
    public $targetFileExt;              ///< EXTENTION to apply to the target file, if $targetFileExtType is set to constant FILE_EXT_NEW
    
    public $MultipleExtType;           ///< How to handle file with multiple extentions (like: myphoto.php.jpg ) - See constant

    public function __construct() {
        // Set some default configuration
        $this->ifFileExists = FILE_IFEXIST_FAIL;    ///< For Security issues
        $this->targetFilePermission = 0664; // For files
//        $this->targetFilePermission = 0774; // For directories
        $this->targetFileExtType = FILE_EXT_PRESERVE;   ///< Same as original file extention
    }
    
    
    
    public function ifExists(){
        
        
    }
    
    public function isEmpty(){
        
    }
    
    
    /**
     * 
     * 
     * @return array
     *  -   0th Element: bool | true if file upload successful / No file was selected by user for upload.
     *  -   1st Element: string | status string to be presented to user 
     *  -   2nd Element: Full file loacation path of the uploaded file.
     *      -   Special case:
     *      -   If no file was selected by the user, 0th element is true, 1st element is string "NIL"  
     */
    
    
    
    public function upload() {
        // file upload processing! $name is the NAME attribute of the <input> tag.
        // If $targetFileName is provided, that name will be used when saving the uploaded file
        // otherwise original name will be used while saving the uploaded file.
        // RETURNS FALSE FOR SUCCESSFUL UPLOAD, OTHERWISE AN ERROR MESSAGE.
        //error checking
        if ($_FILES[$this->name]["size"] <= 0)
            return array(true,"No file selected for upload!",null);   // No file selected -- no upload!
        if ($_FILES[$this->name]["error"] > 0)
            return array(false,$_FILES["file"]["error"]);
        // size check
        if ($this->sizeLimit && ($_FILES[$this->name]["size"] > $this->sizeLimit))
            return array(false,"File to large, max limit is " . $this->sizeLimit . " bytes");
        // MIMETYPE CHECK
        if (!empty($this->mimeTypeArr)) {
            if (!in_array(strtolower($_FILES[$this->name]["type"]), $this->mimeTypeArr))
                return array(false,"This type of file (" . $_FILES[$this->name]['type'] . ") is not allowed for upload.");
        }
        // Check extention 
        $pathinfo = pathinfo($_FILES[$this->name]['name']);
        $fileExtention = strtolower($pathinfo['extension']);
        $fileName = $pathinfo['filename'];
        
        // Care multiple extentions.
        
        $fileExtParts = explode('.', $fileName);
        $hasMulExt = (count($fileExtParts) > 1)?(true):(false);
        
        switch ($this->MultipleExtType){
            case FILE_MULEXT_PRESERVE:
                break;
            case FILE_MULEXT_FAIL:
                if($hasMulExt)
                    return array(false, 'Files with many extentions are not allowed!');
                break;
            case FILE_MULEXT_FILTER:
                $fileName = $fileExtParts[0];
                break;
        }
        
        // Validate Extentions
        
        if(!in_array($fileExtention, $this->ExtWhitelist))
            return array(false,"This file extention is not allowed.");
        
        // GENERATE TARGET FILE PATH.
        
        if (!$this->targetFileName)
            $this->targetFileName = $fileName;
        
        $targetPath = $this->targetDir . '/' . $this->targetFileName;
        
        // Care extentions
        
        switch ($this->targetFileExtType){
            case FILE_EXT_PRESERVE:
                $targetPath .= '.' . $fileExtention;
                break;
            case FILE_EXT_NOEXT:
                break;
            case FILE_EXT_NEW:
                $targetPath .= '.' . $this->targetFileExt;
        }
        
        // What if file already exists?
        
        if(file_exists($targetPath)){
            switch ($this->ifFileExists){
                case FILE_IFEXIST_OVERWRITE:
                    break;
                case FILE_IFEXIST_FAIL:
                    return array(false,"File already exists. Try again renaming the file.");
                    break;
                case FILE_IFEXIST_RENAME:
                    $pathinfo = pathinfo($targetPath);
                    if(!$this->ifFileExistsAppendName)
                        $this->ifFileExistsAppendName = '_' . date("jMY-g-i-a", time());
                    $targetPath = $pathinfo['dirname'] . "/" . $pathinfo['filename'] . $this->ifFileExistsAppendName . "." . $pathinfo['extension'];
                    break;
            }
        }
        // UPLOAD
        if (move_uploaded_file($_FILES[$this->name]['tmp_name'], $targetPath)) {
            chmod($targetPath, $this->targetFilePermission);
        } else {
            //echo $_FILES[$this->name]['tmp_name'] . ":" . $targetPath;
            //exit();
            return array(false,"Can not move file. Upload failed.",$targetPath);
        }
        return array(true,"Upload successful!", $targetPath);
    }
    
    /**
     * Static Method for deleting any file.
     * 
     * @param string $fileFullPath | loction of the file to delete
     * @return bool 
     *  - true | if delete was successful.
     *  - false | if delete unsuccessful/file not found.
     */
    
    public static function delete($fileFullPath){
        if(file_exists($fileFullPath)){
//            echo 'file found!';
            return unlink($fileFullPath);
        }
        return false;
    }
    
    
}


?>

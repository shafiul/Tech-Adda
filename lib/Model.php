<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Model
 *
 * @author ibrahim
 */
class Model {

    protected $db;
    public $repoName;

    public function __construct(Sparrow $db, $repoName) {
        $this->db = $db;
        $this->repoName = $repoName;
    }

    /**
     * Universal Db Get
     *
     * @param string|array $filters as Where condiation 
     * @param number $start offset
     * @param number $limit result limiting
     * @param array|string joinRepo
     * @param array joinRepo Condition
     * @param string "Asc|Desc" $order Sorting Type
     * @param string $orderBy sorting column     
     * @return Array of Query Result
     */
    public function get($filters="", $start=-1, $limit=10, $joinRepo=array(), $joinCond=array(), $order="Asc", $orderBy="") {
        $data = array();
        try {
            $this->db->from($this->repoName);

            if ($filters != "" && !empty($filters)) {
                $this->db->where($filters);
            }
            if ($joinRepo != "" && !empty($joinCond)) {
                $this->db->join($joinRepo, $joinCond);
            }
            if ($orderBy != "" && !empty($orderBy)) {
                $this->db->sort{$order}($orderBy);
            }
            if ($start != -1) {
                $this->db->offset($start);
                $this->db->limit($limit);
            }
            //echo   $this->db->select()->sql()."<br>";
            
            if ($limit != 1)
                $data = $this->db->select()->many();
            else
                $data = $this->db->select()->one();
        } catch (ErrorException $e) {
            App::logMsg("Message\n", "Debug");
            App::logMsg($e->getMessage());
        } catch (Exception $e) {
            App::logMsg("Message\n", "Debug");
            App::logMsg($e->getMessage(), "Debug");
            App::logMsg("Stack Trace\n", "Debug");
            App::logMsg($e->getTraceAsString(), "Debug");
        }        
        return $data;
    }
    
    
     /**
     * Is Exists In database
     * @param array $filters as Where condiation      
     * @return Array of Query Result
     */
    public function isExists($data){
        $data = $this->get($data);
        if(!empty($data))
            return true;
        return false;
    }
    /**
     * Insert into database
     * @param array $data as insert data's
     * @return boolean true if success  or false 
     */
    public function insert($data){                                            
        return $this->db->from($this->repoName)
                    ->insert($data)
                    ->execute();
    }
        
    /**
     * Insert into database if not exits with given condition
     * @param array $data as insert data's
     * @return boolean|int true/false if no id or insert_id
     */
    public function insertIfNotExists($data){
        if(!$this->isExists($data)){
            $this->insert($data);
            return $this->db->insert_id;
        }
        else
            return false;
    }
    /**
     * Update database with given filters with data     
     * @param array $filters as Where condition
     * @param array $data as insert data's
     * @return boolean true if success  or false 
     */
    public function update($filters,$data){
        return  $this->db->from($this->repoName)
                    ->where($filters)
                    ->update($data)
                    ->execute();

    }
    
    /**
     * Remove Db Entry with condition
     * @param array $filters as Where condition     
     * @return boolean true if success  or false 
     */
    public function remove($filters){
        return  $this->db->from($this->repoName)
                        ->where($filters)
                        ->delete()
                        ->execute();
                            
    }
    
    /**
     * Escape array with mysql_real_escape recursively
     * @param array $array as Array of data to Escape
     * @return array Escaped Data as Array
     */

    public function recursiveEscape($array) {
        
        if (is_array($array)) {
            foreach ($array as $key => $val) {
                $array[$key] = $this->db->escape($val);
            }
            return $array;
        }
        else
            return $this->db->escape ($array);
        
    }
    
    public function getCount($filters){
        return  $this->db->from($this->repoName)                        
                        ->where($filters)
                        ->count();                        
                
    }

}

?>

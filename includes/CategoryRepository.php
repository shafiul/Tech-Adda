<?php

class CategoryRepository extends Model
{
    /**
     * @var Sparrow
     */
    protected $db;
  

    public function __construct(Sparrow $db)
    {
        $repoName = TABLE_CATEGORIES;
        $this->db = $db;
        parent::__construct($db,$repoName);        
    }

    public function getAllCategories()
    {
        return $this->get();        
    }

    public function getCategoryById($categoryId)
    {    
        $filters = array("category_id"=>$categoryId);
        $filters = $this->recursiveEscape($filters);
        return $this->get($filters,0,1);        
    }
    
    function create($data){
        $this->recursiveEscape($data);
        return $this->insertIfNotExists($data);
    }
    
    function delete($data){
        return $this->remove($data);
    }
    
    
}
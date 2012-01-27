<?php

class UserRepository extends Model
{
    /**
     * @var Sparrow
     */
    protected $db;

    public function __construct(Sparrow $db)
    {
        $repoName = TABLE_USERS;
        $this->db = $db;
        parent::__construct($db,$repoName);        
    }

    public function getUserByEmail($email)
    {
        $filters = array(
            'email'=> $email
        );          
        $filters = $this->recursiveEscape($filters);
        return $this->get($filters,0,1);
    }
    public function getUserByName($name){
        $filters = array(
            'name'=> $name
        );          
        $filters = $this->recursiveEscape($filters);
        return $this->get($filters,0,1);
    }
    public function getUesrByTwitterId($twitterId){
        $filters = array(
            'twitter_id'=> $twitterId
        );          
        $filters = $this->recursiveEscape($filters);
        return $this->get($filters,0,1);
    }

    public function create($email) {
        
        $data = array(
            'email' =>  $email
            );
        $data = $this->recursiveEscape($data);
        
        return $this->insert($data);
    }
    
    public function createByName($name,$twitterId){
        $data = array(
            'name' => $name,
            'twitter_id'=>$twitterId
            );
        $data = $this->recursiveEscape($data);
        
        return $this->insert($data);
    }
    
    public function updateUsername($usrename,$userId){
        $result = $this->update(array('user_id' => $userId), array('name' => $usrename));
        return $result;
    }

}

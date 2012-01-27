<?php

class CommentsRepository extends Model
{
    /**
     * @var Sparrow
     */
    protected $db;
    

    public function __construct(Sparrow $db)
    {
        $repoName = TABLE_COMMENTS;
        $this->db = $db;
        parent::__construct($db,$repoName);        
    }

    public function getCommentsByTalk($talkId,$start=0,$limit=10)
    {   
        $filters = array("talk_id"=>$talkId);
        $joinRepo = "users";
        $joinCond = array('comments.user_id'=>'users.user_id');
        $filters = $this->recursiveEscape($filters);
        
        $data['result'] = $this->get($filters,$start,$limit,$joinRepo,$joinCond);        
        $data['num_rows'] = $this->getTotalCommentsCountByTalk($talkId);
        return $data;
    }
    public function getCommentsByEvent($eventId,$start=0,$limit=10)
    {   
        $filters = array("event_id"=>$eventId);
        $joinRepo = "users";
        $joinCond = array('comments.user_id'=>'users.user_id');
        $filters = $this->recursiveEscape($filters);
        
        $data['result'] = $this->get($filters,$start,$limit,$joinRepo,$joinCond);        
        $data['num_rows'] = $this->getTotalCommentsCountByEvent($eventId);
        return $data;
    }
    public function getTotalCommentsCountByTalk($talkId){
        $filters = array('talk_id'=>$talkId);
        return $this->getCount($filters);
        
    }
    public function getTotalCommentsCountByEvent($eventId){
        $filters = array('event_id'=>$eventId);
        return $this->getCount($filters);
        
    }
    public function getAllCommentsByTalk($talkId)
    {           
        return $this->getCommentsByTalk($talkId, -1, 0);
    }
    

    public function create($data)
    {        
        $data['user_id'] = $_SESSION['user']['user_id'];        
        $data = $this->recursiveEscape($data);        
        return $this->insert($data);        
    }
    
    
}
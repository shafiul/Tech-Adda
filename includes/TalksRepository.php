<?php

class TalksRepository extends Model
{
    /**
     * @var Sparrow
     */
    protected $db;

    public function __construct(Sparrow $db)
    {
        $repoName = TABLE_TALKS;
        $this->db = $db;
        parent::__construct($db,$repoName);        
    }

    public function getTalksByEvent($eventId)
    {
        $filters= array('event_id = '=> $eventId);
        return $this->get($filters);
    }

    public function getTalkById($talkId)
    {
        $filters = array('talk_id = '=> $talkId);
        return $this->get($filters,0,1);
    }
    
    public function create($data)
    {        

        //$data['user_id'] = $_SESSION['user']['user_id'];
        $data = $this->recursiveEscape($data);
        $this->insert($data);
        return $this->db->insert_id;
    }
    
    
}
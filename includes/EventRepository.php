<?php

class EventRepository extends Model
{
    /**
     * @var Sparrow
     */
    protected $db;

    public function __construct(Sparrow $db)
    {
        $repoName = TABLE_EVENTS;
        $this->db = $db;
        parent::__construct($db,$repoName);        
    }
    
    public function getAll(){
        return $this->get();
    }

    public function getActiveEvents($start=-1,$limit=10)
    {
        $filters = array("is_active = "=>1);
        $filters = $this->recursiveEscape($filters);
        return $this->get($filters,$start,$limit);        
    }
    public function getActiveEventsCount(){
        $filters = array("is_active = "=>1);
        return $this->getCount($filters);
    }

    public function getActiveEventsByCategory($categoryId)
    {        
        $filters = array(            
            'category_id'=> $categoryId,
            'is_active'=> 1
            );
        $filters = $this->recursiveEscape($filters);
        return $this->get($filters);
        
    }

    public function getEventById($eventId)
    {
        $filters = array('event_id = '=> $eventId);
        return $this->get($filters,0,1);
    }

    public function create($data)
    {        
        $data['user_id'] = $_SESSION['user']['user_id'];        
        $data = $this->recursiveEscape($data);        
        $this->insert($data);
        return $this->db->insert_id;
    }
}
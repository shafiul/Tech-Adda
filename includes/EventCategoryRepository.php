<?php

class EventCategoryRepository extends Model
{
    /**
     * @var Sparrow
     */
    protected $db;

    public function __construct(Sparrow $db)
    {
        $repoName = TABLE_EVENTS_CATEGORY;
        $this->db = $db;
        parent::__construct($db,$repoName);        
    }
    
    public function getAll(){
        return $this->get();
    }
    
    public function insertAll($catArr,$eventID){
        foreach($catArr as $catID){
            $data = $this->recursiveEscape($data);
            $this->insertIfNotExists($data);
        }
    }
    
    
    public function getEventsByCatId($catId){
        $events =  $this->get(array('category_id' => $catId));
        
//        var_dump($events);
//        exit();
        
        $eventRepo = App::getRepository('Event');
        
        $eventsArr = array();
        
        foreach($events as $eventId){
//            var_dump($eventId);
//            exit();
            $event = $eventRepo->get(array(
                'event_id' => $eventId['event_id']
            ));
//            var_dump($event[0]);
            if(isset($event[0]))
                $eventsArr[] = $event[0];
//            else
//                echo 'not found';
        }
        return $eventsArr;
    }
    
    
}
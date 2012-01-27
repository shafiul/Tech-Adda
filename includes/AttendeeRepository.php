<?php

class AttendeeRepository extends Model {

    /**
     * @var Sparrow
     */
    protected $db;

    public function __construct(Sparrow $db) {
        $repoName = TABLE_ATTENDEES;
        $this->db = $db;
        parent::__construct($db, $repoName);
    }

    public function create($data) {
        $this->recursiveEscape($data);
        return $this->insertIfNotExists($data);
    }

    /**
     * 
     * @param type $userid 
     */
    public function attendingWhat($userid) {
        $attendies = $this->get(array('user_id' => $userid));
        $attendanceResult = array();

        foreach ($attendies as $row) {
            $attendanceResult[] = $row['event_id'];
        }
        return $attendanceResult;
    }

    public function isUserAttendingEvent($userId, $eventId) {
        $result = $this->get(
                array('user_id' => $userId, 'event_id' => $eventId)
        );
        if(empty($result))
            return false;
        else
            return true;
    }
    
    public function whosAttending($eventId){
//        $attendees = $this->get(array('event_id' => $eventId),-1,0,'user',array('users.user_id' => 'attendees.user_id'));
        $attendees = $this->get(array('event_id' => $eventId));
//        var_dump($attendees);
//        exit();
        $emails = array();
        
        $userRep = App::getRepository('User');
        
        foreach($attendees as $i){
            $user = $userRep->get(array('user_id' => $i['user_id']));
            $emails[] = $user[0]['email'];
        }
//        var_dump($emails);
//        exit();
        return $emails;
    }

}

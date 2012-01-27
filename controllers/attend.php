<?php
class Attend extends Controller{
    
    public function index(){
        $user = $this->authenticateUser();
        $id = $this->input('id'); // got event ID
        // insert if not exits
        $data = array(
            'user_id' => $user['user_id'],
            'event_id' => $id
        );
//        var_dump($data);
        $result = App::getRepository('Attendee')->create($data);
        if($result === false)
            $this->msgExit ('You are already attending the event!', MSG_WARNING);
        else{
            // Update other tables
            $eventRepo = App::getRepository('Event');
            $event = $eventRepo->get(array('event_id' => $id));
//            var_dump($event[0]);
//            exit();
//            echo '<hr />';
//            var_dump($event[0]['total_attending']);
            $attendeeCount = intval($event[0]['total_attending']) + 1;
//            var_dump($attendeeCount);
//            exit();
            $updateResult = $eventRepo->update(array('event_id' => $id), array('total_attending' => $attendeeCount));
//            var_dump($updateResult);
//            exit();
            $this->msgExit ('Thank you for attending the event!', MSG_SUCCESS);
        }
    }
    
}
?>

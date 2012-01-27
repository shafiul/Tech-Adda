<?php
class AttendAjax extends Controller{
    
    
    public function output($output){
        $str = json_encode($output);
        header('Content-Type: text/plain');
        echo $str;
        exit();
    }
    
    public function index(){
        $output = array();
        
        $user = $this->authenticateUser('', false);
        $userId = ($user) ? ($user['user_id']) : (0);
        
        if(!$userId){
            $this->output(array(false, 'Log in please!'));
        }
        
        $validate = new FormValidation($this);
        $validate->requiredElements = array('event_id');
        $fResult = $validate->prepare();
        
//        $id = $this->input('id','',false); // got event ID
        
        if($validate->error){
            $this->output(array(false,'ID can not be empty'));
        }
        
        $id = $fResult['event_id'];
        
        // insert if not exits
        $data = array(
            'user_id' => $user['user_id'],
            'event_id' => $id
        );
        
//        var_dump($data);
        $result = App::getRepository('Attendee')->create($data);
        if($result === false)
            $this->output (array(false,'You are already attending the event!'));
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
            $this->output(array(true,'Thank you for attending the event!'));
        }
    }
    
}
?>

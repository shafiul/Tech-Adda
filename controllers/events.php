<?php

class Events extends Controller {

    function index() {
        
        $pageNo = $this->input("pageNo","",false,1);      
        $start = ($pageNo-1)*PAGINATION_LIMIT;
        
        
        $successUrl = '?page=events';
        $data['activeEvents'] = App::getRepository('Event')->getActiveEvents($start,PAGINATION_LIMIT);
        $totalActiveEvents = App::getRepository('Event')->getActiveEventsCount();
        
        $data['paginationHtml'] = ViewHelper::getPagination($totalActiveEvents, $successUrl,PAGINATION_LIMIT,$pageNo);
        
        $data['categories'] = App::getRepository('Category')->getAllCategories();
        $user = $this->authenticateUser('', false);
        $userId = ($user) ? ($user['user_id']) : (0);
        $data['afterjs'] = array('js/attendEvent.js');
        $data['attendance'] = App::getRepository('Attendee')->attendingWhat($userId);
        // prepare view
        $this->loadView('events', $data);
    }

}

?>

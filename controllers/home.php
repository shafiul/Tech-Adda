<?php

class Home extends Controller {

    function index() {

        $pageNo = $this->input("pageNo","",false,1);      
        $start = ($pageNo-1)*PAGINATION_LIMIT;
        
        
        $successUrl = '?page=home';
        $activeEvents = App::getRepository('Event')->getActiveEvents($start,PAGINATION_LIMIT);
        $totalActiveEvents = App::getRepository('Event')->getActiveEventsCount();
        
        $data['paginationHtml'] = ViewHelper::getPagination($totalActiveEvents, $successUrl,PAGINATION_LIMIT,$pageNo);
        
        $categories = App::getRepository('Category')->getAllCategories();

        $user = $this->authenticateUser('', false);
        $userId = ($user) ? ($user['user_id']) : (0);

        $attendanceResult = App::getRepository('Attendee')->attendingWhat($userId);



        $data['activeEvents'] = $activeEvents;
        $data['categories'] = $categories;
        $data['attendance'] = $attendanceResult;
        $data['afterjs'] = array('js/attendEvent.js');
        $data['sidebars'] = array('categories', 'addEvent');

        $this->loadView('home', $data);
    }

}

?>

<?php

class Cat extends Controller {

    function index() {

        $id = $this->input('id');
        
        $data['category'] = App::getRepository('Category')->getCategoryById($id);
//        $data['activeEvents'] = App::getRepository('Event')->getActiveEventsByCategory($data['category']['category_id']);
        
        $events = App::getRepository('EventCategory')->getEventsByCatId($id);
//        var_dump($events);
        
        $data['activeEvents'] = $events;
        
//        var_dump($data['activeEvents']);
        
//        exit();
        
        $data['categories'] = App::getRepository('Category')->getAllCategories();

        // preapare view
        $this->loadView('cat', $data);
    }

}

?>

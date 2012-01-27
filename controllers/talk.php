<?php

class Talk extends Controller {

    function index() {

        $talkId = $this->input("id", "", true);
        $pageNo = $this->input("pageNo", "", false, 1);


        $data['talk'] = App::getRepository('Talks')->getTalkById($talkId);

        $eventId = $data['talk']['event_id'];
        $data['event'] = App::getRepository('Event')->getEventById($eventId);

//        var_dump($data['talk']);
//        var_dump($data['event']);

        $successUrl = "?page=talk&id=$talkId";

        $start = ($pageNo - 1) * PAGINATION_LIMIT;

        $result = App::getRepository('Comments')->getCommentsByTalk($talkId, $start, PAGINATION_LIMIT);

        $data['comments'] = $result['result'];

        $totalComments = $result['num_rows'];

        $data['paginationHtml'] = ViewHelper::getPagination($totalComments, $successUrl, PAGINATION_LIMIT, $pageNo);


        $data['js'] = array(
            'js/jquery-validate.js',
            'js/jquery-validate-extra.js',
            'js/jquery-rating.js'
        );
        $data['css'] = array('css/jquery-rating.css');

        $data['talkAction'] = $successUrl;
        $this->loadView('talk', $data);
    }

    function prepareView() {
        
    }

}

?>

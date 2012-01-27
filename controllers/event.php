<?php

class Event extends Controller {

    function index() {

        $validator = new FormValidation($this);

        $validator->optionalElements = array('mode');
        $result = $validator->prepare();

        $id;
        if ($result['mode'] == 'event') {
            $this->addComment();
            $id = $result['modeId'];
        } else {
//            die('ehe');
            $id = $this->input('id');
        }



        $data['event'] = App::getRepository('Event')->getEventById($id);
        $data['talks'] = App::getRepository('Talks')->getTalksByEvent($id);
        $data['categories'] = App::getRepository('Category')->getAllCategories();
        $data['afterjs'] = array('js/attendEvent.js');
        $user = $this->authenticateUser('', false);
        $userId = ($user) ? ($user['user_id']) : (0);

        $data['isAttending'] = App::getRepository('Attendee')->isUserAttendingEvent($userId, $id);
        
        $successUrl = "?page=event&id=$id";
        $pageNo = $this->input("pageNo", "", false, 1);
        $start = ($pageNo - 1) * PAGINATION_LIMIT;
        $result = App::getRepository('Comments')->getCommentsByTalk($id, $start, PAGINATION_LIMIT);
        $data['comments'] = $result['result'];
        $totalComments = $result['num_rows'];
        $data['paginationHtml'] = ViewHelper::getPagination($totalComments, $successUrl, PAGINATION_LIMIT, $pageNo);

        // who's attending
        $whosAtt = $this->whosAttending($id);
//        var_dump($whosAtt);
//        exit();
        // Prepare view
        $data['gravatarURLs'] = $whosAtt;
        $data['sidebars'] = array('gravatar');

        $data['js'] = array('js/jquery-validate.js', 'js/jquery-validate-extra.js', 'js/jquery-rating.js');
        $data['css'] = array('css/jquery-rating.css');

        $this->loadView('event', $data);
    }

    private function addComment() {
        $validator = new FormValidation($this);
        $validator->requiredElements = array('rating', 'body', 'modeId');
        $result = $validator->prepare();

        $errors = array();

        if ($validator->error)
            $errors[] = $validator->error;

        if (!empty($errors)) {
            $this->setMsg(implode('<br />', $errors), MSG_ERROR);
        } else {
            $data = array(
                'event_id' => $result['modeId'],
                'type' => 'event',
                'body' => $result['body'],
                'rating' => $result['rating']
            );
            $dbResult = App::getRepository('Comments')->create($data);
            if ($dbResult)
                $this->setMsg('Successfully saved your comment!', MSG_SUCCESS);
        }
    }

    private function whosAttending($eventId) {
        $attendees = App::getRepository('Attendee')->whosAttending($eventId);
//        var_dump($attendees);
//        exit();
        $output = array();
        foreach ($attendees as $email) {
            $output[] = $this->get_gravatar($email);
        }
        return $output;
    }

    /**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     *
     * @param string $email The email address
     * @param string $s Size in pixels, defaults to 80px [ 1 - 512 ]
     * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
     * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
     * @param boole $img True to return a complete IMG tag False for just the URL
     * @param array $atts Optional, additional key/value attributes to include in the IMG tag
     * @return String containing either just a URL or a complete image tag
     * @source http://gravatar.com/site/implement/images/php/
     */
    function get_gravatar($email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array()) {
        $url = 'http://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$s&d=$d&r=$r";
        if ($img) {
            $url = '<img src="' . $url . '"';
            foreach ($atts as $key => $val)
                $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }
        return $url;
    }

}

?>
